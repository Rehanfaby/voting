<?php

namespace App\Http\Controllers;

use App\Helpers\ImageOptimizer;
use App\Helpers\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class SiteGalleryController extends Controller
{
    private function assertCanManage()
    {
        $role = Role::find(Auth::user()->role_id);
        if (!$role || !$role->hasPermissionTo('general_setting')) {
            abort(403, 'Not permitted');
        }
    }

    public function index(Request $request)
    {
        $this->assertCanManage();
        $data = SiteContent::all();
        $categories = SiteContent::galleryCategories();
        $activeId = (string) $request->query('category', $categories[0]['id'] ?? 'general');
        $validIds = array_column($categories, 'id');
        if (!in_array($activeId, $validIds, true)) {
            $activeId = $categories[0]['id'] ?? 'general';
        }
        $items = array_values(array_filter($data['gallery'] ?? [], function ($g) use ($activeId) {
            $cat = is_array($g) ? ($g['category_id'] ?? 'general') : 'general';
            return (string) $cat === $activeId;
        }));

        return view('gallery.admin', [
            'categories' => $categories,
            'activeCategoryId' => $activeId,
            'items' => $items,
            'allItems' => $data['gallery'] ?? [],
        ]);
    }

    public function storeCategory(Request $request)
    {
        $this->assertCanManage();
        $name = trim((string) $request->input('name'));
        if ($name === '') {
            return redirect()->route('gallery.admin')->with('not_permitted', 'Category name is required.');
        }

        $data = SiteContent::all();
        $categories = SiteContent::galleryCategories();
        $id = preg_replace('/[^a-z0-9]+/', '-', strtolower($name));
        $id = trim($id, '-') ?: ('cat-' . (count($categories) + 1));
        $base = $id;
        $n = 2;
        $existing = array_column($categories, 'id');
        while (in_array($id, $existing, true)) {
            $id = $base . '-' . $n;
            $n++;
        }
        $categories[] = [
            'id' => $id,
            'name' => $name,
            'slug' => $id,
            'sort' => count($categories),
        ];
        $data['gallery_categories'] = $categories;
        SiteContent::save(SiteContent::normalizeGalleryData($data));

        return redirect()->route('gallery.admin', ['category' => $id])->with('message', 'Category created.');
    }

    public function deleteCategory(Request $request)
    {
        $this->assertCanManage();
        $id = trim((string) $request->input('category_id'));
        $data = SiteContent::all();
        $categories = SiteContent::galleryCategories();
        if (count($categories) <= 1) {
            return redirect()->route('gallery.admin')->with('not_permitted', 'Keep at least one gallery category.');
        }
        $fallback = null;
        $kept = [];
        foreach ($categories as $cat) {
            if ($cat['id'] === $id) {
                continue;
            }
            if ($fallback === null) {
                $fallback = $cat['id'];
            }
            $kept[] = $cat;
        }
        if ($fallback === null) {
            return redirect()->route('gallery.admin')->with('not_permitted', 'Could not delete category.');
        }
        $gallery = [];
        foreach ($data['gallery'] ?? [] as $g) {
            if (!is_array($g)) {
                $g = ['image' => $g, 'caption' => '', 'category_id' => $fallback];
            }
            if (($g['category_id'] ?? '') === $id) {
                $g['category_id'] = $fallback;
            }
            $gallery[] = $g;
        }
        $data['gallery_categories'] = $kept;
        $data['gallery'] = $gallery;
        SiteContent::save(SiteContent::normalizeGalleryData($data));

        return redirect()->route('gallery.admin', ['category' => $fallback])->with('message', 'Category deleted. Photos moved to another album.');
    }

    public function storeImages(Request $request)
    {
        $this->assertCanManage();
        $categoryId = trim((string) $request->input('category_id'));
        $categories = SiteContent::galleryCategories();
        $validIds = array_column($categories, 'id');
        if (!in_array($categoryId, $validIds, true)) {
            $categoryId = $categories[0]['id'] ?? 'general';
        }

        $data = SiteContent::all();
        $gallery = is_array($data['gallery'] ?? null) ? $data['gallery'] : [];

        $existing = (array) $request->input('gallery_existing', []);
        $captions = (array) $request->input('gallery_caption', []);
        $existingCats = (array) $request->input('gallery_category', []);
        $keptPaths = [];
        foreach ($existing as $i => $path) {
            $path = trim((string) $path);
            if ($path === '') {
                continue;
            }
            $keptPaths[$path] = true;
            $found = false;
            foreach ($gallery as &$g) {
                $img = is_array($g) ? ($g['image'] ?? '') : $g;
                if ((string) $img !== $path) {
                    continue;
                }
                if (!is_array($g)) {
                    $g = ['image' => $img, 'caption' => '', 'category_id' => $categoryId];
                }
                $g['caption'] = trim((string) ($captions[$i] ?? $g['caption'] ?? ''));
                $newCat = trim((string) ($existingCats[$i] ?? $g['category_id'] ?? $categoryId));
                if (in_array($newCat, $validIds, true)) {
                    $g['category_id'] = $newCat;
                }
                $found = true;
                break;
            }
            unset($g);
            if (!$found) {
                $gallery[] = [
                    'image' => $path,
                    'caption' => trim((string) ($captions[$i] ?? '')),
                    'category_id' => $categoryId,
                ];
            }
        }

        // Drop images from this category that were removed from the form
        $gallery = array_values(array_filter($gallery, function ($g) use ($categoryId, $keptPaths) {
            $img = is_array($g) ? ($g['image'] ?? '') : $g;
            $cat = is_array($g) ? ($g['category_id'] ?? 'general') : 'general';
            if ((string) $cat !== $categoryId) {
                return true;
            }
            return isset($keptPaths[$img]);
        }));

        $galleryDir = public_path('uploads/gallery');
        if (!is_dir($galleryDir)) {
            @mkdir($galleryDir, 0755, true);
        }
        $files = $request->file('gallery_images', []);
        if (is_array($files)) {
            foreach ($files as $file) {
                if (!$file || !$file->isValid()) {
                    continue;
                }
                $ext = strtolower($file->getClientOriginalExtension());
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
                    continue;
                }
                $name = 'gallery-' . time() . '-' . mt_rand(1000, 9999) . '.' . $ext;
                $file->move($galleryDir, $name);
                ImageOptimizer::afterUpload($galleryDir . '/' . $name, 'banner');
                $gallery[] = [
                    'image' => 'uploads/gallery/' . $name,
                    'caption' => '',
                    'category_id' => $categoryId,
                ];
            }
        }

        $data['gallery'] = array_values($gallery);
        SiteContent::save(SiteContent::normalizeGalleryData($data));

        return redirect()->route('gallery.admin', ['category' => $categoryId])->with('message', 'Gallery saved.');
    }

    public function deleteImage(Request $request)
    {
        $this->assertCanManage();
        $image = trim((string) $request->input('image'));
        if ($image === '') {
            return response()->json(['status' => 'error', 'message' => 'No image specified'], 422);
        }

        $data = SiteContent::all();
        $gallery = is_array($data['gallery'] ?? null) ? $data['gallery'] : [];
        $kept = [];
        $removed = false;
        foreach ($gallery as $g) {
            $img = is_array($g) ? ($g['image'] ?? '') : $g;
            if ((string) $img === $image) {
                $removed = true;
                continue;
            }
            $kept[] = $g;
        }
        $data['gallery'] = array_values($kept);
        SiteContent::save(SiteContent::normalizeGalleryData($data));

        if ($removed) {
            $path = ltrim(str_replace('\\', '/', $image), '/');
            foreach ([public_path($path), public_path('public/' . $path)] as $full) {
                if (is_file($full)) {
                    @unlink($full);
                    break;
                }
            }
        }

        return response()->json(['status' => 'ok', 'removed' => $removed]);
    }
}
