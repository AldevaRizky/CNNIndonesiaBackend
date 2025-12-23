@extends('layouts.dashboard')
@section('title', 'Detail Artikel')
@section('content')

<div class="container mt-6">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-0">{{ $article->title }}</h3>
                <small class="text-muted">{{ $article->category->name ?? 'Uncategorized' }} • {{ $article->created_at->format('d M Y H:i') }}</small>
            </div>
            <div>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Kembali</a>
                <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-warning">Edit</a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-4">
                        <div class="border rounded p-3 bg-light" style="min-height:120px;">
                            {!! $article->content ?? '<p class="text-muted">Tidak ada konten</p>' !!}
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>Gambar</h5>
                        @if($article->images->count() > 0)
                            <div id="articleCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($article->images as $key => $image)
                                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100" style="height:400px;object-fit:cover;border-radius:6px;">
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#articleCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#articleCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                </button>
                            </div>
                        @else
                            <p class="text-muted">Tidak ada gambar</p>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <h6>Ringkasan</h6>
                        <p>{{ $article->excerpt ?? '-' }}</p>
                    </div>

                    <div class="mb-3">
                        <h6>Meta</h6>
                        <p><strong>Penulis:</strong> {{ $article->admin->name ?? '-' }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($article->status) }}</p>
                        <p><strong>Dilihat:</strong> {{ $article->view_count }}</p>
                        <p><strong>Dipublikasikan:</strong> {{ $article->published_at ? $article->published_at->format('d M Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Initialize Bootstrap tooltips if needed
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});
</script>
@endpush

@endsection
