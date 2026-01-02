@extends('admin.layouts.app')

@section('title', 'Inventory Management')

@section('page-title', 'Inventory Management')

@section('content')

    <div class="product-header">
        <h1>Product</h1>
    </div>

    <!-- Search Bar -->
    <form action="{{ route('admin.product.search') }}" method="GET" class="search-form">
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

    <!-- Add Product Button -->
    <a href="{{ route('admin.product.create') }}" class="btn-add-product">
        <i class="fa-solid fa-plus"></i>
        <span>Add Product</span>
    </a>    

  
    <div class="category-tabs">
        
        <a href="{{ route('admin.product.index') }}" class="category-tab {{ $category == 'all' ? 'active' : '' }}">All</a>
        

        <a href="{{ route('admin.product.vegetable') }}" class="category-tab  {{ $category == 'vegetable' ? 'active' : '' }}">Vegetable</a>

         <a href="{{ route('admin.product.grocery') }}" class="category-tab  {{ $category == 'grocery' ? 'active' : '' }}">Grocery</a>

        
        
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
                    {{ $product->getStockStatus() }}
                    </span>
                                    
                    
                </div>
            </div>
            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn-make-changes">
                <i class="fa-solid fa-pen-to-square"></i>
                Make Changes
            </a>
        </div>
        
        @empty
        
            <div class="no-products-message">
                <i class="fa-solid fa-box-open"></i>
                <p>No products found</p>
                @if(request('search'))
                <a href="{{ route('admin.product.index') }}" class="btn-clear-search">Clear Search</a>
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
    // Category filter functionality
    document.querySelectorAll('.category-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Update active tab
            document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.category;
            const products = document.querySelectorAll('.product-card');
            
            products.forEach(product => {
                if (category === 'all') {
                    product.style.display = 'block';
                } else {
                    const productCategory = product.dataset.category;
                    product.style.display = productCategory === category ? 'block' : 'none';
                }
            });
        });
    });
</script>

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

  /* Available (Green) */
  .availability-badge.available {
    background-color: #28a745; /* Green */
  }

  /* Low Stock (Yellow) */
  .availability-badge.low-stock {
    background-color: #ffc107; /* Yellow */
  }

  /* Out of Stock (Red) */
  .availability-badge.out-of-stock {
    background-color: #dc3545; /* Red */
  }
</style>