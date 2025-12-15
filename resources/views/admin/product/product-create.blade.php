@extends('admin.layouts.app')

@section('title', 'Add Product')

@section('page-title', 'Add Product')

@section('content')

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif
    <div class="form-header">
        <a href="{{ route('admin.product.index') }}" class="back-button">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <h1>Product</h1>
    </div>

    <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" class="product-form">
        @csrf

        <!-- Product Photo Upload -->
        <div class="form-group">
            <label class="form-label">Product Photo</label>
            <div class="upload-area" id="uploadArea">
                <input type="file" id="productImage" name="image" accept="image/*" class="file-input" hidden>
                <div class="upload-content">
                    <i class="fa-solid fa-image upload-icon"></i>
                    <h3 class="upload-title">Upload a photo</h3>
                    <p class="upload-text">Tap here to select a product image from your gallery.</p>
                </div>
                <img id="imagePreview" class="image-preview" style="display: none;">
            </div>
        </div>

        <!-- Product Name -->
        <div class="form-group">
            <label class="form-label">Product name</label>
            <input 
                type="text" 
                name="name" 
                class="form-input" 
                placeholder="e.g., Fresh Tomatoes"
                required
            >
        </div>

        <!-- Category -->
        <div class="form-group">
            <label class="form-label">Category</label>
            
            <select 
                name="category" 
                class="form-input"
                required
            >
                <option value="">-- Select Category --</option>
                <option value="grocery" {{ old('category') == 'grocery' ? 'selected' : '' }}>Grocery</option>
                <option value="vegetable" {{ old('category') == 'vegetable' ? 'selected' : '' }}>Vegetable</option>
            </select>

            @error('category')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>


        

        <!-- Price and Unit Row -->
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Price</label>
                <div class="price-input-wrapper">
                    <span class="currency-symbol">₱</span>
                    <input 
                        type="number" 
                        name="price" 
                        class="form-input price-input" 
                        placeholder="00.00"
                        step="0.01"
                        required
                    >
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Unit</label>
                <select name="unit" class="form-select" required>
                    <option value="">e.g., per kilo</option>
                    <option value="per kilo">per kilo</option>
                    <option value="per piece">per piece</option>
                    <option value="per pack">per pack</option>
                    <option value="per bundle">per bundle</option>
                </select>
            </div>
        </div>

        <!-- Save Button -->
        <button type="submit" class="btn-save">
            Save
        </button>
    </form>

@endsection

<script>
    // Image upload preview
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('productImage');
        const imagePreview = document.getElementById('imagePreview');
        const uploadContent = document.querySelector('.upload-content');

        // Click to upload
        uploadArea.addEventListener('click', function() {
            fileInput.click();
        });

        // File selected
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    uploadContent.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        // Drag and drop
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('drag-over');
        });

        uploadArea.addEventListener('dragleave', function() {
            uploadArea.classList.remove('drag-over');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');
            
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                fileInput.files = e.dataTransfer.files;
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    uploadContent.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>