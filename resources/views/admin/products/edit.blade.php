@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Edit Produk: {{ $product->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Nama Produk*</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $product->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi*</label>
                        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori*</label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga*</label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                   value="{{ old('price', $product->price) }}" min="0" step="100" required>
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Stok*</label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                                   value="{{ old('stock', $product->stock) }}" min="0" required>
                            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Satuan*</label>
                            <select name="unit" class="form-select @error('unit') is-invalid @enderror" required>
                                <option value="kg" {{ $product->unit == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="ikat" {{ $product->unit == 'ikat' ? 'selected' : '' }}>Ikat</option>
                                <option value="buah" {{ $product->unit == 'buah' ? 'selected' : '' }}>Buah</option>
                            </select>
                            @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Asal Kebun</label>
                            <input type="text" name="origin" class="form-control" value="{{ old('origin', $product->origin) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Panen</label>
                            <input type="date" name="harvest_date" class="form-control" 
                                   value="{{ old('harvest_date', $product->harvest_date ? $product->harvest_date->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Badge</label>
                            <select name="badge" class="form-select">
                                <option value="">Tidak Ada</option>
                                <option value="FRESH" {{ $product->badge == 'FRESH' ? 'selected' : '' }}>FRESH</option>
                                <option value="ORGANIC" {{ $product->badge == 'ORGANIC' ? 'selected' : '' }}>ORGANIC</option>
                                <option value="BEST SELLER" {{ $product->badge == 'BEST SELLER' ? 'selected' : '' }}>BEST SELLER</option>
                                <option value="NEW" {{ $product->badge == 'NEW' ? 'selected' : '' }}>NEW</option>
                                <option value="PREMIUM" {{ $product->badge == 'PREMIUM' ? 'selected' : '' }}>PREMIUM</option>
                                <option value="SEASONAL" {{ $product->badge == 'SEASONAL' ? 'selected' : '' }}>SEASONAL</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Gambar Produk</label>
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded mb-2" alt="{{ $product->name }}">
                        @endif
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" onchange="previewImage(event)">
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div id="imagePreview" class="mt-3"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                            <label class="form-check-label">Aktif</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-top pt-3 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Produk
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" style="max-height: 200px;">`;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection