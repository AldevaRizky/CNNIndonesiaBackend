<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} - CNN News</title>
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
        .navbar .nav-link { color: #fff !important; font-weight: 500; padding: 0.5rem 1rem !important; }
        .navbar .nav-link:hover { color: #ffeb3b !important; }
        .navbar .btn-login { background: #fff; color: #cc0000; border: none; font-weight: 600; padding: 0.5rem 1.5rem; border-radius: 50px; }

        /* Article Detail */
        .article-header { background: #fff; padding: 3rem 0; margin-top: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .article-header .category-badge { background: #cc0000; color: #fff; padding: 0.5rem 1.5rem; border-radius: 25px; font-size: 0.9rem; font-weight: 600; display: inline-block; margin-bottom: 1rem; }
        .article-header h1 { font-weight: 800; color: #1a1a1a; line-height: 1.3; margin-bottom: 1.5rem; font-size: 2.5rem; }
        .article-meta { display: flex; gap: 2rem; color: #666; margin-bottom: 2rem; flex-wrap: wrap; }
        .article-meta span { display: flex; align-items: center; gap: 0.5rem; }

        .featured-image { width: 100%; height: 500px; object-fit: cover; border-radius: 15px; margin-bottom: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }

        .article-content { background: #fff; padding: 3rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .article-content p { font-size: 1.1rem; line-height: 1.8; color: #333; margin-bottom: 1.5rem; text-align: justify; }
        .article-content h2, .article-content h3 { font-weight: 700; color: #1a1a1a; margin-top: 2rem; margin-bottom: 1rem; }
        .article-content img { max-width: 100%; height: auto; border-radius: 10px; margin: 2rem 0; }
        .article-content ul, .article-content ol { margin: 1.5rem 0; padding-left: 2rem; }
        .article-content li { margin-bottom: 0.75rem; line-height: 1.8; }

        /* Gallery */
        .article-gallery { margin: 3rem 0; }
        .article-gallery h4 { font-weight: 700; margin-bottom: 1.5rem; }
        .article-gallery img { width: 100%; height: 250px; object-fit: cover; border-radius: 10px; cursor: pointer; transition: transform 0.3s; }
        .article-gallery img:hover { transform: scale(1.05); }

        /* Modal / full preview: show full image without cropping */
        .modal.fade .modal-dialog { transition: none; }
        .modal-backdrop.show { opacity: 0.95; background-color: #000; }
        .modal-fullscreen { width: 100vw !important; height: 100vh !important; max-width: 100vw !important; margin: 0 !important; padding: 0 !important; }
        .modal-fullscreen .modal-content { width: 100vw !important; height: 100vh !important; background: rgba(0,0,0,0.95); border: none; border-radius: 0; }
        .modal-fullscreen .modal-body { width: 100vw !important; height: 100vh !important; padding: 0 !important; display: flex !important; align-items: center !important; justify-content: center !important; overflow: hidden; }
        .modal-full-image { max-width: 98vw !important; max-height: 98vh !important; width: auto !important; height: auto !important; object-fit: contain !important; display: block !important; }
        .modal-header-full { position: fixed; top: 20px; right: 20px; z-index: 1060; border: none; padding: 0; background: transparent; }
        .modal-close-simple { background: rgba(255,255,255,0.2); border: none; color: #fff; font-size: 32px; font-weight: 300; line-height: 1; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; }
        .modal-close-simple:hover { background: rgba(255,255,255,0.3); transform: rotate(90deg); }
        .modal-close-simple:focus { outline: none; box-shadow: none; }

        /* Share Buttons */
        .share-section { background: #f8f9fa; padding: 2rem; border-radius: 15px; text-align: center; margin: 3rem 0; }
        .share-section h5 { font-weight: 700; margin-bottom: 1.5rem; }
        .share-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; margin: 0.5rem; border-radius: 50px; text-decoration: none; font-weight: 600; transition: all 0.3s; }
        .share-btn.facebook { background: #1877f2; color: #fff; }
        .share-btn.twitter { background: #1da1f2; color: #fff; }
        .share-btn.whatsapp { background: #25d366; color: #fff; }
        .share-btn:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }

        /* Sidebar */
        .sidebar-card { background: #fff; border-radius: 15px; padding: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.05); margin-bottom: 2rem; }
        .sidebar-card h5 { font-weight: 700; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 3px solid #cc0000; }
        .related-item { display: flex; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid #eee; transition: all 0.3s; }
        .related-item:last-child { border-bottom: none; }
        .related-item:hover { background: #f8f9fa; margin: 0 -1rem; padding: 1rem; border-radius: 8px; }
        .related-item img { width: 100px; height: 70px; object-fit: cover; border-radius: 8px; }
        .related-item h6 { font-size: 0.95rem; font-weight: 600; margin-bottom: 0.5rem; line-height: 1.4; color: #1a1a1a; }
        .related-item .meta { font-size: 0.8rem; color: #999; }

        /* Footer */
        footer { background: #1a1a1a; color: #fff; padding: 3rem 0 1rem; margin-top: 4rem; }
        footer h5 { color: #cc0000; margin-bottom: 1.5rem; font-weight: 700; }
        footer a { color: #ccc; text-decoration: none; }
        footer a:hover { color: #cc0000; }
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

    <!-- Breadcrumb -->
    <div class="container mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('landing.index') }}" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('landing.index') }}?category={{ $article->category->slug }}" class="text-decoration-none">{{ $article->category->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($article->title, 50) }}</li>
            </ol>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <!-- Article Content -->
            <div class="col-lg-8">
                <!-- Article Header -->
                <div class="article-header">
                    <div class="container">
                        <span class="category-badge">{{ $article->category->name }}</span>
                        <h1>{{ $article->title }}</h1>
                        <div class="article-meta">
                            <span><i class="bi bi-person-circle"></i>{{ $article->admin->name ?? 'Admin' }}</span>
                            <span><i class="bi bi-calendar3"></i>{{ $article->published_at->format('d F Y, H:i') }} WIB</span>
                            <span><i class="bi bi-eye"></i>{{ number_format($article->view_count) }} views</span>
                        </div>
                    </div>
                </div>

                <!-- Featured Image -->
                @if($article->featured_image)
                <div class="mt-4">
                    <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="featured-image">
                </div>
                @endif

                <!-- Article Content -->
                <div class="article-content mt-4">
                    @if($article->excerpt)
                    <div class="alert alert-light border-start border-danger border-4 mb-4">
                        <strong>{{ $article->excerpt }}</strong>
                    </div>
                    @endif

                    <div class="content">
                        {!! $article->content !!}
                    </div>
                </div>

                <!-- Article Gallery -->
                @if($article->images->count() > 0)
                <div class="article-gallery mt-4">
                    <div class="article-content">
                        <h4><i class="bi bi-images me-2"></i>Galeri Foto</h4>
                        <div class="row g-3">
                            @foreach($article->images as $image)
                            <div class="col-md-4">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Gallery Image" data-bs-toggle="modal" data-bs-target="#imageModal{{ $loop->index }}">

                                <!-- Modal -->
                                <div class="modal fade" id="imageModal{{ $loop->index }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
                                        <div class="modal-content bg-transparent">
                                            <div class="modal-header modal-header-full">
                                                <button type="button" class="modal-close-simple" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                                            </div>
                                            <div class="modal-body d-flex justify-content-center align-items-center p-0">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" class="modal-full-image" alt="Gallery Image">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Share Section -->
                <div class="share-section">
                    <h5><i class="bi bi-share me-2"></i>Bagikan Artikel Ini</h5>
                    <div>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('landing.show', $article->slug)) }}" target="_blank" class="share-btn facebook">
                            <i class="bi bi-facebook"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('landing.show', $article->slug)) }}&text={{ urlencode($article->title) }}" target="_blank" class="share-btn twitter">
                            <i class="bi bi-twitter-x"></i> Twitter
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . route('landing.show', $article->slug)) }}" target="_blank" class="share-btn whatsapp">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Popular Articles -->
                <div class="sidebar-card">
                    <h5><i class="bi bi-fire text-danger me-2"></i>Paling Populer</h5>
                    @foreach($popularArticles as $popular)
                    <a href="{{ route('landing.show', $popular->slug) }}" class="text-decoration-none">
                        <div class="related-item">
                            <img src="{{ $popular->featured_image ? asset('storage/' . $popular->featured_image) : 'https://via.placeholder.com/100x70?text=No+Image' }}" alt="{{ $popular->title }}">
                            <div>
                                <h6>{{ Str::limit($popular->title, 60) }}</h6>
                                <div class="meta">
                                    <i class="bi bi-eye me-1"></i>{{ number_format($popular->view_count) }}
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- Related Articles -->
                @if($relatedArticles->count() > 0)
                <div class="sidebar-card">
                    <h5><i class="bi bi-newspaper me-2"></i>Berita Terkait</h5>
                    @foreach($relatedArticles as $related)
                    <a href="{{ route('landing.show', $related->slug) }}" class="text-decoration-none">
                        <div class="related-item">
                            <img src="{{ $related->featured_image ? asset('storage/' . $related->featured_image) : 'https://via.placeholder.com/100x70?text=No+Image' }}" alt="{{ $related->title }}">
                            <div>
                                <h6>{{ Str::limit($related->title, 60) }}</h6>
                                <div class="meta">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $related->published_at->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif
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
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('landing.index') }}">Beranda</a></li>
                        <li class="mb-2"><a href="{{ route('landing.index') }}?category={{ $article->category->slug }}">{{ $article->category->name }}</a></li>
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
