<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // Stats
        $totalArticles = Article::count();
        $publishedCount = Article::where('status', 'published')->count();
        $draftCount = Article::where('status', 'draft')->count();
        $totalCategories = Category::count();
        $totalViews = Article::sum('view_count');

        // Recent
        $recentArticles = Article::with('category')->orderBy('created_at', 'desc')->limit(6)->get();
        $recentCategories = Category::orderBy('created_at', 'desc')->limit(6)->get();

        // Chart data - last 7 days
        $labels = [];
        $counts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d M');
            $counts[] = Article::whereDate('created_at', $date->toDateString())->count();
        }

        return view('admin.dashboard', compact(
            'totalArticles', 'publishedCount', 'draftCount', 'totalCategories', 'totalViews',
            'recentArticles', 'recentCategories', 'labels', 'counts'
        ));
    }
}
