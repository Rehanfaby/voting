<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Ambassador;
use App\Helpers\ImageOptimizer;
use App\Services\JudgeAmbassadorAccountService;
use Auth;
use Illuminate\Validation\Rule;

class AmbassadorController extends Controller
{

    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('employees-index')){
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if(empty($all_permission))
                $all_permission[] = 'dummy text';
            $lims_employee_all = Ambassador::where('is_active', true)->get();
            return view('ambassador.index', compact('lims_employee_all', 'all_permission'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function create()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('employees-add')){
            $lims_role_list = Role::where('is_active', true)->get();

            return view('ambassador.create', compact('lims_role_list'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function store(Request $request)
    {
        $data = $request->except('image', 'password', 'user');

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('ambassadors')->where(function ($query) {
                    return $query->where('is_active', true);
                }),
            ],
            'phone_number' => 'required',
            'image' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
        ]);

        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $request['email']);
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/employee', $imageName);
            ImageOptimizer::afterUpload(public_path('images/employee/' . $imageName), 'portrait');
            $data['image'] = $imageName;
        }

        $data['is_active'] = true;
        $ambassador = Ambassador::create($data);

        $plainPassword = $request->filled('password') ? $request->password : null;
        $result = app(JudgeAmbassadorAccountService::class)
            ->ensureForProfile($ambassador, 'Ambassador', $plainPassword);

        $message = 'Ambassador created successfully with login account (Ambassador role).';
        if (!empty($result['password'])) {
            $message .= ' Temporary password: ' . $result['password'];
        }

        return redirect('ambassador')->with('message', $message);
    }

    public function update(Request $request, $id)
    {
        $lims_employee_data = Ambassador::find($request->ambassador_id);
        $this->validate($request, [
            'email' => [
                'email',
                'max:255',
                Rule::unique('ambassadors')->ignore($lims_employee_data->id)->where(function ($query) {
                    return $query->where('is_active', true);
                }),
            ],
            'image' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
        ]);

        $data = $request->except('image', 'ambassador_id', 'password', 'user');
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $request['email']);
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/employee', $imageName);
            ImageOptimizer::afterUpload(public_path('images/employee/' . $imageName), 'portrait');
            $data['image'] = $imageName;
        }

        $lims_employee_data->update($data);
        app(JudgeAmbassadorAccountService::class)
            ->ensureForProfile($lims_employee_data->fresh(), 'Ambassador');

        return redirect('ambassador')->with('message', 'Ambassador updated successfully');
    }

    public function deleteBySelection(Request $request)
    {
        $employee_id = $request['employeeIdArray'] ?? $request['ids'] ?? [];
        foreach ($employee_id as $id) {
            if($id == null) {
                continue;
            }
            $lims_employee_data = Ambassador::find($id);
            if(!$lims_employee_data) {
                continue;
            }
            $lims_employee_data->is_active = false;
            $lims_employee_data->save();
        }
        return 'Ambassador deleted successfully!';
    }
    public function destroy($id)
    {
        $lims_employee_data = Ambassador::find($id);
        $lims_employee_data->is_active = false;
        $lims_employee_data->save();
        return redirect('ambassador')->with('not_permitted', 'Ambassador deleted successfully');
    }

}
