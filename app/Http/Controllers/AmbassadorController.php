<?php

namespace App\Http\Controllers;

use App\Gallery;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Warehouse;
use App\Biller;
use App\Ambassador;
use App\User;
use App\Department;
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
            $lims_department_list = Department::where('is_active', true)->get();
            return view('ambassador.index', compact('lims_employee_all', 'lims_department_list', 'all_permission'));
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
        $data = $request->except('image');

        $message = 'Ambassador created successfully';
        if(isset($data['user'])){
            $this->validate($request, [
                'name' => [
                    'max:255',
                        Rule::unique('users')->where(function ($query) {
                        return $query->where('is_deleted', false);
                    }),
                ],
                'email' => [
                    'email',
                    'max:255',
                        Rule::unique('users')->where(function ($query) {
                        return $query->where('is_deleted', false);
                    }),
                ],
            ]);

            $data['is_active'] = true;
            $data['is_deleted'] = false;
            $data['password'] = bcrypt($data['password']);
            $data['phone'] = $data['phone_number'];
            User::create($data);
            $user = User::latest()->first();
            $data['user_id'] = $user->id;
            $message = 'Ambassador created successfully and added to user list';
        }
        //validation in employee table
        $this->validate($request, [
            'email' => [
                'max:255',
                    Rule::unique('employees')->where(function ($query) {
                    return $query->where('is_active', true);
                }),
            ],
            'image' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
        ]);

        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $request['email']);
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/employee', $imageName);
            $data['image'] = $imageName;
        }

        $data['name'] = $data['name'];
        $data['is_active'] = true;
        Ambassador::create($data);

        return redirect('ambassador')->with('message', $message);
    }

    public function update(Request $request, $id)
    {
        $lims_employee_data = Ambassador::find($request->ambassador_id);
        //validation in employee table
        $this->validate($request, [
            'email' => [
                'email',
                'max:255',
                    Rule::unique('employees')->ignore($lims_employee_data->id)->where(function ($query) {
                    return $query->where('is_active', true);
                }),
            ],
            'image' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
        ]);

        $data = $request->except('image', 'ambassador_id');
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $request['email']);
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/employee', $imageName);
            $data['image'] = $imageName;
        }

        $lims_employee_data->update($data);
        return redirect('ambassador')->with('message', 'Ambassador updated successfully');
    }

    public function deleteBySelection(Request $request)
    {
        $employee_id = $request['employeeIdArray'];
        foreach ($employee_id as $id) {
            $lims_employee_data = Ambassador::find($id);
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
