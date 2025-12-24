# CNN News API - Quick Start Guide

## 🚀 API telah siap digunakan!

API lengkap untuk aplikasi mobile Flutter telah berhasil dibuat dengan fitur-fitur:

### ✅ Fitur API yang Tersedia

#### 1. **Home Data (All-in-One)**
- Featured articles (5)
- Latest articles (10)
- Popular articles (5)
- Trending articles (5)
- All categories dengan jumlah artikel
- Statistics (total articles, categories)

#### 2. **Articles Endpoints**
- Get all articles (dengan pagination)
- Latest articles
- Popular articles (by view count)
- Trending articles (7 hari terakhir, sorted by views)
- Featured articles
- Article detail (by slug atau ID)
- Search articles (dengan validation min 3 karakter)
- Related articles (same category)

#### 3. **Categories Endpoints**
- Get all categories (dengan article count)
- Get articles by category (dengan pagination)

#### 4. **Statistics**
- Total articles, categories, views
- Per-category statistics

### 🔐 Security

**API Key Authentication**
- Semua endpoint (kecuali `/test`) dilindungi dengan API key
- Bisa dikirim via:
  - HTTP Header: `X-API-Key: cnn-news-api-key-2025-secret`
  - Query Parameter: `?api_key=cnn-news-api-key-2025-secret`
- API key disimpan di `.env`: `API_KEY=cnn-news-api-key-2025-secret`

### 📚 Dokumentasi Lengkap

1. **API_DOCUMENTATION.md** - Dokumentasi lengkap semua endpoints
2. **FLUTTER_INTEGRATION.md** - Panduan integrasi dengan Flutter
3. **FLUTTER_EXAMPLE.dart** - Example code lengkap untuk Flutter
4. **CNN_News_API.postman_collection.json** - Postman collection untuk testing

### 🧪 Testing API

#### Test Connection (Public - No API Key)
```bash
curl http://localhost/news/NewsBackend/public/api/test
```

#### Test Home Data
```bash
curl -H "X-API-Key: cnn-news-api-key-2025-secret" \
     http://localhost/news/NewsBackend/public/api/v1/home
```

#### Test Latest Articles
```bash
curl -H "X-API-Key: cnn-news-api-key-2025-secret" \
     http://localhost/news/NewsBackend/public/api/v1/articles/latest?limit=5
```

#### Test Search
```bash
curl -H "X-API-Key: cnn-news-api-key-2025-secret" \
     "http://localhost/news/NewsBackend/public/api/v1/articles/search?q=presiden"
```

#### Test Popular Articles
```bash
curl -H "X-API-Key: cnn-news-api-key-2025-secret" \
     http://localhost/news/NewsBackend/public/api/v1/articles/popular?limit=5
```

#### Test Categories
```bash
curl -H "X-API-Key: cnn-news-api-key-2025-secret" \
     http://localhost/news/NewsBackend/public/api/v1/categories
```

#### Test Articles by Category
```bash
curl -H "X-API-Key: cnn-news-api-key-2025-secret" \
     http://localhost/news/NewsBackend/public/api/v1/categories/politik/articles
```

### 📱 Flutter Integration

#### 1. Install Dependencies
```yaml
dependencies:
  http: ^1.1.0
  flutter_html: ^3.0.0-beta.2
  cached_network_image: ^3.3.0
  intl: ^0.18.1
```

#### 2. Setup API Service
```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

class ApiService {
  static const String baseUrl = 'http://your-domain.com/api';
  static const String apiKey = 'cnn-news-api-key-2025-secret';
  
  static Map<String, String> get headers => {
    'X-API-Key': apiKey,
    'Accept': 'application/json',
  };

  static Future<Map<String, dynamic>> getHomeData() async {
    final response = await http.get(
      Uri.parse('$baseUrl/v1/home'),
      headers: headers,
    );
    return json.decode(response.body);
  }
}
```

#### 3. Load Data di Flutter
```dart
Future<void> loadHomeData() async {
  try {
    final response = await ApiService.getHomeData();
    
    if (response['success']) {
      final data = response['data'];
      // Use data['featured'], data['latest'], etc.
    }
  } catch (e) {
    print('Error: $e');
  }
}
```

### 🗂️ File Structure

```
NewsBackend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       └── NewsApiController.php  ✅ Main API Controller
│   │   └── Middleware/
│   │       └── ValidateApiKey.php  ✅ API Key Middleware
│   └── Models/
│       ├── Article.php
│       └── Category.php
├── routes/
│   ├── api.php  ✅ Updated to load api_news.php
│   └── api_news.php  ✅ All API routes
├── config/
│   └── app.php  ✅ Added API_KEY config
├── database/
│   └── seeders/
│       ├── CategorySeeder.php  ✅ 8 categories
│       └── ArticleSeeder.php  ✅ 32 articles
├── .env  ✅ Added API_KEY
├── API_DOCUMENTATION.md  ✅ Full API docs
├── FLUTTER_INTEGRATION.md  ✅ Flutter guide
├── FLUTTER_EXAMPLE.dart  ✅ Flutter code examples
└── CNN_News_API.postman_collection.json  ✅ Postman collection
```

### 📊 Database Content

- **8 Kategori**: Politik, Ekonomi, Teknologi, Olahraga, Hiburan, Lifestyle, Internasional, Otomotif
- **32 Artikel**: 4 artikel per kategori dengan konten realistis
- Semua artikel sudah published dengan view count random

### 🔄 Response Format

Semua response menggunakan format standar:

**Success Response:**
```json
{
  "success": true,
  "message": "Success message",
  "data": { ... }
}
```

**Error Response:**
```json
{
  "success": false,
  "message": "Error message",
  "error": "Error details"
}
```

### 🎯 Endpoints Summary

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| GET | `/test` | Test connection | ❌ No |
| GET | `/v1/home` | All home data | ✅ Yes |
| GET | `/v1/stats` | Statistics | ✅ Yes |
| GET | `/v1/articles` | All articles (paginated) | ✅ Yes |
| GET | `/v1/articles/latest` | Latest articles | ✅ Yes |
| GET | `/v1/articles/popular` | Popular articles | ✅ Yes |
| GET | `/v1/articles/trending` | Trending articles | ✅ Yes |
| GET | `/v1/articles/featured` | Featured articles | ✅ Yes |
| GET | `/v1/articles/{slug}` | Article detail | ✅ Yes |
| GET | `/v1/articles/id/{id}` | Article by ID | ✅ Yes |
| GET | `/v1/articles/search` | Search articles | ✅ Yes |
| GET | `/v1/articles/{id}/related` | Related articles | ✅ Yes |
| GET | `/v1/categories` | All categories | ✅ Yes |
| GET | `/v1/categories/{slug}/articles` | Category articles | ✅ Yes |

### 🛠️ Customization

#### Ganti API Key
Edit file `.env`:
```
API_KEY=your-custom-api-key-here
```

Lalu clear cache:
```bash
php artisan config:clear
php artisan config:cache
```

#### Ganti Base URL di Flutter
Edit `ApiConfig`:
```dart
class ApiConfig {
  static const String baseUrl = 'http://your-new-domain.com/api';
  static const String apiKey = 'your-custom-api-key-here';
}
```

#### URL untuk Development

**Android Emulator:**
```
http://10.0.2.2/news/NewsBackend/public/api
```

**iOS Simulator:**
```
http://localhost/news/NewsBackend/public/api
```

**Physical Device (same network):**
```
http://192.168.x.x/news/NewsBackend/public/api
```

### 🚨 Important Notes

1. **API Key Security**: 
   - Jangan commit API key ke public repository
   - Gunakan environment variables di production
   
2. **CORS**: 
   - Jika ada CORS error, update `config/cors.php`
   - Tambahkan allowed origins untuk mobile app
   
3. **Rate Limiting**: 
   - Pertimbangkan add throttle middleware
   - Protect API dari abuse
   
4. **Production**: 
   - Gunakan HTTPS, bukan HTTP
   - Update base URL ke production server
   - Enable caching untuk performance

### 📞 Next Steps

1. ✅ Test semua endpoints dengan Postman
2. ✅ Import Postman collection
3. ✅ Setup Flutter project
4. ✅ Copy API service code
5. ✅ Test dari Flutter app
6. ✅ Deploy to production server
7. ✅ Update Flutter base URL

### 💡 Tips

- Use `/v1/home` endpoint untuk initial app load (paling efisien)
- Implement caching di Flutter untuk better performance
- Use pagination untuk long lists
- Handle offline mode dengan local storage
- Add pull-to-refresh untuk better UX
- Implement error handling yang baik

### 📖 Documentation Links

- Full API Documentation: [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
- Flutter Integration Guide: [FLUTTER_INTEGRATION.md](FLUTTER_INTEGRATION.md)
- Flutter Code Examples: [FLUTTER_EXAMPLE.dart](FLUTTER_EXAMPLE.dart)
- Postman Collection: [CNN_News_API.postman_collection.json](CNN_News_API.postman_collection.json)

---

**API is ready! Happy coding! 🎉**
