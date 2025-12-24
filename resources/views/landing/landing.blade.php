<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CNN News - Berita Terkini Indonesia</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f8f9fa; }
        
        /* Navbar */
        .navbar-brand img { height: 35px; }
        .navbar { background: #cc0000 !important; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .navbar .nav-link { color: #fff !important; font-weight: 500; padding: 0.5rem 1rem !important; transition: all 0.3s; }
        .navbar .nav-link:hover { color: #ffeb3b !important; }
        .navbar .btn-login { background: #fff; color: #cc0000; border: none; font-weight: 600; padding: 0.5rem 1.5rem; border-radius: 50px; }
        .navbar .btn-login:hover { background: #ffeb3b; color: #cc0000; }
        
        /* Hero / Breaking News */
        .breaking-news { background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); color: #fff; padding: 1rem 0; }
        .breaking-label { background: #cc0000; padding: 0.3rem 1rem; border-radius: 3px; font-weight: 700; margin-right: 1rem; }
        
        /* Featured Section */
        .featured-card { position: relative; overflow: hidden; border-radius: 10px; height: 450px; cursor: pointer; transition: transform 0.3s; }
        .featured-card:hover { transform: translateY(-5px); }
        .featured-card img { width: 100%; height: 100%; object-fit: cover; }
        .featured-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.9)); padding: 2rem; color: #fff; }
        .featured-overlay .category-badge { background: #cc0000; padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.85rem; display: inline-block; margin-bottom: 0.5rem; }
        .featured-overlay h3 { font-weight: 700; margin-bottom: 0.5rem; }
        .featured-overlay .meta { font-size: 0.9rem; opacity: 0.9; }
        
        /* Article Cards */
        .article-card { background: #fff; border-radius: 10px; overflow: hidden; transition: all 0.3s; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 1.5rem; height: 100%; }
        .article-card:hover { box-shadow: 0 5px 20px rgba(0,0,0,0.15); transform: translateY(-3px); }
        .article-card img { width: 100%; height: 220px; object-fit: cover; }
        .article-card .card-body { padding: 1.5rem; }
        .article-card .category-badge { background: #cc0000; color: #fff; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.75rem; font-weight: 600; display: inline-block; margin-bottom: 0.75rem; }
        .article-card h5 { font-weight: 700; color: #1a1a1a; margin-bottom: 0.75rem; line-height: 1.4; min-height: 60px; }
        .article-card .excerpt { color: #666; font-size: 0.9rem; line-height: 1.6; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .article-card .meta { font-size: 0.85rem; color: #999; display: flex; justify-content: space-between; align-items: center; }
        
        /* Sidebar */
        .sidebar-card { background: #fff; border-radius: 10px; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 1.5rem; }
        .sidebar-card h5 { font-weight: 700; margin-bottom: 1.5rem; color: #1a1a1a; padding-bottom: 0.75rem; border-bottom: 3px solid #cc0000; }
        .popular-item { display: flex; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid #eee; transition: all 0.3s; }
        .popular-item:last-child { border-bottom: none; }
        .popular-item:hover { background: #f8f9fa; margin: 0 -1rem; padding: 1rem; border-radius: 8px; }
        .popular-item img { width: 80px; height: 60px; object-fit: cover; border-radius: 5px; }
        .popular-item .content { flex: 1; }
        .popular-item h6 { font-size: 0.9rem; font-weight: 600; margin-bottom: 0.25rem; line-height: 1.4; }
        .popular-item .meta { font-size: 0.75rem; color: #999; }
        
        /* Category Filter */
        .category-filter { background: #fff; border-radius: 10px; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 2rem; }
        .category-filter .btn { margin: 0.25rem; border-radius: 20px; font-size: 0.9rem; }
        .category-filter .btn-outline-danger.active { background: #cc0000; color: #fff; }
        
        /* Footer */
        footer { background: #1a1a1a; color: #fff; padding: 3rem 0 1rem; margin-top: 4rem; }
        footer h5 { color: #cc0000; margin-bottom: 1.5rem; font-weight: 700; }
        footer a { color: #ccc; text-decoration: none; transition: color 0.3s; }
        footer a:hover { color: #cc0000; }
        footer .social-icons a { font-size: 1.5rem; margin-right: 1rem; }
        
        /* Search Bar */
        .search-bar { position: relative; }
        .search-bar input { border-radius: 50px; padding: 0.75rem 3rem 0.75rem 1.5rem; border: 1px solid #ddd; }
        .search-bar button { position: absolute; right: 5px; top: 50%; transform: translateY(-50%); border-radius: 50px; padding: 0.5rem 1.5rem; background: #cc0000; border: none; color: #fff; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing.index') }}">
                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b1/CNN.svg" alt="CNN">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('landing.index') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('landing.index') }}?category=nasional">Nasional</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('landing.index') }}?category=internasional">Internasional</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('landing.index') }}?category=ekonomi">Ekonomi</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('landing.index') }}?category=olahraga">Olahraga</a></li>
                    <li class="nav-item ms-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-login">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-login">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Breaking News Ticker -->
    <div class="breaking-news">
        <div class="container">
            <div class="d-flex align-items-center">
                <span class="breaking-label">BREAKING NEWS</span>
                <marquee behavior="scroll" direction="left" scrollamount="5">
                    @foreach($featuredArticles->take(5) as $item)
                        <span class="me-5">{{ $item->title }}</span>
                    @endforeach
                </marquee>
            </div>
        </div>
    </div>

    <!-- Featured Articles -->
    <div class="container mt-4">
        <div class="row g-3">
            @if($featuredArticles->count() > 0)
                <!-- Main Featured -->
                <div class="col-lg-8">
                    <a href="{{ route('landing.show', $featuredArticles[0]->slug) }}" class="text-decoration-none">
                        <div class="featured-card">
                            <img src="{{ $featuredArticles[0]->featured_image ? asset('storage/' . $featuredArticles[0]->featured_image) : 'https://via.placeholder.com/800x450?text=No+Image' }}" alt="{{ $featuredArticles[0]->title }}">
                            <div class="featured-overlay">
                                <span class="category-badge">{{ $featuredArticles[0]->category->name }}</span>
                                <h3>{{ $featuredArticles[0]->title }}</h3>
                                <div class="meta">
                                    <i class="bi bi-calendar3 me-2"></i>{{ $featuredArticles[0]->published_at->format('d M Y') }}
                                    <span class="ms-3"><i class="bi bi-eye me-2"></i>{{ number_format($featuredArticles[0]->view_count) }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Side Featured -->
                <div class="col-lg-4">
                    <div class="row g-3">
                        @foreach($featuredArticles->slice(1, 2) as $featured)
                        <div class="col-12">
                            <a href="{{ route('landing.show', $featured->slug) }}" class="text-decoration-none">
                                <div class="featured-card" style="height: 217px;">
                                    <img src="{{ $featured->featured_image ? asset('storage/' . $featured->featured_image) : 'https://via.placeholder.com/400x220?text=No+Image' }}" alt="{{ $featured->title }}">
                                    <div class="featured-overlay">
                                        <span class="category-badge">{{ $featured->category->name }}</span>
                                        <h5 class="mb-2">{{ Str::limit($featured->title, 60) }}</h5>
                                        <div class="meta small">
                                            <i class="bi bi-calendar3 me-1"></i>{{ $featured->published_at->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Search & Category Filter -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-9">
                <div class="search-bar mb-3">
                    <form action="{{ route('landing.index') }}" method="GET">
                        <input type="text" name="q" class="form-control" placeholder="Cari berita..." value="{{ $search }}">
                        <button type="submit"><i class="bi bi-search"></i></button>
                    </form>
                </div>
                
                <div class="category-filter">
                    <a href="{{ route('landing.index') }}" class="btn btn-sm {{ !$categorySlug ? 'btn-danger' : 'btn-outline-danger' }}">Semua</a>
                    @foreach($categories as $cat)
                        <a href="{{ route('landing.index') }}?category={{ $cat->slug }}" class="btn btn-sm {{ $categorySlug == $cat->slug ? 'btn-danger' : 'btn-outline-danger' }}">
                            {{ $cat->name }} <span class="badge bg-light text-dark">{{ $cat->articles_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mt-4 mb-5">
        <div class="row">
            <!-- Articles Grid -->
            <div class="col-lg-9">
                @if($search)
                    <h4 class="mb-4">Hasil pencarian untuk: <strong>{{ $search }}</strong></h4>
                @endif
                
                <div class="row">
                    @forelse($articles as $article)
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('landing.show', $article->slug) }}" class="text-decoration-none">
                            <div class="article-card">
                                <img src="{{ $article->featured_image ? asset('storage/' . $article->featured_image) : 'https://via.placeholder.com/400x220?text=No+Image' }}" alt="{{ $article->title }}">
                                <div class="card-body">
                                    <span class="category-badge">{{ $article->category->name }}</span>
                                    @if($article->created_at->diffInHours(now()) < 24)
                                        <span class="badge bg-success ms-1" style="font-size: 0.7rem;">
                                            <i class="bi bi-clock-history me-1"></i>TERBARU
                                        </span>
                                    @endif
                                    <h5>{{ Str::limit($article->title, 60) }}</h5>
                                    <p class="excerpt">{{ Str::limit(strip_tags($article->excerpt ?: $article->content), 100) }}</p>
                                    <div class="meta">
                                        <span><i class="bi bi-calendar3 me-1"></i>{{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}</span>
                                        <span><i class="bi bi-eye me-1"></i>{{ number_format($article->view_count) }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle me-2"></i>Tidak ada artikel ditemukan.
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $articles->links('pagination::bootstrap-5') }}
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3">
                <!-- Popular Articles -->
                <div class="sidebar-card">
                    <h5><i class="bi bi-fire text-danger me-2"></i>Paling Populer</h5>
                    @foreach($popularArticles as $index => $popular)
                    <a href="{{ route('landing.show', $popular->slug) }}" class="text-decoration-none">
                        <div class="popular-item">
                            <div class="text-danger fw-bold" style="font-size: 1.5rem;">{{ $index + 1 }}</div>
                            <img src="{{ $popular->featured_image ? asset('storage/' . $popular->featured_image) : 'https://via.placeholder.com/80x60?text=No+Image' }}" alt="{{ $popular->title }}">
                            <div class="content">
                                <h6 class="text-dark">{{ Str::limit($popular->title, 50) }}</h6>
                                <div class="meta">
                                    <i class="bi bi-eye me-1"></i>{{ number_format($popular->view_count) }}
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- Categories Widget -->
                <div class="sidebar-card">
                    <h5><i class="bi bi-grid-3x3-gap me-2"></i>Kategori</h5>
                    <div class="list-group list-group-flush">
                        @foreach($categories as $cat)
                        <a href="{{ route('landing.index') }}?category={{ $cat->slug }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 px-0">
                            {{ $cat->name }}
                            <span class="badge bg-danger rounded-pill">{{ $cat->articles_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>Tentang CNN Indonesia</h5>
                    <p class="text-muted">Portal berita terpercaya yang menyajikan informasi terkini dari dalam dan luar negeri.</p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Kategori</h5>
                    <ul class="list-unstyled">
                        @foreach($categories->take(6) as $cat)
                        <li class="mb-2"><a href="{{ route('landing.index') }}?category={{ $cat->slug }}">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Hubungi Kami</h5>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>Jakarta, Indonesia</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i>+62 21 1234 5678</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i>info@cnn.co.id</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center text-muted">
                <p class="mb-0">&copy; {{ date('Y') }} CNN Indonesia. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
