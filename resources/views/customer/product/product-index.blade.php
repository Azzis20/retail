@extends('customer.layouts.app')

@section('title', 'Product')

@section('page-title', 'Product')

@section('content')

    <div class="product-header">
        <h1>Product</h1>
    </div>

    <!-- Success/Error Messages -->
    <!-- @if(session('success'))
    <div class="alert alert-success">
        <i class="fa-solid fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        <i class="fa-solid fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
    @endif -->

    <!-- Search Bar -->
    <form action="{{ route('customer.product.search') }}" method="GET" class="search-form">
        <div class="search-container">
            <button type="submit" class="search-btn" aria-label="Search">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
            </button>
            <input 
                type="text" 
                name="search" 
                class="search-input" 
                placeholder="search product.." 
                value="{{ request('search') }}"
            >
        </div>
    </form>

    <div class="category-tabs">
        <a href="{{ route('customer.product.index') }}" class="category-tab {{ $category == 'all' ? 'active' : '' }}">All</a>
        <a href="{{ route('customer.product.vegetable') }}" class="category-tab  {{ $category == 'vegetable' ? 'active' : '' }}">Vegetable</a>
        <a href="{{ route('customer.product.grocery') }}" class="category-tab  {{ $category == 'grocery' ? 'active' : '' }}">Grocery</a>
    </div>

    <div class="cart-button">
        <a href="{{ route('customer.product.cart') }}" class="cart-link">
            <i class="fa-solid fa-basket-shopping cart-icon"></i>
            <!-- Optional: Display Cart Item Count -->
            <!-- <span class="cart-item-count"> </span>   -->
        </a>
    </div>

    <!-- Product List Section -->
    <h2 class="product-list-title">Product List</h2>

    <div class="products-container">
        
        @forelse($products as $product)
        
        <div class="product-card" data-category="{{ strtolower($product->category ?? 'all') }}">
            <div class="product-main">
                @if ($product->image)
                    <img 
                        src="{{ $product->image }}" 
                        alt="{{ $product->product_name }}" 
                        class="product-image"
                    >
                @else
                    <div class="no-picture-container">
                        No Picture
                    </div>
                @endif  

                <div class="product-details">
                    <h3 class="product-name">{{ $product->product_name }}</h3>
                    <p class="product-price">₱{{ number_format($product->price, 2) }}</p>
                    
                    <!-- Stock Status Badge -->
                    @if($product->available_stock <= 0)
                        <span class="stock-badge out-of-stock">Out of Stock</span>
                    @elseif($product->available_stock <= $product->min_threshold)
                        <span class="stock-badge low-stock">Low Stock ({{ $product->available_stock }} left)</span>
                    @else
                        <span class="stock-badge in-stock">In Stock</span>
                    @endif
                </div>
            </div>

            <form action="{{ route('customer.product.addToCart', $product->id) }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="add-to-cart {{ $product->available_stock <= 0 ? 'disabled' : '' }}"
                    {{ $product->available_stock <= 0 ? 'disabled' : '' }}
                >
                    <i class="fa-solid fa-cart-plus"></i>
                    {{ $product->available_stock <= 0 ? 'Out of Stock' : 'Add To Cart' }}
                </button>
            </form>

        </div>
        
        @empty
        
        <div class="no-products-message">
            <i class="fa-solid fa-box-open"></i>
            <p>No products found</p>
            @if(request('search'))
            <a href="{{ route('customer.product.index') }}" class="btn-clear-search">Clear Search</a>
            @endif
        </div>
        
        @endforelse

    </div>  
    
    <!-- Paginator -->
    @if($products->hasPages())
    <div class="pagination-wrapper">
        {{ $products->appends(request()->query())->links() }}
    </div>
    @endif



<script>
    // Auto-hide success/error message after 3 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => alert.remove(), 300);
        });
    }, 3000);
</script>

@endsection