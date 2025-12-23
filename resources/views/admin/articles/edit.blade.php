@extends('layouts.dashboard')
@section('title', 'Edit Artikel')
@section('content')

<div class="container mt-6">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Edit Artikel</h4>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Kembali</a>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ old('category_id', $article->category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $article->title) }}" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Excerpt</label>
                        <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt', $article->excerpt) }}</textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Konten</label>
                        <textarea name="content" id="editor" class="form-control">{{ old('content', $article->content) }}</textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Gambar Saat Ini</label>
                        <div class="row" id="existingImages">
                            @forelse($article->images as $image)
                                <div class="col-md-2 mb-2" id="existing-image-{{ $image->id }}">
                                    <div class="image-preview-item position-relative">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" style="width:150px;height:150px;object-fit:cover;border-radius:8px;border:2px solid #dee2e6;">
                                        <button type="button" class="remove-existing-image btn btn-sm btn-danger" data-image-id="{{ $image->id }}" style="position:absolute;top:5px;right:5px;">×</button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">Tidak ada gambar</p>
                            @endforelse
                        </div>
                        <div id="deletedImagesContainer"></div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Upload Gambar Baru (Multiple)</label>
                        <input type="file" name="images[]" id="images" class="form-control" accept="image/*" multiple>
                        <div id="imagePreview" class="mt-3 row"></div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Batal</a>
                    <button class="btn btn-primary">Update Artikel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/4.22.0/full/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    CKEDITOR.config.versionCheck = false;
    CKEDITOR.replace('editor', {
        height: 400,
        filebrowserUploadUrl: "{{ route('admin.articles.upload') }}?_token={{ csrf_token() }}",
        filebrowserUploadMethod: 'form',
        allowedContent: true,
    });

    const deletedImagesContainer = document.getElementById('deletedImagesContainer');
    document.querySelectorAll('.remove-existing-image').forEach(button => {
        button.addEventListener('click', function(){
            const imageId = this.getAttribute('data-image-id');
            const imgEl = document.getElementById('existing-image-' + imageId);
            if (!confirm('Hapus gambar ini?')) return;
            const input = document.createElement('input'); input.type='hidden'; input.name='delete_images[]'; input.value=imageId;
            deletedImagesContainer.appendChild(input);
            imgEl.style.display = 'none';
        });
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
