<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $query = Category::orderBy('created_at', 'desc');

        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('slug', 'like', "%{$q}%");
            });
        }

        $categories = $query->paginate(10)->appends(['q' => $q]);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = Str::slug($request->name);
        $original = $slug;
        $count = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count;
            $count++;
        }

        Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = $category->slug;
        if ($category->name !== $request->name) {
            $slug = Str::slug($request->name);
            $original = $slug;
            $count = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $original . '-' . $count;
                $count++;
            }
        }

        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : false,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
