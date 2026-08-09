<?php

// app/Http/Controllers/AmbassadorPointController.php
namespace App\Http\Controllers;

use App\Ambassador;
use App\AmbassadorPoint;
use App\Employee;
use App\GeneralSetting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class AmbassadorPointController extends Controller
{
    private function ambassadorRoleId()
    {
        $role = Role::whereRaw('LOWER(name) = ?', ['ambassador'])->first();

        return $role ? (int) $role->id : 0;
    }

    public function index()
    {
        $ambassadorRoleId = $this->ambassadorRoleId();
        $isAmbassador = $ambassadorRoleId && (int) Auth::user()->role_id === $ambassadorRoleId;
        $grading_disabled = !$this->isGradingAvailable();

        // Always list grades for current contestants (do not hide when grading is toggled off).
        $query = AmbassadorPoint::with(['ambassador:id,name', 'contestant:id,name,image'])
            ->whereHas('contestant', function ($q) {
                $q->where('is_active', true)->where('is_approve', true);
            });

        if ($isAmbassador) {
            $query->where('ambassador_id', Auth::id());
        }

        $points = $query->latest()->get();

        return view('ambassador_points.index', compact('points', 'grading_disabled'));
    }

    public function create(Request $request, $candidate_id = null)
    {
        $candidate_id = $candidate_id ?? $request->candidate_id;
        $candidate_name = null;
        if ($candidate_id) {
            $candidate = Employee::where('id', $candidate_id)->where('is_active', true)->where('is_approve', true)->first(['id', 'name']);
            $candidate_name = $candidate ? $candidate->name : null;
        }
        $ambassador_role_id = $this->ambassadorRoleId();
        $isAmbassador = $ambassador_role_id && (int) Auth::user()->role_id === $ambassador_role_id;

        // Mobile grading path: card → create/{id} — skip loading full lists.
        if ($isAmbassador && $candidate_id) {
            $ambassadors = collect();
            $candidates = collect();
        } else {
            $ambassadors = User::where('is_deleted', false)->where('role_id', $ambassador_role_id)->get(['id', 'name']);
            $candidates = Employee::orderBy('name')->where('is_active', true)->where('is_approve', true)->get(['id', 'name']);
        }
        $grading_disabled = !$this->isGradingAvailable();

        return view('ambassador_points.create', compact(
            'candidates',
            'ambassadors',
            'candidate_id',
            'candidate_name',
            'grading_disabled',
            'ambassador_role_id'
        ));
    }

    public function store(Request $request)
    {
        if (!$this->isGradingAvailable()) {
            return back()->with('not_permitted', trans('file.Grading is not enabled yet'));
        }

        $request->validate([
            'ambassador_id' => 'required|exists:users,id',
            'candidate_id'  => 'required|exists:employees,id',
            'points'        => 'required|numeric|min:0.01|max:5',
        ], [
            'points.required' => 'Please enter points for this candidate.',
            'points.numeric'  => 'Points must be a number from 0.01 to 5 (decimals allowed).',
            'points.min'      => 'Points must be at least 0.01.',
            'points.max'      => 'Points cannot be more than 5. Please enter a number from 0.01 to 5.',
        ]);

        $exists = AmbassadorPoint::where('ambassador_id', $request->ambassador_id)
            ->where('candidate_id', $request->candidate_id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['candidate_id' => 'You have already given points to this candidate.']);
        }

        AmbassadorPoint::create([
            'ambassador_id' => $request->ambassador_id,
            'candidate_id'  => $request->candidate_id,
            'points'        => round((float) $request->points, 2),
        ]);
        Cache::forget('grader_dash_stats_ambassador_' . Auth::id());

        return redirect()->route('ambassador_points.awaiting_candidates')->with('success', 'Points added successfully.');
    }


    public function edit($id)
    {
        $point = AmbassadorPoint::where('id', $id)->firstOrFail();
//        $judges = Ambassador::where('is_active', true)->orderBy('name')->get();
        $candidates = Employee::orderBy('name')->where('is_active', true)->where('is_approve', true)->get(['id', 'name']);
        $ambassador_role_id = $this->ambassadorRoleId();
        return view('ambassador_points.edit', compact('point','candidates', 'ambassador_role_id'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'points' => 'required|numeric|min:0.01|max:5',
        ], [
            'points.required' => 'Please enter points for this candidate.',
            'points.numeric'  => 'Points must be a number from 0.01 to 5 (decimals allowed).',
            'points.min'      => 'Points must be at least 0.01.',
            'points.max'      => 'Points cannot be more than 5. Please enter a number from 0.01 to 5.',
        ]);
        $ambassador_point = AmbassadorPoint::where('id', $id)->firstOrFail();
        if($ambassador_point) {
            $ambassador_point->update([
                'points' => round((float) $request->points, 2),
            ]);
        }
        Cache::forget('grader_dash_stats_ambassador_' . Auth::id());

        return redirect()->route('ambassador_points.awaiting_candidates')->with('success', 'Point updated');
    }
    public function destroy($id)
    {
        $point = AmbassadorPoint::findOrFail($id);
        $point->delete();

        return redirect()->route('ambassador_points.index')->with('success', 'Point deleted');
    }

    public function awaitingCandidates()
    {
        $user_id = (int) Auth::id();
        $user_role = (int) Auth::user()->role_id;
        $ambassador_role_id = $this->ambassadorRoleId();
        $isAmbassador = $ambassador_role_id && $user_role === $ambassador_role_id;
        $adminView = !$isAmbassador;
        $grading_disabled = !$this->isGradingAvailable();

        // Ambassadors: only contestants they have not graded yet (shrinks as they grade).
        // Admins/staff: all approved contestants so they can open any card to grade.
        $query = Employee::where('is_active', true)
            ->where('is_approve', true)
            ->orderBy('name')
            ->select(['id', 'name', 'image']);

        if ($isAmbassador) {
            $gradedIds = AmbassadorPoint::where('ambassador_id', $user_id)
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

        return view('ambassador_points.awaiting_candidates', compact(
            'awaiting_candidates',
            'adminView',
            'grading_disabled'
        ));
    }


    public function deleteBySelection(Request $request)
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('ambassador_point_delete')) {
            $ids = array_filter($request->ids);
            if ($ids) {
                AmbassadorPoint::whereIn('id', $ids)->delete();
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
