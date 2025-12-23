@extends('layouts.dashboard')
@section('title', 'Edit Kategori')
@section('content')

<div class="container mt-6">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Edit Kategori</h4>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            {{-- Validation errors shown with SweetAlert2 --}}

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" {{ $category->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktif</label>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Batal</a>
                    <button class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    @if ($errors->any())
        let html = '<ul style="text-align:left;">';
        @foreach($errors->all() as $e)
            html += '<li>{{ $e }}</li>';
        @endforeach
        html += '</ul>';
        Swal.fire({icon:'error', title:'Validasi Gagal', html: html});
    @endif
});
</script>
@endpush

@endsection
