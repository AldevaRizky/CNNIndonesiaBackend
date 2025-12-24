// lib/services/api_service.dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {
  // Configuration
  static const String baseUrl = 'http://your-domain.com/api';
  static const String apiKey = 'cnn-news-api-key-2025-secret';
  
  // Headers
  static Map<String, String> get headers => {
    'X-API-Key': apiKey,
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  };

  // Generic GET request
  static Future<Map<String, dynamic>> _get(String endpoint) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl$endpoint'),
        headers: headers,
      );

      if (response.statusCode == 200) {
        return json.decode(response.body);
      } else if (response.statusCode == 401) {
        throw Exception('Invalid API Key');
      } else {
        throw Exception('Failed to load data: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  // ============= HOME & STATS =============
  
  /// Get all home data (featured, latest, popular, trending, categories)
  static Future<Map<String, dynamic>> getHomeData() async {
    return await _get('/v1/home');
  }

  /// Get statistics
  static Future<Map<String, dynamic>> getStats() async {
    return await _get('/v1/stats');
  }

  // ============= ARTICLES =============

  /// Get all articles with pagination
  static Future<Map<String, dynamic>> getArticles({
    int perPage = 10,
    int page = 1,
  }) async {
    return await _get('/v1/articles?per_page=$perPage&page=$page');
  }

  /// Get latest articles
  static Future<Map<String, dynamic>> getLatestArticles({int limit = 10}) async {
    return await _get('/v1/articles/latest?limit=$limit');
  }

  /// Get popular articles
  static Future<Map<String, dynamic>> getPopularArticles({int limit = 10}) async {
    return await _get('/v1/articles/popular?limit=$limit');
  }

  /// Get trending articles (last 7 days)
  static Future<Map<String, dynamic>> getTrendingArticles({int limit = 10}) async {
    return await _get('/v1/articles/trending?limit=$limit');
  }

  /// Get featured articles
  static Future<Map<String, dynamic>> getFeaturedArticles({int limit = 5}) async {
    return await _get('/v1/articles/featured?limit=$limit');
  }

  /// Get article detail by slug
  static Future<Map<String, dynamic>> getArticleBySlug(String slug) async {
    return await _get('/v1/articles/$slug');
  }

  /// Get article detail by ID
  static Future<Map<String, dynamic>> getArticleById(int id) async {
    return await _get('/v1/articles/id/$id');
  }

  /// Search articles
  static Future<Map<String, dynamic>> searchArticles({
    required String query,
    int perPage = 10,
    int page = 1,
  }) async {
    final encodedQuery = Uri.encodeComponent(query);
    return await _get('/v1/articles/search?q=$encodedQuery&per_page=$perPage&page=$page');
  }

  /// Get related articles (same category)
  static Future<Map<String, dynamic>> getRelatedArticles(int articleId, {int limit = 5}) async {
    return await _get('/v1/articles/$articleId/related?limit=$limit');
  }

  // ============= CATEGORIES =============

  /// Get all categories
  static Future<Map<String, dynamic>> getCategories() async {
    return await _get('/v1/categories');
  }

  /// Get articles by category slug
  static Future<Map<String, dynamic>> getArticlesByCategory({
    required String categorySlug,
    int perPage = 10,
    int page = 1,
  }) async {
    return await _get('/v1/categories/$categorySlug/articles?per_page=$perPage&page=$page');
  }
}


// ============= MODELS =============

// lib/models/article.dart
class Article {
  final int id;
  final int adminId;
  final int categoryId;
  final String title;
  final String slug;
  final String? excerpt;
  final String? content;
  final String? featuredImage;
  final String status;
  final DateTime? publishedAt;
  final int viewCount;
  final DateTime createdAt;
  final DateTime updatedAt;
  final Category? category;
  final Admin? admin;

  Article({
    required this.id,
    required this.adminId,
    required this.categoryId,
    required this.title,
    required this.slug,
    this.excerpt,
    this.content,
    this.featuredImage,
    required this.status,
    this.publishedAt,
    required this.viewCount,
    required this.createdAt,
    required this.updatedAt,
    this.category,
    this.admin,
  });

  factory Article.fromJson(Map<String, dynamic> json) {
    return Article(
      id: json['id'],
      adminId: json['admin_id'],
      categoryId: json['category_id'],
      title: json['title'],
      slug: json['slug'],
      excerpt: json['excerpt'],
      content: json['content'],
      featuredImage: json['featured_image'],
      status: json['status'],
      publishedAt: json['published_at'] != null 
          ? DateTime.parse(json['published_at']) 
          : null,
      viewCount: json['view_count'],
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
      category: json['category'] != null 
          ? Category.fromJson(json['category']) 
          : null,
      admin: json['admin'] != null 
          ? Admin.fromJson(json['admin']) 
          : null,
    );
  }

  String get imageUrl {
    if (featuredImage != null && featuredImage!.isNotEmpty) {
      return 'http://your-domain.com/storage/$featuredImage';
    }
    return 'https://via.placeholder.com/400x220?text=No+Image';
  }
}

// lib/models/category.dart
class Category {
  final int id;
  final String name;
  final String slug;
  final bool isActive;
  final int? articlesCount;

  Category({
    required this.id,
    required this.name,
    required this.slug,
    required this.isActive,
    this.articlesCount,
  });

  factory Category.fromJson(Map<String, dynamic> json) {
    return Category(
      id: json['id'],
      name: json['name'],
      slug: json['slug'],
      isActive: json['is_active'],
      articlesCount: json['articles_count'],
    );
  }
}

// lib/models/admin.dart
class Admin {
  final int id;
  final String name;

  Admin({
    required this.id,
    required this.name,
  });

  factory Admin.fromJson(Map<String, dynamic> json) {
    return Admin(
      id: json['id'],
      name: json['name'],
    );
  }
}


// ============= USAGE EXAMPLES =============

// lib/screens/home_screen.dart
import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../models/article.dart';
import '../models/category.dart';

class HomeScreen extends StatefulWidget {
  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  bool isLoading = true;
  List<Article> featuredArticles = [];
  List<Article> latestArticles = [];
  List<Article> popularArticles = [];
  List<Category> categories = [];
  String? errorMessage;

  @override
  void initState() {
    super.initState();
    loadHomeData();
  }

  Future<void> loadHomeData() async {
    try {
      setState(() {
        isLoading = true;
        errorMessage = null;
      });

      final response = await ApiService.getHomeData();
      
      if (response['success']) {
        final data = response['data'];
        
        setState(() {
          featuredArticles = (data['featured'] as List)
              .map((json) => Article.fromJson(json))
              .toList();
          
          latestArticles = (data['latest'] as List)
              .map((json) => Article.fromJson(json))
              .toList();
          
          popularArticles = (data['popular'] as List)
              .map((json) => Article.fromJson(json))
              .toList();
          
          categories = (data['categories'] as List)
              .map((json) => Category.fromJson(json))
              .toList();
          
          isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        isLoading = false;
        errorMessage = e.toString();
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    if (isLoading) {
      return Scaffold(
        body: Center(child: CircularProgressIndicator()),
      );
    }

    if (errorMessage != null) {
      return Scaffold(
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(Icons.error_outline, size: 60, color: Colors.red),
              SizedBox(height: 16),
              Text('Error: $errorMessage'),
              SizedBox(height: 16),
              ElevatedButton(
                onPressed: loadHomeData,
                child: Text('Retry'),
              ),
            ],
          ),
        ),
      );
    }

    return Scaffold(
      appBar: AppBar(
        title: Text('CNN News'),
        actions: [
          IconButton(
            icon: Icon(Icons.search),
            onPressed: () {
              // Navigate to search screen
            },
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: loadHomeData,
        child: ListView(
          children: [
            // Featured Section
            Padding(
              padding: EdgeInsets.all(16),
              child: Text(
                'Featured',
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              ),
            ),
            Container(
              height: 200,
              child: ListView.builder(
                scrollDirection: Axis.horizontal,
                itemCount: featuredArticles.length,
                itemBuilder: (context, index) {
                  final article = featuredArticles[index];
                  return FeaturedArticleCard(article: article);
                },
              ),
            ),

            // Categories
            Padding(
              padding: EdgeInsets.all(16),
              child: Text(
                'Categories',
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              ),
            ),
            Container(
              height: 50,
              child: ListView.builder(
                scrollDirection: Axis.horizontal,
                itemCount: categories.length,
                itemBuilder: (context, index) {
                  final category = categories[index];
                  return CategoryChip(category: category);
                },
              ),
            ),

            // Latest Articles
            Padding(
              padding: EdgeInsets.all(16),
              child: Text(
                'Latest News',
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              ),
            ),
            ListView.builder(
              shrinkWrap: true,
              physics: NeverScrollableScrollPhysics(),
              itemCount: latestArticles.length,
              itemBuilder: (context, index) {
                final article = latestArticles[index];
                return ArticleListItem(article: article);
              },
            ),
          ],
        ),
      ),
    );
  }
}

// lib/screens/article_detail_screen.dart
class ArticleDetailScreen extends StatefulWidget {
  final String slug;

  ArticleDetailScreen({required this.slug});

  @override
  _ArticleDetailScreenState createState() => _ArticleDetailScreenState();
}

class _ArticleDetailScreenState extends State<ArticleDetailScreen> {
  bool isLoading = true;
  Article? article;
  List<Article> relatedArticles = [];
  String? errorMessage;

  @override
  void initState() {
    super.initState();
    loadArticle();
  }

  Future<void> loadArticle() async {
    try {
      setState(() {
        isLoading = true;
        errorMessage = null;
      });

      final response = await ApiService.getArticleBySlug(widget.slug);
      
      if (response['success']) {
        final articleData = Article.fromJson(response['data']);
        
        // Load related articles
        final relatedResponse = await ApiService.getRelatedArticles(
          articleData.id,
          limit: 5,
        );
        
        setState(() {
          article = articleData;
          relatedArticles = (relatedResponse['data'] as List)
              .map((json) => Article.fromJson(json))
              .toList();
          isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        isLoading = false;
        errorMessage = e.toString();
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    if (isLoading) {
      return Scaffold(
        appBar: AppBar(),
        body: Center(child: CircularProgressIndicator()),
      );
    }

    if (article == null || errorMessage != null) {
      return Scaffold(
        appBar: AppBar(),
        body: Center(child: Text('Error: $errorMessage')),
      );
    }

    return Scaffold(
      appBar: AppBar(
        title: Text('Article Detail'),
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Featured Image
            Image.network(
              article!.imageUrl,
              width: double.infinity,
              height: 250,
              fit: BoxFit.cover,
            ),
            
            Padding(
              padding: EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Category Badge
                  Container(
                    padding: EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(
                      color: Colors.red,
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Text(
                      article!.category?.name ?? '',
                      style: TextStyle(color: Colors.white, fontSize: 12),
                    ),
                  ),
                  
                  SizedBox(height: 12),
                  
                  // Title
                  Text(
                    article!.title,
                    style: TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  
                  SizedBox(height: 8),
                  
                  // Meta info
                  Row(
                    children: [
                      Icon(Icons.person, size: 16, color: Colors.grey),
                      SizedBox(width: 4),
                      Text(article!.admin?.name ?? 'Admin'),
                      SizedBox(width: 16),
                      Icon(Icons.visibility, size: 16, color: Colors.grey),
                      SizedBox(width: 4),
                      Text('${article!.viewCount} views'),
                    ],
                  ),
                  
                  SizedBox(height: 16),
                  Divider(),
                  SizedBox(height: 16),
                  
                  // Content (you'll need an HTML renderer package)
                  Text(article!.content ?? ''),
                  
                  SizedBox(height: 24),
                  
                  // Related Articles
                  if (relatedArticles.isNotEmpty) ...[
                    Text(
                      'Related Articles',
                      style: TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    SizedBox(height: 12),
                    ListView.builder(
                      shrinkWrap: true,
                      physics: NeverScrollableScrollPhysics(),
                      itemCount: relatedArticles.length,
                      itemBuilder: (context, index) {
                        return ArticleListItem(
                          article: relatedArticles[index],
                        );
                      },
                    ),
                  ],
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// lib/screens/search_screen.dart
class SearchScreen extends StatefulWidget {
  @override
  _SearchScreenState createState() => _SearchScreenState();
}

class _SearchScreenState extends State<SearchScreen> {
  final TextEditingController searchController = TextEditingController();
  List<Article> searchResults = [];
  bool isSearching = false;
  String? errorMessage;

  Future<void> performSearch(String query) async {
    if (query.length < 3) {
      setState(() {
        searchResults = [];
      });
      return;
    }

    try {
      setState(() {
        isSearching = true;
        errorMessage = null;
      });

      final response = await ApiService.searchArticles(query: query);
      
      if (response['success']) {
        setState(() {
          searchResults = (response['data']['articles'] as List)
              .map((json) => Article.fromJson(json))
              .toList();
          isSearching = false;
        });
      }
    } catch (e) {
      setState(() {
        isSearching = false;
        errorMessage = e.toString();
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: TextField(
          controller: searchController,
          decoration: InputDecoration(
            hintText: 'Search articles...',
            border: InputBorder.none,
          ),
          onSubmitted: performSearch,
        ),
        actions: [
          IconButton(
            icon: Icon(Icons.search),
            onPressed: () => performSearch(searchController.text),
          ),
        ],
      ),
      body: isSearching
          ? Center(child: CircularProgressIndicator())
          : searchResults.isEmpty
              ? Center(child: Text('No results found'))
              : ListView.builder(
                  itemCount: searchResults.length,
                  itemBuilder: (context, index) {
                    return ArticleListItem(article: searchResults[index]);
                  },
                ),
    );
  }
}
