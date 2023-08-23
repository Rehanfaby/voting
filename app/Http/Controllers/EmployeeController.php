<?php

namespace App\Http\Controllers;

use App\Gallery;
use App\vote;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Warehouse;
use App\Biller;
use App\Employee;
use App\User;
use App\Department;
use Auth;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
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

            if ($role->id == 1){
                $lims_employee_all = Employee::where('is_active', true)->get();
            } elseif ($role->id == 2) {
                $lims_employee_all = Employee::where('is_active', true)->where('name', Auth::user()->name)->get();
            } else {
                return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
            }
            $lims_department_list = Department::where('is_active', true)->get();
            return view('employee.index', compact('lims_employee_all', 'lims_department_list', 'all_permission'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function create()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('employees-add')){
            $lims_role_list = Role::where('is_active', true)->get();
            $lims_department_list = Department::where('is_active', true)->get();

            return view('employee.create', compact('lims_role_list', 'lims_department_list'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function store(Request $request)
    {
        $data = $request->except('image');
        $message = 'Contestant created successfully';

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

        $password = rand(1, 999999);
        $data['is_active'] = true;
        $data['is_deleted'] = false;
        $data['password'] = bcrypt($password);
        $data['phone'] = $data['phone_number'];
        $data['name'] = $data['employee_name'];
        $data['role_id'] = 2;
        User::create($data);
        $user = User::latest()->first();
        $data['user_id'] = $user->id;
        $message = 'Contestant created successfully';

        $msg = '*Congrats:* Your account has been created \n\n';
        $msg .= '*User name:* '. $user->name . '\n\n';
        $msg .= '*Phone number:* '. $user->phone . '\n\n';
        $msg .= '*Password:* '. $password . '\n\n';

        try{
            $this->wpMessage($user->phone, $msg);
        }
        catch(\Exception $e){

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

        $data['is_active'] = true;
        Employee::create($data);

        return redirect('musician')->with('message', $message);
    }

    public function update(Request $request, $id)
    {
        $lims_employee_data = Employee::find($request['employee_id']);
        if($lims_employee_data->user_id){
            $this->validate($request, [
                'name' => [
                    'max:255',
                    Rule::unique('users')->ignore($lims_employee_data->user_id)->where(function ($query) {
                        return $query->where('is_deleted', false);
                    }),
                ],
                'email' => [
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($lims_employee_data->user_id)->where(function ($query) {
                        return $query->where('is_deleted', false);
                    }),
                ],
            ]);
        }
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

        $data = $request->except('image');
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $request['email']);
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/employee', $imageName);
            $data['image'] = $imageName;
        }

        $lims_employee_data->update($data);
        return redirect('musician')->with('message', 'Contestant updated successfully');
    }

    public function deleteBySelection(Request $request)
    {
        $employee_id = $request['employeeIdArray'];
        foreach ($employee_id as $id) {
            if($id == null) {
                continue;
            }
            $lims_employee_data = Employee::find($id);
            $lims_employee_data->is_active = false;
            $lims_employee_data->save();
        }
        return 'Musician deleted successfully!';
    }
    public function destroy($id)
    {
        $lims_employee_data = Employee::find($id);
        $lims_employee_data->is_active = false;
        $lims_employee_data->save();
        return redirect('musician')->with('not_permitted', 'Contestant deleted successfully');
    }

    public function gallery($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('employees-index')) {
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
        } else {
            $all_permission = [];
        }
        $lims_employee_data = Employee::find($id);
        $lims_employee_gallery = Gallery::where('employee_id', $id)->orderBy('id', 'desc')->get();
        return view('employee.gallery', compact('lims_employee_data', 'lims_employee_gallery', 'all_permission'));
    }

    public function upload($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('employees-index')) {
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
        } else {
            $all_permission = [];
        }

        $lims_employee_data = Employee::find($id);
        return view('employee.upload', compact('lims_employee_data', 'all_permission'));
    }

    public function uploadStore(Request $request)
    {
        $data['employee_id'] = $request['employee_id'];

        $file = $request->file;
        $data['type'] = $request['type'];
        if($data['type'] == 'link' || $data['type'] == 'short'){
            $data['file'] = "https://www.youtube.com/embed/" . $request['file_path'];
        } else {
            if ($file) {
                $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $request['file']);
                $imageName = $imageName . '.' . $ext;
                $file->move('public/employee/data', $imageName);

                $data['file'] = $imageName;
            }
        }

        Gallery::create($data);
        return back()->with('message', 'File uploaded successfully');
    }

    public function galleryDestroy($id)
    {
        $lims_employee_gallery = Gallery::find($id);
        $lims_employee_gallery->delete();
        return back()->with('not_permitted', 'File deleted successfully');
    }

    public function galleryEdit($id)
    {
        $lims_employee_gallery = Gallery::find($id);
        return view('employee.gallery_edit', compact('lims_employee_gallery'));
    }

    public function votes($id) {
        $lims_employee_data = Employee::find($id);
//        $start_date = date('Y-m-d', strtotime('last monday'));
//        $end_date = date('Y-m-d');

        $lims_employee_votes = Vote::where('musician_id', $id)
                                ->orderBy('id', 'desc')
//                                ->whereDate('created_at', '>=', $start_date)
//                                ->whereDate('created_at', '<=', $end_date)
                                ->where('status', true)
                                ->get();

        return view('employee.votes', compact('lims_employee_data', 'lims_employee_votes'));
    }
}
