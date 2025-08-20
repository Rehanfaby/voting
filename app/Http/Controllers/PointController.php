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
use Spatie\Permission\Models\Role;

class PointController extends Controller
{

    private function getJudgeRoleId() {
        return Role::where('name', 'judge')->first()->id;
    }

    public function index()
    {
        if ($this->isGradingAvailable()) {
            if(Auth::user()->role_id == $this->getJudgeRoleId()) {
                $points = Point::with(['judge','contestant'])->where('judge_id', Auth::user()->id)->latest()->get();
            } else {
                $points = Point::with(['judge','contestant'])->latest()->get();
            }
        } else {
            $points = [];
        }


        return view('points.index', compact('points'));
    }

    public function create(Request $request, $candidate_id = null)
    {
        $candidate_id = $candidate_id ?? $request->candidate_id;
        $candidate_name = null;
        if ($candidate_id) {
            $candidate_name = Employee::where('id', $candidate_id)->where('is_active', true)->where('is_approve', true)->first()->name;
        }

        $judge_role_id = Role::where('name', 'judge')->first()->id;
        $judges = User::where('is_deleted', false)->where('role_id', $judge_role_id)->get();
        if ($this->isGradingAvailable()) {
            $candidates = Employee::orderBy('name')->where('is_active', true)->where('is_approve', true)->get();
        } else {
            $candidates = [];
        }
        return view('points.create', compact('judges','candidates', 'candidate_id', 'candidate_name'));
    }

    public function store(StorePointRequest $request)
    {
        $data = $request->validated();
        $point = Point::create($data);
        $point->calculateTotal();
        $point->save();

        return redirect()->route('points.index')->with('success', 'Point saved successfully');
    }

    public function show(Point $point)
    {
        $point->load(['judge','contestant']);
        return view('points.show', compact('point'));
    }

    public function edit(Point $point)
    {
//        $judges = Judge::orderBy('name')->where('is_active', true)->get();
        $judge_role_id = Role::where('name', 'judge')->first()->id;
        $judges = User::where('is_deleted', false)->where('role_id', $judge_role_id)->get();
        $candidates = Employee::orderBy('name')->where('is_active', true)->where('is_approve', true)->get();
        return view('points.edit', compact('point','judges','candidates'));
    }

    public function update(StorePointRequest $request, $id)
    {
        $data = $request->validated();
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

        return redirect()->route('points.index')->with('success', 'Point updated');
    }

    public function destroy(Point $point)
    {
        $point->delete();
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
        $contestants = Employee::where('is_active', true)->where('is_approve', true)->get()->map(function ($contestant) use ($ratedContestants) {
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
        $user_id = Auth::user()->id;
        $user_role = Auth::user()->role_id;
        $judge_role_id = Role::where('name', 'judge')->first()->id;

        if ($user_role == $judge_role_id && $this->isGradingAvailable()) {
            $awaiting_candidates = Employee::where('is_active', true)
                ->where('is_approve', true)
                ->whereNotIn('id', function($query) use ($user_id) {
                    $query->select('candidate_id')
                        ->from('points')
                        ->where('judge_id', $user_id);
                })
                ->get();
        } else {
            $awaiting_candidates = [];
        }
        return view('points.awaiting_candidates', compact('awaiting_candidates'));
    }

    public function deleteBySelection(Request $request)
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('points_delete')) {
            $ids = array_filter($request->ids);
            if ($ids) {
                Point::whereIn('id', $ids)->delete();
            }
            return 'Grading deleted successfully!';
        } else {
            return 'You do not have permission to delete this!';
        }
    }

    private function isGradingAvailable()
    {
        $setting = GeneralSetting::first();
        if ($setting->available_grading) {
            return true;
        }
    }

}
