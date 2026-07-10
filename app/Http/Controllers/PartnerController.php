<?php

namespace App\Http\Controllers;

use App\Partner;
use App\Helpers\ImageOptimizer;
use Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class PartnerController extends Controller
{
    private function authorizePartner($permission = 'employees-index')
    {
        $role = Role::find(Auth::user()->role_id);
        if (!$role || !$role->hasPermissionTo($permission)) {
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
        }

        return null;
    }

    private function permissionList()
    {
        $role = Role::find(Auth::user()->role_id);
        $permissions = Role::findByName($role->name)->permissions;
        $all_permission = [];
        foreach ($permissions as $permission) {
            $all_permission[] = $permission->name;
        }
        if (empty($all_permission)) {
            $all_permission[] = 'dummy text';
        }

        return $all_permission;
    }

    public function index()
    {
        if ($denied = $this->authorizePartner()) {
            return $denied;
        }

        $partners = Partner::orderBy('sort_order')->orderBy('id')->get();

        return view('partner.index', [
            'partners' => $partners,
            'all_permission' => $this->permissionList(),
        ]);
    }

    public function store(Request $request)
    {
        if ($denied = $this->authorizePartner('employees-add')) {
            return $denied;
        }

        $this->validate($request, [
            'name' => 'nullable|max:255',
            'link' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp,svg|max:100000',
        ]);

        $data = $request->only('name', 'link');
        $data['sort_order'] = (int) $request->input('sort_order', 0);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : true;
        $data['image'] = $this->storeLogo($request->file('image'), $request->name ?: 'logo');

        Partner::create($data);

        return redirect('partner')->with('message', trans('file.Logo added successfully'));
    }

    public function update(Request $request)
    {
        if ($denied = $this->authorizePartner('employees-edit')) {
            return $denied;
        }

        $partner = Partner::findOrFail($request->partner_id);

        $this->validate($request, [
            'name' => 'nullable|max:255',
            'link' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp,svg|max:100000',
        ]);

        $data = $request->only('name', 'link');
        $data['sort_order'] = (int) $request->input('sort_order', 0);
        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : $partner->is_active;

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeLogo($request->file('image'), $request->name ?: 'logo');
        }

        $partner->update($data);

        return redirect('partner')->with('message', trans('file.Logo updated successfully'));
    }

    public function destroy($id)
    {
        if ($denied = $this->authorizePartner('employees-delete')) {
            return $denied;
        }

        $partner = Partner::find($id);
        if ($partner) {
            $partner->delete();
        }

        return redirect('partner')->with('message', trans('file.Logo removed successfully'));
    }

    public function deleteBySelection(Request $request)
    {
        $role = Role::find(Auth::user()->role_id);
        if (!$role || !$role->hasPermissionTo('employees-delete')) {
            return 'Sorry! You are not allowed to access this module';
        }

        $ids = $request['partnerIdArray'] ?? $request['ids'] ?? [];
        foreach ($ids as $id) {
            if ($id === null) {
                continue;
            }
            $partner = Partner::find($id);
            if ($partner) {
                $partner->delete();
            }
        }

        return 'Logo deleted successfully!';
    }

    private function storeLogo($image, $slug)
    {
        $dir = public_path('images/partners');
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
        $imageName = 'partner_' . time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $slug) . '.' . $ext;
        $image->move($dir, $imageName);

        if (strtolower($ext) !== 'svg') {
            ImageOptimizer::afterUpload($dir . '/' . $imageName, 'banner');
        }

        return $imageName;
    }
}
