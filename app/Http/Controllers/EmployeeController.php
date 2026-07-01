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
                $lims_employee_all = Employee::where('is_active', true)->where('is_approve', true)->get();
            } elseif ($role->id == 2) {
                $lims_employee_all = Employee::where('is_active', true)->where('is_approve', true)->where('name', Auth::user()->name)->get();
            } else {
                return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
            }
            $pending = 0;
            $lims_department_list = Department::where('is_active', true)->get();
            return view('employee.index', compact('lims_employee_all', 'lims_department_list', 'all_permission', 'pending'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function pending()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('employees-index')){
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if(empty($all_permission))
                $all_permission[] = 'dummy text';

            if ($role->id == 1){
                $lims_employee_all = Employee::where('is_active', true)->where('is_approve', false)->get();
            } else {
                return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
            }
            $pending = 1;
            $lims_department_list = Department::where('is_active', true)->get();
            return view('employee.index', compact('lims_employee_all', 'lims_department_list', 'all_permission', 'pending'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function approveStore($id)
    {
        Employee::where('id', $id)->update(['is_approve' => true]);
        return redirect()->back()->with('message', 'Contestant has been approved successfully');
    }

    public function rejectStore($id)
    {
        $employee =  Employee::where('id', $id)->first();
        $employee->is_approve = false;
        $employee->is_active = false;
        $employee->save();
        $msg = 'Dear ' . $employee->name . ', your account has been rejected.';

        try{
            $this->wpMessage($employee->phone_number, $msg);
        }
        catch(\Exception $e){
        }
        return redirect()->back()->with('not_permitted', 'Contestant has been rejected successfully');
    }

    public function show($id)
    {
        // No dedicated detail page; the resource "show" route would otherwise 500.
        return redirect()->route('musician.index');
    }

    /**
     * Normalize a Cameroon phone number: strip spaces/formatting and ensure the
     * +237 country code is present.
     */
    public static function normalizePhone($phone)
    {
        if ($phone === null) {
            return $phone;
        }
        // Remove spaces and common separators.
        $phone = preg_replace('/[\s\-\.\(\)]/', '', (string) $phone);
        if ($phone === '') {
            return $phone;
        }
        // Already has a "+" country code – leave as is.
        if (strpos($phone, '+') === 0) {
            return $phone;
        }
        // International prefix written as 00 -> convert to +.
        if (strpos($phone, '00') === 0) {
            return '+' . substr($phone, 2);
        }
        // Starts with the Cameroon code without the plus.
        if (strpos($phone, '237') === 0) {
            return '+' . $phone;
        }
        // Local number – prepend the Cameroon country code.
        return '+237' . $phone;
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
        if (!empty($data['phone_number'])) {
            $data['phone_number'] = self::normalizePhone($data['phone_number']);
        }
        $data['country'] = 'Cameroon';
        $message = 'Contestant created successfully';

        // Validate the contestant (employees) uniqueness first so a failed
        // submission never leaves behind an orphan user account.
        $this->validate($request, [
            'email' => [
                'max:255',
                Rule::unique('employees')->where(function ($query) {
                    return $query->where('is_active', true);
                }),
            ],
            'image' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
        ]);

        // A contestant and a voter (or judge/jury/user) can be the SAME person,
        // so we do NOT block on the users table. If an account already exists for
        // this email we reuse it; otherwise we create a dedicated contestant
        // account. This lets a single person exist as multiple roles at once.
        $existingUser = null;
        if (!empty($request['email'])) {
            $existingUser = User::whereRaw('LOWER(email) = ?', [strtolower($request['email'])])
                ->where('is_deleted', false)
                ->first();
        }

        if ($existingUser) {
            $user = $existingUser;
        } else {
            $password = rand(1, 999999);
            $user = User::create([
                'name'       => $data['employee_name'] ?? '',
                'email'      => $request['email'],
                'phone'      => $data['phone_number'] ?? null,
                'password'   => bcrypt($password),
                'role_id'    => 2, // contestant role (voters stay role 3)
                'is_active'  => true,
                'is_deleted' => false,
            ]);

            $msg  = '*Congrats:* Your account has been created \n\n';
            $msg .= '*User name:* '. $user->name . '\n\n';
            $msg .= '*Phone number:* '. $user->phone . '\n\n';
            $msg .= '*Password:* '. $password . '\n\n';
            try {
                $this->wpMessage($user->phone, $msg);
            } catch (\Exception $e) {
            }
        }

        $data['user_id'] = $user->id;
        $data['name'] = $data['employee_name'] ?? ($data['name'] ?? '');

        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $request['email']);
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/employee', $imageName);
            \App\Helpers\ImageOptimizer::process(public_path('images/employee/' . $imageName));
            $data['image'] = $imageName;
        }

        $data['is_active'] = true;
        Employee::create($data);

        return redirect('musician')->with('message', $message);
    }

    public function update(Request $request, $id)
    {
        $lims_employee_data = Employee::find($request['employee_id']);
        // Contestants may share an email with a voter/judge account, so we only
        // validate uniqueness within the contestant (employees) table below.
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
        if (!empty($data['phone_number'])) {
            $data['phone_number'] = self::normalizePhone($data['phone_number']);
        }
        $data['country'] = 'Cameroon';
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $request['email']);
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/employee', $imageName);
            \App\Helpers\ImageOptimizer::process(public_path('images/employee/' . $imageName));
            $data['image'] = $imageName;
        }

        $lims_employee_data->update($data);
        return redirect('musician')->with('message', 'Contestant updated successfully');
    }

    public function deleteBySelection(Request $request)
    {
        $employee_id = $request['employeeIdArray'] ?? $request['ids'] ?? [];
        foreach ($employee_id as $id) {
            if($id == null) {
                continue;
            }
            $lims_employee_data = Employee::find($id);
            if(!$lims_employee_data) {
                continue;
            }
            // Only deactivate the contestant profile. The linked user account is
            // left intact because the same person may also be a voter/judge.
            $lims_employee_data->is_active = false;
            $lims_employee_data->save();
        }
        return 'Contestant deleted successfully!';
    }

    public function approveBySelection(Request $request)
    {
        $employee_id = $request['employeeIdArray'] ?? $request['ids'] ?? [];
        $count = 0;
        foreach ($employee_id as $id) {
            if($id == null) {
                continue;
            }
            if(Employee::where('id', $id)->update(['is_approve' => true])) {
                $count++;
            }
        }
        return $count . ' contestant(s) approved successfully!';
    }
    public function destroy($id)
    {
        $lims_employee_data = Employee::find($id);
        if($lims_employee_data) {
            // Only deactivate the contestant profile; keep the linked user account
            // (the same person may also be a voter/judge/jury).
            $lims_employee_data->is_active = false;
            $lims_employee_data->save();
        }
        return redirect()->back()->with('not_permitted', 'Contestant deleted successfully');
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
                if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    \App\Helpers\ImageOptimizer::optimize(public_path('employee/data/' . $imageName), 1280, 80);
                }

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
