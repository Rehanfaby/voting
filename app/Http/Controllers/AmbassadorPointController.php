<?php

// app/Http/Controllers/AmbassadorPointController.php
namespace App\Http\Controllers;

use App\Ambassador;
use App\AmbassadorPoint;
use App\Employee;
use Illuminate\Http\Request;

class AmbassadorPointController extends Controller
{
    public function index()
    {

        $points = AmbassadorPoint::with(['ambassador', 'contestant'])->get();
        return view('ambassador_points.index', compact('points'));
    }

    public function create()
    {
        $ambassadors = Ambassador::where('is_active', true)->orderBy('name')->get();
        $candidates = Employee::orderBy('name')->where('is_active', true)->where('is_approve', true)->get();
        return view('ambassador_points.create', compact('candidates', 'ambassadors'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'candidate_id' => 'required|exists:employees,id',
            'points'       => 'required|integer|min:1|max:5',
        ]);

        $exists = AmbassadorPoint::where('ambassador_id', $request->ambassador_id)
            ->where('candidate_id', $request->candidate_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['candidate_id' => 'You have already given points to this candidate.']);
        }

        AmbassadorPoint::create([
            'ambassador_id' => $request->ambassador_id,
            'candidate_id'  => $request->candidate_id,
            'points'        => $request->points,
        ]);

        return redirect()->route('ambassador_points.index')->with('success', 'Points added successfully.');
    }


    public function edit($id)
    {
        $point = AmbassadorPoint::where('id', $id)->firstOrFail();
        $judges = Ambassador::where('is_active', true)->orderBy('name')->get();
        $candidates = Employee::orderBy('name')->where('is_active', true)->where('is_approve', true)->get();
        return view('ambassador_points.edit', compact('point','judges','candidates'));
    }

    public function update($id, Request $request)
    {
        $ambassador_point = AmbassadorPoint::where('id', $id)->firstOrFail();
        if($ambassador_point) {
            $ambassador_point->update([
                'points' => $request->points
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
}
