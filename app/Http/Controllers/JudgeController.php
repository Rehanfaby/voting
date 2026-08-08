<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Judge;
use App\Helpers\ImageOptimizer;
use App\Services\JudgeAmbassadorAccountService;
use Auth;
use Illuminate\Validation\Rule;

class JudgeController extends Controller
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
            $lims_employee_all = Judge::realJudgesQuery()
                ->orderByDesc('is_active')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();
            return view('judge.index', compact('lims_employee_all', 'all_permission'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function create()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('employees-add')){
            $lims_role_list = Role::where('is_active', true)->get();

            return view('judge.create', compact('lims_role_list'));
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
                Rule::unique('judges')->where(function ($query) {
                    return $query->where('is_active', true);
                }),
            ],
            'phone_number' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:100000',
        ]);

        $imageName = $this->storeJudgeImage($request);
        if ($imageName) {
            $data['image'] = $imageName;
        }

        $data['is_active'] = true;
        $judge = Judge::create($data);

        $plainPassword = $request->filled('password') ? $request->password : null;
        $result = app(JudgeAmbassadorAccountService::class)
            ->ensureForProfile($judge, 'Judge', $plainPassword);

        $message = 'Judge created successfully with login account (Judge role).';
        if (!empty($result['password'])) {
            $message .= ' Temporary password: ' . $result['password'];
        }

        return redirect('judge')->with('message', $message);
    }

    public function update(Request $request, $id)
    {
        $lims_employee_data = Judge::find($request->judge_id);
        if (!$lims_employee_data) {
            return redirect('judge')->with('not_permitted', 'Judge not found');
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'phone_number' => 'required',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('judges')->ignore($lims_employee_data->id)->where(function ($query) {
                    return $query->where('is_active', true);
                }),
            ],
            'password' => 'nullable|min:4',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:100000',
        ]);

        $data = $request->except('image', 'judge_id', 'password', 'user', 'is_active');
        $data['is_active'] = (string) $request->input('is_active', '0') === '1';

        $imageName = $this->storeJudgeImage($request, $lims_employee_data->image);
        if ($imageName) {
            $data['image'] = $imageName;
        }

        $lims_employee_data->update($data);

        $plainPassword = $request->filled('password') ? $request->password : null;
        $result = app(JudgeAmbassadorAccountService::class)
            ->ensureForProfile($lims_employee_data->fresh(), 'Judge', $plainPassword, (bool) $plainPassword);

        $message = 'Judge updated successfully';
        if (!empty($result['password'])) {
            $message .= '. Login password set to: ' . $result['password'];
        }
        if (!$data['is_active']) {
            $message .= '. Judge is disabled (cannot log in).';
        }

        return redirect('judge')->with('message', $message);
    }

    public function deleteBySelection(Request $request)
    {
        $employee_id = $request['employeeIdArray'];
        foreach ($employee_id as $id) {
            $lims_employee_data = Judge::find($id);
            if ($lims_employee_data) {
                $lims_employee_data->is_active = false;
                $lims_employee_data->save();
                app(JudgeAmbassadorAccountService::class)
                    ->ensureForProfile($lims_employee_data->fresh(), 'Judge');
            }
        }
        return 'Judge deleted successfully!';
    }
    public function destroy($id)
    {
        $lims_employee_data = Judge::find($id);
        if ($lims_employee_data) {
            $lims_employee_data->is_active = false;
            $lims_employee_data->save();
            app(JudgeAmbassadorAccountService::class)
                ->ensureForProfile($lims_employee_data->fresh(), 'Judge');
        }
        return redirect('judge')->with('not_permitted', 'Judge deleted successfully');
    }

    /**
     * Store a new judge photo with a unique filename so browsers do not keep
     * showing a cached thumbnail after an overwrite of the same email-based name.
     */
    protected function storeJudgeImage(Request $request, $previousImage = null)
    {
        $image = $request->file('image');
        if (!$image) {
            return null;
        }

        $ext = strtolower($image->getClientOriginalExtension() ?: $image->extension() ?: 'jpg');
        $slug = preg_replace('/[^a-zA-Z0-9]/', '', (string) $request->input('email', 'judge'));
        if ($slug === '') {
            $slug = 'judge';
        }
        $imageName = $slug . '_' . time() . '.' . $ext;

        $dir = public_path('images/employee');
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        $image->move($dir, $imageName);
        ImageOptimizer::afterUpload($dir . DIRECTORY_SEPARATOR . $imageName, 'portrait');

        if ($previousImage && $previousImage !== $imageName) {
            foreach ([
                $dir . DIRECTORY_SEPARATOR . $previousImage,
                $dir . DIRECTORY_SEPARATOR . 'thumbs' . DIRECTORY_SEPARATOR . $previousImage,
            ] as $oldPath) {
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }

        return $imageName;
    }

    public function awaitingCandidates()
    {
        $user_id = Auth::user()->id;
        $user_role = Auth::user()->role_id;
        $ambassador_role_id = Role::where('name', 'judge')->first()->id;

        if ($user_role == $ambassador_role_id) {
            $awaiting_candidates = Employee::where('is_active', true)
                ->where('is_approve', true)
                ->whereNotIn('id', function($query) use ($user_id) {
                    $query->select('candidate_id')
                        ->from('points')
                        ->where('judge_id', $user_id);
                })
                ->get();
        } else {
            $awaiting_candidates = [];
        }
        return view('points.awaiting_candidates', compact('awaiting_candidates'));
    }

}
