@extends('layouts.dashboard')
@section('title', 'Artikel')
@section('content')

<div class="container mt-6">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h4>Manajemen Artikel</h4>
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">Tambah Artikel</a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="GET" class="mb-3">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Cari judul/excerpt/konten..." value="{{ request('q') }}">
                    <button class="btn btn-outline-secondary">Cari</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Excerpt</th>
                            <th>Gambar</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                        <tr>
                            <td>{{ $loop->iteration + ($articles->currentPage() - 1) * $articles->perPage() }}</td>
                            <td>{{ $article->title }}</td>
                            <td>{{ $article->category->name ?? '-' }}</td>
                            <td>{{ Str::limit(strip_tags($article->excerpt ?? '-'), 50) }}</td>
                            <td>
                                @if($article->images->count() > 0)
                                    <img src="{{ asset('storage/' . $article->images->first()->image_path) }}" style="width:60px;height:60px;object-fit:cover;border-radius:4px;">
                                @else
                                    <span class="text-muted">Tidak ada gambar</span>
                                @endif
                            </td>
                            <td>{{ $article->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-warning btn-sm mb-1">Edit</a>
                                <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center">Tidak ada artikel</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">{{ $articles->links() }}</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function(e){
        e.preventDefault();
        const form = this.closest('.delete-form');
        if (confirm('Hapus artikel ini?')) form.submit();
    });
});
</script>
@endpush

@endsection
