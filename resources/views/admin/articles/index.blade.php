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
            {{-- Search handled by navbar; alerts shown by SweetAlert2 --}}

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
document.addEventListener('DOMContentLoaded', function(){
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: "{{ session('success') }}",
        });
    @endif

    @if ($errors->any())
        let html = '<ul style="text-align:left;">';
        @foreach ($errors->all() as $error)
            html += '<li>{{ $error }}</li>';
        @endforeach
        html += '</ul>';
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal',
            html: html,
        });
    @endif

    // Delete confirmation using SweetAlert2
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data artikel ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush

@endsection
