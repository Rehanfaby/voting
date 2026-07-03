<?php

namespace App\Http\Controllers;

use App\AboutMember;
use App\AboutWinner;
use App\Helpers\ImageOptimizer;
use App\Helpers\SiteContent;
use Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AboutUsController extends Controller
{
    private function authorizeAbout($permission = 'employees-index')
    {
        $role = Role::find(Auth::user()->role_id);
        if (!$role->hasPermissionTo($permission)) {
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
        if ($denied = $this->authorizeAbout()) {
            return $denied;
        }

        $members = AboutMember::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('about_us.index', [
            'members' => $members,
            'all_permission' => $this->permissionList(),
        ]);
    }

    public function store(Request $request)
    {
        if ($denied = $this->authorizeAbout('employees-add')) {
            return $denied;
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'title' => 'nullable|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:100000',
        ]);

        $data = $request->only('name', 'title', 'bio', 'country', 'sort_order');
        $data['is_active'] = true;
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeAboutImage($request->file('image'), $request->name);
        }

        AboutMember::create($data);

        return redirect('about-us')->with('message', trans('file.About member added successfully'));
    }

    public function update(Request $request)
    {
        if ($denied = $this->authorizeAbout('employees-edit')) {
            return $denied;
        }

        $member = AboutMember::findOrFail($request->member_id);

        $this->validate($request, [
            'name' => 'required|max:255',
            'title' => 'nullable|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:100000',
        ]);

        $data = $request->only('name', 'title', 'bio', 'country', 'sort_order');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeAboutImage($request->file('image'), $request->name);
        }

        $member->update($data);

        return redirect('about-us')->with('message', trans('file.About member updated successfully'));
    }

    public function destroy($id)
    {
        if ($denied = $this->authorizeAbout('employees-delete')) {
            return $denied;
        }

        $member = AboutMember::findOrFail($id);
        $member->is_active = false;
        $member->save();

        return redirect('about-us')->with('message', trans('file.About member removed successfully'));
    }

    public function settings()
    {
        if ($denied = $this->authorizeAbout()) {
            return $denied;
        }

        $about = SiteContent::get('about_page', []);

        return view('about_us.settings', compact('about'));
    }

    public function settingsStore(Request $request)
    {
        if ($denied = $this->authorizeAbout('employees-edit')) {
            return $denied;
        }

        $data = SiteContent::all();
        $about = $data['about_page'] ?? [];

        foreach (['hero_subtitle', 'mission_title', 'mission_p1', 'mission_p2', 'mission_p3', 'heart_badge', 'intro_title', 'intro_text', 'regions', 'leaders_heading', 'leaders_subheading'] as $field) {
            $about[$field] = $request->input($field, '');
        }

        if ($request->hasFile('about_image')) {
            $request->validate(['about_image' => 'image|mimes:jpg,jpeg,png,gif,webp|max:8192']);
            $dir = public_path('uploads/about');
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            $file = $request->file('about_image');
            $name = 'about-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $name);
            ImageOptimizer::afterUpload($dir . '/' . $name, 'banner');
            $about['image'] = 'uploads/about/' . $name;
        }

        $data['about_page'] = $about;
        SiteContent::save($data);

        return redirect()->route('about_us.settings')->with('message', trans('file.About page updated successfully'));
    }

    public function values()
    {
        if ($denied = $this->authorizeAbout()) {
            return $denied;
        }

        $about = SiteContent::get('about_page', []);

        return view('about_us.values', compact('about'));
    }

    public function valuesStore(Request $request)
    {
        if ($denied = $this->authorizeAbout('employees-edit')) {
            return $denied;
        }

        $data = SiteContent::all();
        $about = $data['about_page'] ?? [];
        $about['values_heading'] = $request->input('values_heading', '');
        $about['values'] = $request->input('values', '');

        $data['about_page'] = $about;
        SiteContent::save($data);

        return redirect()->route('about_us.values')->with('message', trans('file.Our Values updated successfully'));
    }

    public function winners()
    {
        if ($denied = $this->authorizeAbout()) {
            return $denied;
        }

        $about = SiteContent::get('about_page', []);
        $year = SiteContent::aboutWinnersYear();
        $winners = $this->winnersForYear($year);

        return view('about_us.winners', compact('about', 'year', 'winners'));
    }

    public function winnersStore(Request $request)
    {
        if ($denied = $this->authorizeAbout('employees-edit')) {
            return $denied;
        }

        $year = trim($request->input('winners_year', '2025'));
        if ($year === '') {
            $year = '2025';
        }

        $data = SiteContent::all();
        $about = $data['about_page'] ?? [];
        $about['winners_year'] = $year;
        $about['winners_heading'] = $request->input('winners_heading', '');
        $data['about_page'] = $about;
        SiteContent::save($data);

        foreach (AboutWinner::PLACEMENTS as $placement => $label) {
            $entry = AboutWinner::firstOrNew(['year' => $year, 'placement' => $placement]);
            $entry->name = $request->input("name_{$placement}");
            $entry->bio = $request->input("bio_{$placement}");

            if ($request->hasFile("image_{$placement}")) {
                $request->validate([
                    "image_{$placement}" => 'image|mimes:jpg,jpeg,png,gif,webp|max:8192',
                ]);
                $entry->image = $this->storeAboutImage($request->file("image_{$placement}"), $placement . $year);
            }

            $entry->save();
        }

        return redirect()->route('about_us.winners')->with('message', trans('file.Winners updated successfully'));
    }

    private function winnersForYear($year)
    {
        $rows = AboutWinner::where('year', $year)->get()->keyBy('placement');
        $winners = [];
        foreach (AboutWinner::PLACEMENTS as $placement => $label) {
            $winners[$placement] = $rows->get($placement) ?: new AboutWinner([
                'year' => $year,
                'placement' => $placement,
            ]);
        }

        return $winners;
    }

    private function storeAboutImage($image, $slug)
    {
        $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
        $imageName = 'about_' . time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $slug) . '.' . $ext;
        $image->move('public/images/employee', $imageName);
        ImageOptimizer::afterUpload(public_path('images/employee/' . $imageName), 'portrait');

        return $imageName;
    }
}
