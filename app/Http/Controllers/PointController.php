<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Http\Requests\StorePointRequest;
use App\Judge;
use App\Point;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function index()
    {
        $points = Point::with(['judge','contestant'])->latest()->paginate(20);
        return view('points.index', compact('points'));
    }

    public function create()
    {
        $judges = Judge::orderBy('name')->where('is_active', true)->get();
        $candidates = Employee::orderBy('name')->where('is_active', true)->where('is_approve', true)->get();
        return view('points.create', compact('judges','candidates'));
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
        $judges = Judge::orderBy('name')->where('is_active', true)->get();
        $candidates = Employee::orderBy('name')->where('is_active', true)->where('is_approve', true)->get();
        return view('points.edit', compact('point','judges','candidates'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $point = Point::where('id', $id)->update([
            'depth' => $data['depth'],
            'diction' => $data['diction'],
            'accuracy' => $data['accuracy'],
            'interpretation' => $data['interpretation'],
            'technique' => $data['technique'],
            'stage_presence' => $data['stage_presence'],
            'song_choice' => $data['song_choice'],
            'overall_presentation' => $data['overall_presentation'],
            'adaptability' => $data['adaptability'],
            'audience_interaction' => $data['audience_interaction'],
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

}
