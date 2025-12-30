<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsApiController extends Controller
{
    /**
     * Get all articles with pagination
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);

            $articles = Article::with(['category:id,name,slug', 'admin:id,name'])
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Articles retrieved successfully',
                'data' => [
                    'articles' => $articles->items(),
                    'pagination' => [
                        'total' => $articles->total(),
                        'per_page' => $articles->perPage(),
                        'current_page' => $articles->currentPage(),
                        'last_page' => $articles->lastPage(),
                        'from' => $articles->firstItem(),
                        'to' => $articles->lastItem(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get latest articles
     */
    public function latest(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);

            $articles = Article::with(['category:id,name,slug', 'admin:id,name'])
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Latest articles retrieved successfully',
                'data' => $articles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve latest articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get popular articles by view count
     */
    public function popular(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);

            $articles = Article::with(['category:id,name,slug', 'admin:id,name'])
                ->where('status', 'published')
                ->orderBy('view_count', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Popular articles retrieved successfully',
                'data' => $articles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve popular articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get trending articles (combination of recent and popular)
     */
    public function trending(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);

            // Get articles from last 7 days ordered by views
            $articles = Article::with(['category:id,name,slug', 'admin:id,name'])
                ->where('status', 'published')
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('view_count', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Trending articles retrieved successfully',
                'data' => $articles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve trending articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured articles
     */
    public function featured(Request $request)
    {
        try {
            $limit = $request->get('limit', 5);

            $articles = Article::with(['category:id,name,slug', 'admin:id,name'])
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Featured articles retrieved successfully',
                'data' => $articles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve featured articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get article by slug
     */
    public function show($slug)
    {
        try {
            $article = Article::with(['category:id,name,slug', 'admin:id,name', 'images'])
                ->where('slug', $slug)
                ->where('status', 'published')
                ->first();

            if (!$article) {
                return response()->json([
                    'success' => false,
                    'message' => 'Article not found',
                ], 404);
            }

            // Increment view count
            $article->increment('view_count');

            return response()->json([
                'success' => true,
                'message' => 'Article retrieved successfully',
                'data' => $article
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get article by ID
     */
    public function showById($id)
    {
        try {
            $article = Article::with(['category:id,name,slug', 'admin:id,name', 'images'])
                ->where('id', $id)
                ->where('status', 'published')
                ->first();

            if (!$article) {
                return response()->json([
                    'success' => false,
                    'message' => 'Article not found',
                ], 404);
            }

            // Increment view count
            $article->increment('view_count');

            return response()->json([
                'success' => true,
                'message' => 'Article retrieved successfully',
                'data' => $article
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search articles
     */
    public function search(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'q' => 'required|string|min:3',
                'per_page' => 'nullable|integer|min:1|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $search = $request->get('q');
            $perPage = $request->get('per_page', 10);

            $articles = Article::with(['category:id,name,slug', 'admin:id,name'])
                ->where('status', 'published')
                ->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                          ->orWhere('excerpt', 'like', "%{$search}%")
                          ->orWhere('content', 'like', "%{$search}%");
                })
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Search completed successfully',
                'data' => [
                    'search_query' => $search,
                    'articles' => $articles->items(),
                    'pagination' => [
                        'total' => $articles->total(),
                        'per_page' => $articles->perPage(),
                        'current_page' => $articles->currentPage(),
                        'last_page' => $articles->lastPage(),
                        'from' => $articles->firstItem(),
                        'to' => $articles->lastItem(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get articles by category
     */
    public function byCategory($categorySlug, Request $request)
    {
        try {
            $category = Category::where('slug', $categorySlug)
                ->where('is_active', true)
                ->first();

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            $perPage = $request->get('per_page', 10);

            $articles = Article::with(['category:id,name,slug', 'admin:id,name'])
                ->where('category_id', $category->id)
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Articles by category retrieved successfully',
                'data' => [
                    'category' => $category,
                    'articles' => $articles->items(),
                    'pagination' => [
                        'total' => $articles->total(),
                        'per_page' => $articles->perPage(),
                        'current_page' => $articles->currentPage(),
                        'last_page' => $articles->lastPage(),
                        'from' => $articles->firstItem(),
                        'to' => $articles->lastItem(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve articles by category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get related articles (same category)
     */
    public function related($articleId, Request $request)
    {
        try {
            $article = Article::find($articleId);

            if (!$article) {
                return response()->json([
                    'success' => false,
                    'message' => 'Article not found',
                ], 404);
            }

            $limit = $request->get('limit', 5);

            $relatedArticles = Article::with(['category:id,name,slug', 'admin:id,name'])
                ->where('category_id', $article->category_id)
                ->where('id', '!=', $article->id)
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Related articles retrieved successfully',
                'data' => $relatedArticles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve related articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all categories
     * FIXED: Only count published articles
     */
    public function categories()
    {
        try {
            $categories = Category::where('is_active', true)
                ->withCount(['articles' => function ($query) {
                    $query->where('status', 'published');
                }])
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get home data (all in one for app home screen)
     * FIXED: Only count published articles in categories
     */
    public function home()
    {
        try {
            // Featured articles (top 5)
            $featuredArticles = Article::with(['category:id,name,slug', 'admin:id,name'])
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Latest articles (10)
            $latestArticles = Article::with(['category:id,name,slug', 'admin:id,name'])
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Popular articles (5)
            $popularArticles = Article::with(['category:id,name,slug', 'admin:id,name'])
                ->where('status', 'published')
                ->orderBy('view_count', 'desc')
                ->limit(5)
                ->get();

            // Trending articles (from last 7 days, 5 articles)
            $trendingArticles = Article::with(['category:id,name,slug', 'admin:id,name'])
                ->where('status', 'published')
                ->where('created_at', '>=', now()->subDays(7))
                ->orderBy('view_count', 'desc')
                ->limit(5)
                ->get();

            // Categories with published article count only
            $categories = Category::where('is_active', true)
                ->withCount(['articles' => function ($query) {
                    $query->where('status', 'published');
                }])
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Home data retrieved successfully',
                'data' => [
                    'featured' => $featuredArticles,
                    'latest' => $latestArticles,
                    'popular' => $popularArticles,
                    'trending' => $trendingArticles,
                    'categories' => $categories,
                    'stats' => [
                        'total_articles' => Article::where('status', 'published')->count(),
                        'total_categories' => Category::where('is_active', true)->count(),
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve home data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics
     * FIXED: Only count published articles per category
     */
    public function stats()
    {
        try {
            $totalArticles = Article::where('status', 'published')->count();
            $totalCategories = Category::where('is_active', true)->count();
            $totalViews = Article::where('status', 'published')->sum('view_count');

            // Articles per category - only published
            $categoriesStats = Category::where('is_active', true)
                ->withCount(['articles' => function ($query) {
                    $query->where('status', 'published');
                }])
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'articles_count' => $category->articles_count,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Statistics retrieved successfully',
                'data' => [
                    'total_articles' => $totalArticles,
                    'total_categories' => $totalCategories,
                    'total_views' => $totalViews,
                    'categories' => $categoriesStats,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
