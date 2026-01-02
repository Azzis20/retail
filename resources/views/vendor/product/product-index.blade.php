@extends('vendor.layouts.app')

@section('title', 'Inventory Management')

@section('page-title', 'Inventory Management')

@section('content')

    <div class="product-header">
        <h1>Product</h1>
    </div>

    <!-- Search Bar -->
    <form action="{{ route('vendor.product.search') }}" method="GET" class="search-form">
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

    <!-- Stock Status Filter Dropdown -->
    <div class="stock-filter-container">
        <form action="{{ route('vendor.product.filter-stock') }}" method="GET" class="stock-filter-form">
            <label for="stockStatusFilter" class="stock-filter-label">
                <i class="fa-solid fa-filter"></i>
                Filter by Stock:
            </label>
            <select 
                name="stock_status" 
                id="stockStatusFilter" 
                class="stock-filter-select"
                onchange="this.form.submit()"
            >
                <option value="all" {{ ($stockStatus ?? 'all') === 'all' ? 'selected' : '' }}>
                    All Products
                </option>
                <option value="available" {{ ($stockStatus ?? '') === 'available' ? 'selected' : '' }}>
                    Available
                </option>
                <option value="low-stock" {{ ($stockStatus ?? '') === 'low-stock' ? 'selected' : '' }}>
                    Low Stock
                </option>
                <option value="out-of-stock" {{ ($stockStatus ?? '') === 'out-of-stock' ? 'selected' : '' }}>
                    Out of Stock
                </option>
            </select>
        </form>
    </div>

    <!-- Add Product Button -->
    <a href="{{ route('vendor.product.create') }}" class="btn-add-product">
        <i class="fa-solid fa-plus"></i>
        <span>Add Product</span>
    </a>    

    <!-- Category Tabs -->
    <div class="category-tabs">
        <a href="{{ route('vendor.product.index') }}" class="category-tab {{ $category == 'all' ? 'active' : '' }}">All</a>
        <a href="{{ route('vendor.product.vegetable') }}" class="category-tab {{ $category == 'vegetable' ? 'active' : '' }}">Vegetable</a>
        <a href="{{ route('vendor.product.grocery') }}" class="category-tab {{ $category == 'grocery' ? 'active' : '' }}">Grocery</a>
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
                    
                    <span class="availability-badge {{ $product->getStockStatus() }}">
                        {{ ucfirst(str_replace('-', ' ', $product->getStockStatus())) }}
                    </span>
                </div>
            </div>
            <a href="{{ route('vendor.product.edit', $product->id) }}" class="btn-make-changes">
                <i class="fa-solid fa-pen-to-square"></i>
                Make Changes
            </a>
        </div>
        
        @empty
        
            <div class="no-products-message">
                <i class="fa-solid fa-box-open"></i>
                <p>No products found</p>
                @if(request('search') || request('stock_status'))
                <a href="{{ route('vendor.product.index') }}" class="btn-clear-search">Clear Filters</a>
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

@endsection

<style>
  .availability-badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
    text-transform: capitalize;
    color: white;
  }

  .availability-badge.available {
    background-color: #28a745;
  }

  .availability-badge.low-stock {
    background-color: #ffc107;
  }

  .availability-badge.out-of-stock {
    background-color: #dc3545;
  }
</style>