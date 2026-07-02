<?php

namespace App\Http\Controllers;

use App\AboutMember;
use App\Helpers\ImageOptimizer;
use Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AboutUsController extends Controller
{
    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        if (!$role->hasPermissionTo('employees-index')) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        $permissions = Role::findByName($role->name)->permissions;
        foreach ($permissions as $permission) {
            $all_permission[] = $permission->name;
        }
        if (empty($all_permission)) {
            $all_permission[] = 'dummy text';
        }

        $members = AboutMember::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('about_us.index', compact('members', 'all_permission'));
    }

    public function store(Request $request)
    {
        $role = Role::find(Auth::user()->role_id);
        if (!$role->hasPermissionTo('employees-add')) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'title' => 'nullable|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:100000',
        ]);

        $data = $request->only('name', 'title', 'country', 'sort_order');
        $data['is_active'] = true;
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = 'about_' . time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $request->name) . '.' . $ext;
            $image->move('public/images/employee', $imageName);
            ImageOptimizer::afterUpload(public_path('images/employee/' . $imageName), 'portrait');
            $data['image'] = $imageName;
        }

        AboutMember::create($data);

        return redirect('about-us')->with('message', trans('file.About member added successfully'));
    }

    public function update(Request $request)
    {
        $role = Role::find(Auth::user()->role_id);
        if (!$role->hasPermissionTo('employees-edit')) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        $member = AboutMember::findOrFail($request->member_id);

        $this->validate($request, [
            'name' => 'required|max:255',
            'title' => 'nullable|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:100000',
        ]);

        $data = $request->only('name', 'title', 'country', 'sort_order');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = 'about_' . time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $request->name) . '.' . $ext;
            $image->move('public/images/employee', $imageName);
            ImageOptimizer::afterUpload(public_path('images/employee/' . $imageName), 'portrait');
            $data['image'] = $imageName;
        }

        $member->update($data);

        return redirect('about-us')->with('message', trans('file.About member updated successfully'));
    }

    public function destroy($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if (!$role->hasPermissionTo('employees-delete')) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        $member = AboutMember::findOrFail($id);
        $member->is_active = false;
        $member->save();

        return redirect('about-us')->with('message', trans('file.About member removed successfully'));
    }
}
