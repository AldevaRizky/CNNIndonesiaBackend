<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $query = Article::with(['category', 'images'])->orderBy('created_at', 'desc');

        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('excerpt', 'like', "%{$q}%")
                      ->orWhere('content', 'like', "%{$q}%");
            });
        }

        $articles = $query->paginate(10)->appends(['q' => $q]);

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'nullable|in:draft,published',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $slug = Str::slug($request->title);
        $original = $slug;
        $count = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count;
            $count++;
        }

        $articleData = [
            'admin_id' => auth()->id() ?? 1,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => $slug,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'status' => $request->status ?? 'draft',
            'published_at' => $request->published_at ?? null,
        ];

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $img = $request->file('featured_image');
            $imgName = time() . '_featured_' . uniqid() . '.' . $img->getClientOriginalExtension();
            $imgPath = $img->storeAs('articles', $imgName, 'public');
            $articleData['featured_image'] = $imgPath;
        }

        $article = Article::create($articleData);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('articles', $imageName, 'public');

                ArticleImage::create([
                    'article_id' => $article->id,
                    'image_path' => $path,
                ]);
                // if no featured_image set, use first uploaded image as featured
                if (empty($article->featured_image)) {
                    $article->featured_image = $path;
                    $article->save();
                }
            }
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $article = Article::with('images')->findOrFail($id);
        $categories = Category::all();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'nullable|in:draft,published',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $slug = $article->slug;
        if ($article->title !== $request->title) {
            $slug = Str::slug($request->title);
            $original = $slug;
            $count = 1;
            while (Article::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $original . '-' . $count;
                $count++;
            }
        }

        $data = [
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => $slug,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'status' => $request->status ?? $article->status,
            'published_at' => $request->published_at ?? $article->published_at,
        ];

        // Handle featured image replacement
        if ($request->hasFile('featured_image')) {
            // delete old
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $img = $request->file('featured_image');
            $imgName = time() . '_featured_' . uniqid() . '.' . $img->getClientOriginalExtension();
            $imgPath = $img->storeAs('articles', $imgName, 'public');
            $data['featured_image'] = $imgPath;
        }

        $article->update($data);

        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = ArticleImage::find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('articles', $imageName, 'public');

                ArticleImage::create([
                    'article_id' => $article->id,
                    'image_path' => $path,
                ]);
                // ensure featured_image exists
                if (empty($article->featured_image)) {
                    $article->featured_image = $path;
                    $article->save();
                }
            }
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        // delete gallery images
        foreach ($article->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        // delete featured image
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            try {
                $image = $request->file('upload');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('ckeditor', $imageName, 'public');
                $url = asset('storage/' . $path);

                // If CKEditor expects a JavaScript callback (iframe POST), return script
                if ($request->has('CKEditorFuncNum')) {
                    $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                    $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', 'Image uploaded successfully');</script>";
                    return response($response);
                }

                // Otherwise, return JSON (used by XHR uploads)
                return response()->json([
                    'uploaded' => 1,
                    'fileName' => $imageName,
                    'url' => $url,
                ]);
            } catch (\Exception $e) {
                if ($request->has('CKEditorFuncNum')) {
                    $CKEditorFuncNum = $request->input('CKEditorFuncNum');
                    $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '', 'Upload failed: " . $e->getMessage() . "');</script>";
                    return response($response);
                }

                return response()->json([
                    'uploaded' => 0,
                    'error' => ['message' => 'Upload failed: ' . $e->getMessage()]
                ], 500);
            }
        }

        if ($request->has('CKEditorFuncNum')) {
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '', 'No file uploaded');</script>";
            return response($response);
        }

        return response()->json([
            'uploaded' => 0,
            'error' => ['message' => 'No file uploaded']
        ], 400);
    }

    public function show($id)
    {
        $article = Article::with(['category', 'images', 'admin'])->findOrFail($id);
        return view('admin.articles.show', compact('article'));
    }
}
