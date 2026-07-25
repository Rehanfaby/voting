<?php

namespace App\Http\Controllers;

use App\Hall;
use App\Services\Halls\HallLayoutService;
use Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class HallController extends Controller
{
    protected function can($perm)
    {
        $role = Role::find(Auth::user()->role_id);
        return $role && $role->hasPermissionTo($perm);
    }

    public function index()
    {
        if (!$this->can('halls-index')) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        $halls = Hall::with(['latestPublishedLayout', 'draftLayout'])
            ->orderBy('name')
            ->get();

        return view('halls.index', compact('halls'));
    }

    public function create()
    {
        if (!$this->can('halls-edit')) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        return view('halls.create');
    }

    public function store(Request $request, HallLayoutService $layouts)
    {
        if (!$this->can('halls-edit')) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        $data = $request->validate([
            'name' => 'required|string|max:190',
            'city' => 'nullable|string|max:120',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $hall = Hall::create($data + ['is_active' => true]);
        $draft = $layouts->createDraft($hall);
        $layouts->ensureDefaultLevel($draft);

        return redirect()->route('halls.layouts.edit', [$hall->id, $draft->id])
            ->with('message', 'Hall created. Design the seating layout.');
    }

    public function edit($id)
    {
        if (!$this->can('halls-edit')) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        $hall = Hall::with('layoutVersions')->findOrFail($id);

        return view('halls.edit', compact('hall'));
    }

    public function update(Request $request, $id)
    {
        if (!$this->can('halls-edit')) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        $hall = Hall::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:190',
            'city' => 'nullable|string|max:120',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
        $hall->fill($data);
        $hall->is_active = $request->boolean('is_active');
        $hall->save();

        return redirect()->route('halls.index')->with('message', 'Hall updated.');
    }
}
