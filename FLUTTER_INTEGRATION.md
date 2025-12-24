# Flutter Integration Guide

Panduan lengkap untuk mengintegrasikan CNN News API dengan aplikasi Flutter.

## Prerequisites

1. Flutter SDK (versi 3.0 atau lebih baru)
2. Package yang dibutuhkan:
   ```yaml
   dependencies:
     flutter:
       sdk: flutter
     http: ^1.1.0
     flutter_html: ^3.0.0-beta.2  # Untuk render HTML content
     cached_network_image: ^3.3.0  # Untuk cache images
     intl: ^0.18.1  # Untuk format tanggal
   ```

## Setup

### 1. Install Dependencies

Tambahkan di `pubspec.yaml`:
```yaml
dependencies:
  flutter:
    sdk: flutter
  http: ^1.1.0
  flutter_html: ^3.0.0-beta.2
  cached_network_image: ^3.3.0
  intl: ^0.18.1
```

Jalankan:
```bash
flutter pub get
```

### 2. Konfigurasi API

Buat file `lib/config/api_config.dart`:
```dart
class ApiConfig {
  static const String baseUrl = 'http://your-domain.com/api';
  static const String apiKey = 'cnn-news-api-key-2025-secret';
  static const String storageUrl = 'http://your-domain.com/storage';
}
```

**PENTING:** 
- Ganti `your-domain.com` dengan domain server Anda
- Untuk development lokal di Android Emulator gunakan: `http://10.0.2.2/news/NewsBackend/public/api`
- Untuk development lokal di iOS Simulator gunakan: `http://localhost/news/NewsBackend/public/api`
- Untuk testing di device fisik, gunakan IP komputer Anda

### 3. Structure Folder

```
lib/
├── config/
│   └── api_config.dart
├── models/
│   ├── article.dart
│   ├── category.dart
│   └── admin.dart
├── services/
│   └── api_service.dart
├── screens/
│   ├── home_screen.dart
│   ├── article_detail_screen.dart
│   ├── search_screen.dart
│   └── category_screen.dart
└── widgets/
    ├── article_card.dart
    ├── category_chip.dart
    └── loading_widget.dart
```

## API Service Implementation

File lengkap tersedia di `FLUTTER_EXAMPLE.dart`, berikut ringkasan method yang tersedia:

### Home & Statistics
```dart
// Get semua data untuk home screen
ApiService.getHomeData()

// Get statistik
ApiService.getStats()
```

### Articles
```dart
// List artikel dengan pagination
ApiService.getArticles(perPage: 10, page: 1)

// Artikel terbaru
ApiService.getLatestArticles(limit: 10)

// Artikel populer
ApiService.getPopularArticles(limit: 10)

// Artikel trending (7 hari terakhir)
ApiService.getTrendingArticles(limit: 10)

// Artikel featured
ApiService.getFeaturedArticles(limit: 5)

// Detail artikel by slug
ApiService.getArticleBySlug('article-slug')

// Detail artikel by ID
ApiService.getArticleById(1)

// Search artikel
ApiService.searchArticles(query: 'keyword', perPage: 10, page: 1)

// Artikel terkait
ApiService.getRelatedArticles(articleId, limit: 5)
```

### Categories
```dart
// List semua kategori
ApiService.getCategories()

// Artikel by kategori
ApiService.getArticlesByCategory(
  categorySlug: 'politik',
  perPage: 10,
  page: 1
)
```

## Models

### Article Model
```dart
class Article {
  final int id;
  final String title;
  final String slug;
  final String? excerpt;
  final String? content;
  final String? featuredImage;
  final int viewCount;
  final DateTime createdAt;
  final Category? category;
  final Admin? admin;
  
  // Helper method untuk get image URL
  String get imageUrl {
    if (featuredImage != null) {
      return '${ApiConfig.storageUrl}/$featuredImage';
    }
    return 'https://via.placeholder.com/400x220';
  }
}
```

### Category Model
```dart
class Category {
  final int id;
  final String name;
  final String slug;
  final int? articlesCount;
}
```

## Usage Examples

### 1. Home Screen dengan Multiple Sections

```dart
class HomeScreen extends StatefulWidget {
  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  bool isLoading = true;
  Map<String, dynamic>? homeData;

  @override
  void initState() {
    super.initState();
    loadData();
  }

  Future<void> loadData() async {
    try {
      final response = await ApiService.getHomeData();
      if (response['success']) {
        setState(() {
          homeData = response['data'];
          isLoading = false;
        });
      }
    } catch (e) {
      // Handle error
      print('Error: $e');
    }
  }

  @override
  Widget build(BuildContext context) {
    if (isLoading) {
      return Center(child: CircularProgressIndicator());
    }

    return ListView(
      children: [
        FeaturedSection(articles: homeData['featured']),
        LatestSection(articles: homeData['latest']),
        PopularSection(articles: homeData['popular']),
        CategoriesSection(categories: homeData['categories']),
      ],
    );
  }
}
```

### 2. Article Detail dengan HTML Render

```dart
import 'package:flutter_html/flutter_html.dart';

class ArticleDetailScreen extends StatelessWidget {
  final Article article;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Detail')),
      body: SingleChildScrollView(
        child: Column(
          children: [
            CachedNetworkImage(
              imageUrl: article.imageUrl,
              height: 250,
              width: double.infinity,
              fit: BoxFit.cover,
              placeholder: (context, url) => CircularProgressIndicator(),
              errorWidget: (context, url, error) => Icon(Icons.error),
            ),
            Padding(
              padding: EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    article.title,
                    style: TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  SizedBox(height: 8),
                  Row(
                    children: [
                      Icon(Icons.visibility, size: 16),
                      SizedBox(width: 4),
                      Text('${article.viewCount} views'),
                      SizedBox(width: 16),
                      Icon(Icons.access_time, size: 16),
                      SizedBox(width: 4),
                      Text(DateFormat('dd MMM yyyy').format(article.createdAt)),
                    ],
                  ),
                  SizedBox(height: 16),
                  Html(
                    data: article.content ?? '',
                    style: {
                      "p": Style(fontSize: FontSize(16)),
                    },
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
```

### 3. Search dengan Debouncing

```dart
import 'dart:async';

class SearchScreen extends StatefulWidget {
  @override
  _SearchScreenState createState() => _SearchScreenState();
}

class _SearchScreenState extends State<SearchScreen> {
  final TextEditingController _controller = TextEditingController();
  Timer? _debounce;
  List<Article> results = [];
  bool isSearching = false;

  void onSearchChanged(String query) {
    if (_debounce?.isActive ?? false) _debounce!.cancel();
    
    _debounce = Timer(const Duration(milliseconds: 500), () {
      performSearch(query);
    });
  }

  Future<void> performSearch(String query) async {
    if (query.length < 3) return;
    
    setState(() => isSearching = true);
    
    try {
      final response = await ApiService.searchArticles(query: query);
      if (response['success']) {
        setState(() {
          results = (response['data']['articles'] as List)
              .map((json) => Article.fromJson(json))
              .toList();
          isSearching = false;
        });
      }
    } catch (e) {
      setState(() => isSearching = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: TextField(
          controller: _controller,
          decoration: InputDecoration(
            hintText: 'Search...',
            border: InputBorder.none,
          ),
          onChanged: onSearchChanged,
        ),
      ),
      body: isSearching
          ? Center(child: CircularProgressIndicator())
          : ListView.builder(
              itemCount: results.length,
              itemBuilder: (context, index) {
                return ArticleCard(article: results[index]);
              },
            ),
    );
  }

  @override
  void dispose() {
    _debounce?.cancel();
    _controller.dispose();
    super.dispose();
  }
}
```

### 4. Pull to Refresh

```dart
RefreshIndicator(
  onRefresh: () async {
    await loadData();
  },
  child: ListView.builder(
    itemCount: articles.length,
    itemBuilder: (context, index) {
      return ArticleCard(article: articles[index]);
    },
  ),
)
```

### 5. Pagination dengan Infinite Scroll

```dart
class ArticleListScreen extends StatefulWidget {
  @override
  _ArticleListScreenState createState() => _ArticleListScreenState();
}

class _ArticleListScreenState extends State<ArticleListScreen> {
  final ScrollController _scrollController = ScrollController();
  List<Article> articles = [];
  int currentPage = 1;
  bool isLoading = false;
  bool hasMore = true;

  @override
  void initState() {
    super.initState();
    loadArticles();
    _scrollController.addListener(_scrollListener);
  }

  void _scrollListener() {
    if (_scrollController.position.pixels ==
        _scrollController.position.maxScrollExtent) {
      if (!isLoading && hasMore) {
        loadArticles();
      }
    }
  }

  Future<void> loadArticles() async {
    if (isLoading) return;
    
    setState(() => isLoading = true);
    
    try {
      final response = await ApiService.getArticles(
        perPage: 10,
        page: currentPage,
      );
      
      if (response['success']) {
        final newArticles = (response['data']['articles'] as List)
            .map((json) => Article.fromJson(json))
            .toList();
        
        setState(() {
          articles.addAll(newArticles);
          currentPage++;
          hasMore = response['data']['pagination']['current_page'] <
              response['data']['pagination']['last_page'];
          isLoading = false;
        });
      }
    } catch (e) {
      setState(() => isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('All Articles')),
      body: ListView.builder(
        controller: _scrollController,
        itemCount: articles.length + (hasMore ? 1 : 0),
        itemBuilder: (context, index) {
          if (index == articles.length) {
            return Center(child: CircularProgressIndicator());
          }
          return ArticleCard(article: articles[index]);
        },
      ),
    );
  }

  @override
  void dispose() {
    _scrollController.dispose();
    super.dispose();
  }
}
```

## Error Handling

```dart
class ApiException implements Exception {
  final String message;
  final int? statusCode;

  ApiException(this.message, [this.statusCode]);

  @override
  String toString() => message;
}

// Dalam ApiService
static Future<Map<String, dynamic>> _get(String endpoint) async {
  try {
    final response = await http.get(
      Uri.parse('$baseUrl$endpoint'),
      headers: headers,
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else if (response.statusCode == 401) {
      throw ApiException('Invalid API Key', 401);
    } else if (response.statusCode == 404) {
      throw ApiException('Data not found', 404);
    } else {
      throw ApiException('Server error: ${response.statusCode}', response.statusCode);
    }
  } on SocketException {
    throw ApiException('No internet connection');
  } on HttpException {
    throw ApiException('HTTP error occurred');
  } on FormatException {
    throw ApiException('Invalid response format');
  } catch (e) {
    throw ApiException('Unexpected error: $e');
  }
}
```

## Caching Strategy

Untuk performance lebih baik, implement caching:

```dart
class CacheManager {
  static final Map<String, CacheItem> _cache = {};
  
  static void put(String key, dynamic data, {Duration duration = const Duration(minutes: 5)}) {
    _cache[key] = CacheItem(
      data: data,
      expiry: DateTime.now().add(duration),
    );
  }
  
  static dynamic get(String key) {
    final item = _cache[key];
    if (item != null && DateTime.now().isBefore(item.expiry)) {
      return item.data;
    }
    _cache.remove(key);
    return null;
  }
  
  static void clear() {
    _cache.clear();
  }
}

class CacheItem {
  final dynamic data;
  final DateTime expiry;
  
  CacheItem({required this.data, required this.expiry});
}

// Usage in ApiService
static Future<Map<String, dynamic>> getHomeData() async {
  final cached = CacheManager.get('home_data');
  if (cached != null) return cached;
  
  final response = await _get('/v1/home');
  CacheManager.put('home_data', response);
  return response;
}
```

## Testing

### Unit Test untuk ApiService

```dart
import 'package:flutter_test/flutter_test.dart';
import 'package:mockito/mockito.dart';
import 'package:http/http.dart' as http;

void main() {
  group('ApiService Tests', () {
    test('getHomeData returns data on success', () async {
      // Mock HTTP response
      // Test implementation
    });

    test('throws exception on API key error', () async {
      // Test error handling
    });
  });
}
```

## Tips & Best Practices

1. **Always use HTTPS in production**
2. **Store API key securely** (consider using flutter_secure_storage)
3. **Implement proper error handling**
4. **Use loading states** untuk UX yang baik
5. **Cache data** untuk mengurangi API calls
6. **Implement retry mechanism** untuk failed requests
7. **Handle offline mode** dengan local storage
8. **Add analytics** untuk tracking usage
9. **Use environment variables** untuk different environments (dev/staging/prod)
10. **Implement rate limiting** di client side

## Troubleshooting

### Connection Issues
- Android Emulator: gunakan `10.0.2.2` bukan `localhost`
- iOS Simulator: gunakan `localhost` atau IP komputer
- Physical Device: pastikan di network yang sama dan gunakan IP komputer

### CORS Issues
- Pastikan server sudah setup CORS dengan benar
- Tambahkan domain Flutter app ke allowed origins

### API Key Issues
- Verify API key di .env server sudah benar
- Check header format: `X-API-Key: your-key`

## Resources

- [API Documentation](./API_DOCUMENTATION.md)
- [Postman Collection](./CNN_News_API.postman_collection.json)
- [Flutter HTTP Package](https://pub.dev/packages/http)
- [Flutter HTML Package](https://pub.dev/packages/flutter_html)

## Support

Untuk bantuan lebih lanjut, hubungi tim development.
