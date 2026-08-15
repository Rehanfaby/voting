<?php

namespace App\Http\Controllers;

use App\Employee;
use App\GeneralSetting;
use App\Http\Requests\StorePointRequest;
use App\Judge;
use App\Point;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class PointController extends Controller
{

    private function getJudgeRoleId() {
        return Role::where('name', 'judge')->first()->id;
    }

    public function index()
    {
        $judgeRole = Role::whereRaw('LOWER(name) = ?', ['judge'])->first();
        $isJudge = $judgeRole && (int) Auth::user()->role_id === (int) $judgeRole->id;
        $grading_disabled = !$this->isGradingAvailable();

        $query = Point::with(['judge:id,name', 'contestant:id,name,image'])
            ->whereHas('contestant', function ($q) {
                $q->where('is_active', true)->where('is_approve', true);
            });

        if ($isJudge) {
            $query->where('judge_id', Auth::id());
        }

        $points = $query->latest()->get();

        return view('points.index', compact('points', 'grading_disabled'));
    }

    public function create(Request $request, $candidate_id = null)
    {
        $candidate_id = $candidate_id ?? $request->candidate_id;
        $candidate_name = null;
        if ($candidate_id) {
            $candidate = Employee::publiclyListed()->where('id', $candidate_id)->first(['id', 'name']);
            $candidate_name = $candidate ? $candidate->name : null;
        }

        $judge_role_id = (int) $this->getJudgeRoleId();
        $isJudge = (int) Auth::user()->role_id === $judge_role_id;

        // Mobile grading path: card → create/{id} — skip loading full judge/candidate lists.
        if ($isJudge && $candidate_id) {
            $judges = collect();
            $candidates = collect();
        } else {
            $judges = User::where('is_deleted', false)->where('role_id', $judge_role_id)->get(['id', 'name']);
            if ($this->isGradingAvailable()) {
                $candidates = Employee::publiclyListed()->orderBy('name')->get(['id', 'name']);
            } else {
                $candidates = collect();
            }
        }

        return view('points.create', compact('judges','candidates', 'candidate_id', 'candidate_name', 'judge_role_id'));
    }

    public function store(StorePointRequest $request)
    {
        if (!$this->isGradingAvailable()) {
            return back()->with('not_permitted', trans('file.Grading is not enabled yet'));
        }

        $data = $request->validated();
        foreach (['depth', 'accuracy', 'interpretation', 'song_choice', 'overall_presentation'] as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = round((float) $data[$field], 2);
            }
        }
        $point = Point::create($data);
        $point->calculateTotal();
        $point->save();
        Cache::forget('grader_dash_stats_judge_' . Auth::id());

        return redirect()->route('points.awaiting_candidates')->with('success', 'Point saved successfully');
    }

    public function show(Point $point)
    {
        $point->load(['judge','contestant']);
        return view('points.show', compact('point'));
    }

    public function edit(Point $point)
    {
//        $judges = Judge::orderBy('name')->where('is_active', true)->get();
        $judge_role_id = (int) $this->getJudgeRoleId();
        $judges = User::where('is_deleted', false)->where('role_id', $judge_role_id)->get(['id', 'name']);
        $candidates = Employee::publiclyListed()->orderBy('name')->get(['id', 'name']);
        return view('points.edit', compact('point','judges','candidates', 'judge_role_id'));
    }

    public function update(StorePointRequest $request, $id)
    {
        $data = $request->validated();
        foreach (['depth', 'accuracy', 'interpretation', 'song_choice', 'overall_presentation'] as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = round((float) $data[$field], 2);
            }
        }
        $point = Point::where('id', $id)->update([
            'depth' => $data['depth'],
//            'diction' => $data['diction'],
            'accuracy' => $data['accuracy'],
            'interpretation' => $data['interpretation'],
//            'technique' => $data['technique'],
//            'stage_presence' => $data['stage_presence'],
            'song_choice' => $data['song_choice'],
            'overall_presentation' => $data['overall_presentation'],
//            'adaptability' => $data['adaptability'],
//            'audience_interaction' => $data['audience_interaction'],
        ]);
        $point = Point::where('id', $id)->first();
        $point->calculateTotal();
        $point->save();
        Cache::forget('grader_dash_stats_judge_' . Auth::id());

        return redirect()->route('points.awaiting_candidates')->with('success', 'Point updated');
    }

    public function destroy(Point $point)
    {
        $role = Role::find(Auth::user()->role_id);
        if (!$role || !$role->hasPermissionTo('points_delete')) {
            return redirect()->route('points.index')->with('not_permitted', 'You do not have permission to delete this!');
        }

        $judgeRole = Role::whereRaw('LOWER(name) = ?', ['judge'])->first();
        $isJudge = $judgeRole && (int) Auth::user()->role_id === (int) $judgeRole->id;
        if ($isJudge && (int) $point->judge_id !== (int) Auth::id()) {
            return redirect()->route('points.index')->with('not_permitted', 'You do not have permission to delete this!');
        }

        $point->delete();
        Cache::forget('grader_dash_stats_judge_' . Auth::id());
        return redirect()->route('points.index')->with('success', 'Point deleted');
    }

    public function getRatedContestants($judgeId)
    {

        // Example: contestants the judge has already rated

        if ($this->isGradingAvailable() == false) {
            return response()->json([]);
        }
        $ratedContestants = Point::where('judge_id', $judgeId)
            ->pluck('candidate_id')
            ->toArray();

        // Fetch all contestants with a "rated" flag
        $contestants = Employee::publiclyListed()->get(['id', 'name'])->map(function ($contestant) use ($ratedContestants) {
            return [
                'id'     => $contestant->id,
                'name'   => $contestant->name,
                'rated'  => in_array($contestant->id, $ratedContestants),
            ];
        });

        return response()->json($contestants);
    }

    public function awaitingCandidates()
    {
        $user_id = (int) Auth::id();
        $user_role = (int) Auth::user()->role_id;
        $judgeRole = Role::whereRaw('LOWER(name) = ?', ['judge'])->first();
        $judge_role_id = $judgeRole ? (int) $judgeRole->id : 0;
        $isJudge = $judge_role_id && $user_role === $judge_role_id;
        $adminView = !$isJudge;
        $grading_disabled = !$this->isGradingAvailable();

        $query = Employee::publiclyListed()
            ->orderBy('name')
            ->select(['id', 'name', 'image']);

        if ($isJudge) {
            $gradedIds = Point::where('judge_id', $user_id)
                ->pluck('candidate_id')
                ->filter()
                ->unique()
                ->values()
                ->all();
            if (!empty($gradedIds)) {
                $query->whereNotIn('id', $gradedIds);
            }
        }

        $awaiting_candidates = $query->get();

        return view('points.awaiting_candidates', compact(
            'awaiting_candidates',
            'adminView',
            'grading_disabled'
        ));
    }

    public function deleteBySelection(Request $request)
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('points_delete')) {
            $ids = array_filter((array) $request->ids);
            if ($ids) {
                $query = Point::whereIn('id', $ids);
                $judgeRole = Role::whereRaw('LOWER(name) = ?', ['judge'])->first();
                $isJudge = $judgeRole && (int) Auth::user()->role_id === (int) $judgeRole->id;
                if ($isJudge) {
                    $query->where('judge_id', Auth::id());
                }
                $query->delete();
                Cache::forget('grader_dash_stats_judge_' . Auth::id());
            }
            return 'Grading deleted successfully!';
        } else {
            return 'You do not have permission to delete this!';
        }
    }

    private function isGradingAvailable()
    {
        return \App\Helpers\VoteSettings::gradingEnabled();
    }

}
