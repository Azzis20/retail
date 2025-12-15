@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('page-title', 'Edit Product')

@section('content')

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>
                <strong>There were some errors with your submission:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="form-header">
        <a href="{{ route('admin.product.index') }}" class="back-button">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <h1>Product</h1>
    </div>

  <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="product-form">
    @csrf
    @method('PUT')

    <!-- Product Photo Upload -->
    <div class="form-group">
        <label class="form-label">Product Photo</label>
        <input type="file" id="productImage" name="image" accept="image/*" style="display: none;">
        <div class="upload-area-wrapper">
            <div class="current-image-display" id="currentImageWrapper">
                <img id="currentImage" src="{{ asset($product->picture) }}" alt="Current Product" class="current-product-image">
            </div>
        </div>
        @error('image')
            <span class="error-message">
                <i class="fa-solid fa-circle-exclamation"></i>
                {{ $message }}
            </span>
        @enderror
    </div>  

    <button type="button" class="btn-make-changes" onclick="document.getElementById('productImage').click()">
        Select Photo
    </button>

    <!-- Product Name -->
    <div class="form-group">
        <label class="form-label" style="margin-top:16px;">Product name</label>
        <input 
            type="text" 
            name="name" 
            class="form-input @error('name') input-error @enderror" 
            placeholder="e.g., Fresh Tomatoes"
            value="{{ old('name', $product->product_name) }}"
            required
        >
        @error('name')
            <span class="error-message">
                <i class="fa-solid fa-circle-exclamation"></i>
                {{ $message }}
            </span>
        @enderror
    </div>

    <!-- Category -->
    <div class="form-group">
        <label class="form-label">Category</label>
        <select name="category" class="form-select @error('category') input-error @enderror" required>
            <option value="">-- Select Category --</option>
            <option value="grocery" {{ old('category', $product->category) == 'grocery' ? 'selected' : '' }}>Grocery</option>
            <option value="vegetable" {{ old('category', $product->category) == 'vegetable' ? 'selected' : '' }}>Vegetable</option>
        </select>
        @error('category')
            <span class="error-message">
                <i class="fa-solid fa-circle-exclamation"></i>
                {{ $message }}
            </span>
        @enderror
    </div>

    <!-- Price & Unit -->
    <div class="form-row">
        <div class="form-group">
            <label class="form-label">Price</label>
            <div class="price-input-wrapper">
                <span class="currency-symbol">₱</span>
                <input 
                    type="number" 
                    name="price" 
                    class="form-input price-input @error('price') input-error @enderror" 
                    placeholder="00.00"
                    step="0.01"
                    value="{{ old('price', $product->price) }}"
                    required
                >
            </div>
            @error('price')
                <span class="error-message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Unit</label>
            <select name="unit" class="form-select @error('unit') input-error @enderror" required>
                <option value="">e.g., per kilo</option>
                <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>per kilo</option>
                <option value="piece" {{ old('unit', $product->unit) == 'piece' ? 'selected' : '' }}>per piece</option>
                <option value="pack" {{ old('unit', $product->unit) == 'pack' ? 'selected' : '' }}>per pack</option>
                <option value="bundle" {{ old('unit', $product->unit) == 'bundle' ? 'selected' : '' }}>per bundle</option>
            </select>
            @error('unit')  
                <span class="error-message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn-save">
        <i class="fa-solid fa-check"></i>
        Update Product
    </button>
</form>

<!-- Hidden Delete Form -->
 <div class="form-buttons-wrapper" style="max-width: 600px; margin: 0 auto; margin-top:16px">
    <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-delete-product">
            <i class="fa-solid fa-trash"></i>
            Delete Product
        </button>
    </form>
</div>


 



  

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('productImage');
    const currentImage = document.getElementById('currentImage');

    // File input change handler
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (!file) {
            return;
        }

        // 1. Create a FileReader object
        const reader = new FileReader();
        
        // 2. Define what happens when the file is read (onload)
        reader.onload = function(e) {
            // Update current image preview using the result (Base64 string)
            if (currentImage) {
                currentImage.src = e.target.result; 
            }
        };
        
        // 3. Read the file as a Data URL (Base64 encoded string)
        reader.readAsDataURL(file);
    });
});

// ... (rest of your delete confirmation function)
function confirmDelete() {
    // ...
}
</script>