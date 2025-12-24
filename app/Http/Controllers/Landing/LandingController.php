<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q');
        $categorySlug = $request->get('category');

        // Get all active categories
        $categories = Category::where('is_active', true)
            ->withCount('articles')
            ->orderBy('name')
            ->get();

        // Query articles
        $query = Article::with(['category', 'admin'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc');

        // Filter by category
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Get articles with pagination
        $articles = $query->paginate(12)->appends([
            'q' => $search,
            'category' => $categorySlug
        ]);

        // Featured articles (latest 3)
        $featuredArticles = Article::with(['category', 'admin'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Popular articles (by view count)
        $popularArticles = Article::with(['category'])
            ->where('status', 'published')
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get();

        return view('landing.landing', compact(
            'articles',
            'featuredArticles',
            'popularArticles',
            'categories',
            'search',
            'categorySlug'
        ));
    }

    public function show($slug)
    {
        $article = Article::with(['category', 'admin', 'images'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment view count
        $article->increment('view_count');

        // Related articles (same category)
        $relatedArticles = Article::with(['category'])
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // Popular articles
        $popularArticles = Article::with(['category'])
            ->where('status', 'published')
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get();

        return view('landing.detail', compact('article', 'relatedArticles', 'popularArticles'));
    }
}
