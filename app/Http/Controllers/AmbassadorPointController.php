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
        if (!$this->isGradingAvailable()) {
            return view('ambassador_points.index', [
                'points' => collect(),
                'grading_disabled' => true,
            ]);
        }

        $ambassadorRoleId = $this->ambassadorRoleId();
        if (!$ambassadorRoleId) {
            return view('ambassador_points.index', [
                'points' => collect(),
                'grading_disabled' => true,
            ]);
        }

        if ((int) Auth::user()->role_id === $ambassadorRoleId) {
            $points = AmbassadorPoint::with(['ambassador', 'contestant'])
                ->whereHas('contestant')
                ->whereHas('ambassador')
                ->where('ambassador_id', Auth::user()->id)
                ->latest()
                ->get();
        } else {
            $points = AmbassadorPoint::with(['ambassador', 'contestant'])
                ->whereHas('contestant')
                ->whereHas('ambassador')
                ->latest()
                ->get();
        }

        return view('ambassador_points.index', compact('points'));
    }

    public function create(Request $request, $candidate_id = null)
    {
        $candidate_id = $candidate_id ?? $request->candidate_id;
        $candidate_name = null;
        if ($candidate_id) {
            $candidate_name = Employee::where('id', $candidate_id)->where('is_active', true)->where('is_approve', true)->first()->name;
        }
        $judge_role_id = $this->ambassadorRoleId();
        $ambassadors = User::where('is_deleted', false)->where('role_id', $judge_role_id)->get();
//        $ambassadors = Ambassador::where('is_active', true)->orderBy('name')->get();
        if ($this->isGradingAvailable()) {
            $candidates = Employee::orderBy('name')->where('is_active', true)->where('is_approve', true)->get();
        } else {
            $candidates = [];
        }
        return view('ambassador_points.create', compact('candidates', 'ambassadors', 'candidate_id', 'candidate_name'));
    }

    public function store(Request $request)
    {
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

        return redirect()->route('ambassador_points.index')->with('success', 'Points added successfully.');
    }


    public function edit($id)
    {
        $point = AmbassadorPoint::where('id', $id)->firstOrFail();
//        $judges = Ambassador::where('is_active', true)->orderBy('name')->get();
        $candidates = Employee::orderBy('name')->where('is_active', true)->where('is_approve', true)->get();
        return view('ambassador_points.edit', compact('point','candidates'));
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

        return redirect()->route('ambassador_points.index')->with('success', 'Point updated');
    }
    public function destroy($id)
    {
        $point = AmbassadorPoint::findOrFail($id);
        $point->delete();

        return redirect()->route('ambassador_points.index')->with('success', 'Point deleted');
    }

    public function awaitingCandidates()
    {
        $user_id = Auth::user()->id;
        $user_role = (int) Auth::user()->role_id;
        $ambassador_role_id = $this->ambassadorRoleId();

        if ($ambassador_role_id && $user_role === $ambassador_role_id && $this->isGradingAvailable()) {
            $awaiting_candidates = Employee::where('is_active', true)
                ->where('is_approve', true)
                ->whereNotIn('id', function ($query) use ($user_id) {
                    $query->select('candidate_id')
                        ->from('ambassador_points')
                        ->where('ambassador_id', $user_id);
                })
                ->orderBy('name')
                ->get();
        } else {
            $awaiting_candidates = collect();
        }

        return view('ambassador_points.awaiting_candidates', compact('awaiting_candidates'));
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
        $setting = GeneralSetting::first();
        return $setting && (bool) $setting->available_grading;
    }
}
