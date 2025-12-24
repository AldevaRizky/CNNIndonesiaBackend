# News API Documentation

API untuk aplikasi mobile (Flutter) dengan authentikasi menggunakan API Key.

## Base URL
```
http://localhost/api
```

## Authentication

Semua endpoint (kecuali `/test`) memerlukan API Key yang valid.

### Cara Menggunakan API Key:

**Option 1: HTTP Header (Recommended)**
```
X-API-Key: your-api-key-here
```

**Option 2: Query Parameter**
```
?api_key=your-api-key-here
```

### Setup API Key:

1. Buka file `.env`
2. Tambahkan baris:
```
API_KEY=your-secret-api-key-here
```
3. Restart server

---

## Endpoints

### 1. Test Connection (Public)
**GET** `/test`

No authentication required. Use this to test if API is working.

**Response:**
```json
{
    "success": true,
    "message": "API is working!",
    "timestamp": "2025-12-24 10:30:00",
    "version": "v1.0.0"
}
```

---

### 2. Home Data (All in One)
**GET** `/v1/home`

Mendapatkan semua data untuk tampilan home screen aplikasi.

**Response:**
```json
{
    "success": true,
    "message": "Home data retrieved successfully",
    "data": {
        "featured": [...], // 5 artikel featured
        "latest": [...],   // 10 artikel terbaru
        "popular": [...],  // 5 artikel populer
        "trending": [...], // 5 artikel trending (7 hari terakhir)
        "categories": [...], // Semua kategori dengan jumlah artikel
        "stats": {
            "total_articles": 32,
            "total_categories": 8
        }
    }
}
```

---

### 3. Get All Articles
**GET** `/v1/articles`

**Query Parameters:**
- `per_page` (optional): Jumlah artikel per halaman (default: 10)
- `page` (optional): Nomor halaman (default: 1)

**Response:**
```json
{
    "success": true,
    "message": "Articles retrieved successfully",
    "data": {
        "articles": [...],
        "pagination": {
            "total": 32,
            "per_page": 10,
            "current_page": 1,
            "last_page": 4,
            "from": 1,
            "to": 10
        }
    }
}
```

---

### 4. Get Latest Articles
**GET** `/v1/articles/latest`

**Query Parameters:**
- `limit` (optional): Jumlah artikel (default: 10, max: 50)

**Response:**
```json
{
    "success": true,
    "message": "Latest articles retrieved successfully",
    "data": [
        {
            "id": 1,
            "admin_id": 1,
            "category_id": 6,
            "title": "Presiden Umumkan Program Reformasi...",
            "slug": "presiden-umumkan-program-reformasi...",
            "excerpt": "Pemerintah meluncurkan program...",
            "content": "<p>Jakarta - Presiden Indonesia...</p>",
            "featured_image": null,
            "status": "published",
            "published_at": "2025-12-21T01:05:45.000000Z",
            "view_count": 1870,
            "created_at": "2025-12-24T01:05:45.000000Z",
            "updated_at": "2025-12-24T01:05:45.000000Z",
            "category": {
                "id": 6,
                "name": "Politik",
                "slug": "politik"
            },
            "admin": {
                "id": 1,
                "name": "Admin"
            }
        }
    ]
}
```

---

### 5. Get Popular Articles
**GET** `/v1/articles/popular`

Artikel dengan view_count tertinggi.

**Query Parameters:**
- `limit` (optional): Jumlah artikel (default: 10)

**Response:** Same as Latest Articles

---

### 6. Get Trending Articles
**GET** `/v1/articles/trending`

Artikel trending (dari 7 hari terakhir, diurutkan berdasarkan view_count).

**Query Parameters:**
- `limit` (optional): Jumlah artikel (default: 10)

**Response:** Same as Latest Articles

---

### 7. Get Featured Articles
**GET** `/v1/articles/featured`

Artikel featured untuk banner/carousel.

**Query Parameters:**
- `limit` (optional): Jumlah artikel (default: 5)

**Response:** Same as Latest Articles

---

### 8. Get Article by Slug
**GET** `/v1/articles/{slug}`

**Example:** `/v1/articles/presiden-umumkan-program-reformasi-birokrasi-nasional-2025`

**Response:**
```json
{
    "success": true,
    "message": "Article retrieved successfully",
    "data": {
        "id": 4,
        "title": "Presiden Umumkan Program Reformasi...",
        "slug": "presiden-umumkan-program-reformasi...",
        "excerpt": "...",
        "content": "...",
        "featured_image": null,
        "status": "published",
        "published_at": "2025-12-21T01:05:45.000000Z",
        "view_count": 1871,
        "category": {...},
        "admin": {...},
        "images": []
    }
}
```

---

### 9. Get Article by ID
**GET** `/v1/articles/id/{id}`

**Example:** `/v1/articles/id/4`

**Response:** Same as Get Article by Slug

---

### 10. Search Articles
**GET** `/v1/articles/search`

**Query Parameters:**
- `q` (required): Kata kunci pencarian (min 3 karakter)
- `per_page` (optional): Jumlah per halaman (default: 10, max: 100)
- `page` (optional): Nomor halaman

**Example:** `/v1/articles/search?q=presiden&per_page=20`

**Response:**
```json
{
    "success": true,
    "message": "Search completed successfully",
    "data": {
        "search_query": "presiden",
        "articles": [...],
        "pagination": {...}
    }
}
```

---

### 11. Get Related Articles
**GET** `/v1/articles/{article_id}/related`

Mendapatkan artikel terkait (kategori yang sama).

**Query Parameters:**
- `limit` (optional): Jumlah artikel (default: 5)

**Example:** `/v1/articles/4/related?limit=3`

**Response:** Same as Latest Articles

---

### 12. Get All Categories
**GET** `/v1/categories`

**Response:**
```json
{
    "success": true,
    "message": "Categories retrieved successfully",
    "data": [
        {
            "id": 6,
            "name": "Politik",
            "slug": "politik",
            "is_active": true,
            "created_at": "2025-12-24T00:45:45.000000Z",
            "updated_at": "2025-12-24T00:45:45.000000Z",
            "articles_count": 4
        }
    ]
}
```

---

### 13. Get Articles by Category
**GET** `/v1/categories/{slug}/articles`

**Query Parameters:**
- `per_page` (optional): Jumlah per halaman (default: 10)
- `page` (optional): Nomor halaman

**Example:** `/v1/categories/politik/articles`

**Response:**
```json
{
    "success": true,
    "message": "Articles by category retrieved successfully",
    "data": {
        "category": {
            "id": 6,
            "name": "Politik",
            "slug": "politik",
            "is_active": true,
            "articles_count": 4
        },
        "articles": [...],
        "pagination": {...}
    }
}
```

---

### 14. Get Statistics
**GET** `/v1/stats`

**Response:**
```json
{
    "success": true,
    "message": "Statistics retrieved successfully",
    "data": {
        "total_articles": 32,
        "total_categories": 8,
        "total_views": 150000,
        "categories": [
            {
                "id": 6,
                "name": "Politik",
                "slug": "politik",
                "articles_count": 4
            }
        ]
    }
}
```

---

## Error Responses

### 401 Unauthorized (Invalid API Key)
```json
{
    "success": false,
    "message": "Invalid or missing API key",
    "error": "Unauthorized access"
}
```

### 404 Not Found
```json
{
    "success": false,
    "message": "Article not found"
}
```

### 422 Validation Error
```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "q": ["The q field is required."]
    }
}
```

### 500 Internal Server Error
```json
{
    "success": false,
    "message": "Failed to retrieve articles",
    "error": "Error message details"
}
```

---

## Flutter Example Code

### Setup HTTP Client with API Key

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

class ApiService {
  static const String baseUrl = 'http://your-domain.com/api';
  static const String apiKey = 'your-secret-api-key-here';
  
  static Future<Map<String, dynamic>> getHomeData() async {
    final response = await http.get(
      Uri.parse('$baseUrl/v1/home'),
      headers: {
        'X-API-Key': apiKey,
        'Accept': 'application/json',
      },
    );
    
    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to load home data');
    }
  }
  
  static Future<Map<String, dynamic>> getArticleDetail(String slug) async {
    final response = await http.get(
      Uri.parse('$baseUrl/v1/articles/$slug'),
      headers: {
        'X-API-Key': apiKey,
        'Accept': 'application/json',
      },
    );
    
    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to load article');
    }
  }
  
  static Future<Map<String, dynamic>> searchArticles(String query) async {
    final response = await http.get(
      Uri.parse('$baseUrl/v1/articles/search?q=$query'),
      headers: {
        'X-API-Key': apiKey,
        'Accept': 'application/json',
      },
    );
    
    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to search articles');
    }
  }
}
```

---

## Testing with cURL

### Test Connection
```bash
curl http://localhost/api/test
```

### Get Home Data
```bash
curl -H "X-API-Key: your-secret-api-key-here" \
     http://localhost/api/v1/home
```

### Get Latest Articles
```bash
curl -H "X-API-Key: your-secret-api-key-here" \
     http://localhost/api/v1/articles/latest?limit=5
```

### Search Articles
```bash
curl -H "X-API-Key: your-secret-api-key-here" \
     "http://localhost/api/v1/articles/search?q=presiden"
```

---

## Notes

1. **API Key Security**: Jangan commit API key ke repository. Gunakan environment variables.
2. **Rate Limiting**: Pertimbangkan menambahkan throttle middleware untuk mencegah abuse.
3. **Caching**: Gunakan cache untuk endpoint yang sering diakses (categories, stats).
4. **Image URLs**: Semua image path di response akan otomatis include full URL dengan `asset('storage/...')`.
5. **Pagination**: Gunakan pagination untuk list yang panjang agar performance tetap optimal.

---

## Support

Untuk pertanyaan atau issue, silakan hubungi developer.
