@extends('layouts.dashboard')
@section('title', 'Tambah Artikel')
@section('content')

<div class="container mt-6">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Tambah Artikel Baru</h4>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Kembali</a>
        </div>

        <div class="card-body">
            {{-- Validation errors shown with SweetAlert2 --}}

            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Excerpt</label>
                        <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt') }}</textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Konten</label>
                        <textarea name="content" id="editor" class="form-control">{{ old('content') }}</textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Upload Gambar (Multiple)</label>
                        <input type="file" name="images[]" id="images" class="form-control" accept="image/*" multiple>
                        <div id="imagePreview" class="mt-3 row"></div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Batal</a>
                    <button class="btn btn-primary">Simpan Artikel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- CKEditor -->
<script src="https://cdn.ckeditor.com/4.22.0/full/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Show validation errors if any
    @if ($errors->any())
        let html = '<ul style="text-align:left;">';
        @foreach($errors->all() as $e)
            html += '<li>{{ $e }}</li>';
        @endforeach
        html += '</ul>';
        Swal.fire({icon:'error', title:'Validasi Gagal', html: html});
    @endif

    CKEDITOR.config.versionCheck = false;
    CKEDITOR.replace('editor', {
        height: 400,
        filebrowserUploadUrl: "{{ route('admin.articles.upload') }}?_token={{ csrf_token() }}",
        filebrowserUploadMethod: 'form',
        allowedContent: true,
    });

    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('imagePreview');
    imageInput.addEventListener('change', function(e){
        imagePreview.innerHTML = '';
        Array.from(e.target.files).forEach(file => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = function(ev){
                const col = document.createElement('div'); col.className = 'col-md-2 mb-2';
                const img = document.createElement('img'); img.src = ev.target.result; img.style.width='150px'; img.style.height='150px'; img.style.objectFit='cover'; img.className='rounded';
                col.appendChild(img); imagePreview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    });
});
</script>
@endpush

@endsection
