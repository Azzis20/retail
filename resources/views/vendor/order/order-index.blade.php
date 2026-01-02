@extends('admin.layouts.app')

@section('title', 'Manage Order')

@section('page-title', 'Order Management')

@section('content')

<div class="order-history-container">
    <h1 class="order-history-title">Order History</h1>

    <!-- Search Bar -->
    <form action="{{ route('admin.order.search',$orders) }}" method="GET" class="search-form">
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

    <!-- Filter Tabs -->
     <div class="category-tabs">
        
        <a href="{{ route('admin.order.index') }}" class="category-tab {{ $status == 'all' ? 'active' : '' }}">All</a>
        

        <a href="{{ route('admin.order.pending') }}" class="category-tab  {{ $status == 'pending' ? 'active' : '' }}">Pending</a>

        <a href="{{ route('admin.order.ofd') }}" class="category-tab {{ $status == 'Out-for-delivery' ? 'active' : '' }}">Out for Delivery</a>

        <a href="{{ route('admin.order.completed') }}" class="category-tab  {{ $status == 'completed' ? 'active' : '' }}">Completed</a>

    </div>
    

    <!-- Orders List -->
    <div class="orders-list">
    @if ($orders->isEmpty())
        <div class="no-products-message">
                <i class="fa-solid fa-box-open"></i>
                <p>No orders found</p>
                @if(request('search'))
                <a href="{{ route('admin.order.index') }}" class="btn-clear-search">Clear Search</a>
                @endif
        </div>
    @else
        @foreach($orders as $order)
            <div class="order-card" data-status="{{ strtolower($order->status) }}">
                <a href="{{ route('admin.order.show',$order->id) }}">
                    <div class="order-card-header">

                        <div class="order-date">
                            <h4>
                            <i class="fa-solid fa-calendar-days"></i>
                            <span>{{ $order->created_at->format('F d, Y') }}</span>
                            </h4>
                        </div>
                        <div class="order-total">
                            <span class="total-label">{{ $order->status === 'cancelled' ? 'Total Paid' : 'Total' }}</span>
                            <span class="total-amount">
                                ₱{{ $order->bill ? number_format($order->bill->balance, 2) : '0.00' }}
                            </span>
                        </div>
                    </div>
                    <span class="info-value">ORD{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}</span>

                    <h3 class="order-customer-name">{{ $order->customer->fname . ' ' . $order->customer->lname }}</h3>

                    <span class="order-status-badge 
                            @if($order->status == 'pending') pending-badge
                            @elseif($order->status == 'confirmed') confirmed-badge
                            @elseif($order->status == 'out-for-delivery') delivery-badge
                            @elseif($order->status == 'completed') completed-badge
                            @elseif($order->status == 'cancelled') cancelled-badge
                            @endif">
                            @if($order->status == 'pending') Pending
                            @elseif($order->status == 'out-for-delivery') Out for Delivery
                            @elseif($order->status == 'completed') Completed
                            @elseif($order->status == 'cancelled') Cancelled
                            @endif
                    </span>
                    

                </a>
            </div>
        @endforeach
    @endif  
</div>

<!-- Paginator -->
    <div>
            {{ $orders->appends(request()->query())->links() }}
    </div>

</div>


<script>
    // Filter functionality
    document.querySelectorAll('.order-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Update active tab
            document.querySelectorAll('.order-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            const orders = document.querySelectorAll('.order-card');
            
                orders.forEach(order => {
    if (filter === 'all') {
        order.style.display = 'block';
    } else if (filter === 'completed') {
        // Show completed orders (you'll need to add data-status="completed" to completed orders)
        order.style.display = order.dataset.status === 'completed' ? 'block' : 'none';
    } else if (filter === 'pending') {
        // Show pending orders
        order.style.display = order.dataset.status === 'pending' ? 'block' : 'none';
    } else if (filter === 'Out-for-delivery') {
        // Show out-for-delivery orders
        order.style.display = order.dataset.status === 'Out-for-delivery' ? 'block' : 'none';
    }
});

        });
    });

    // Search functionality
    document.getElementById('orderSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const orders = document.querySelectorAll('.order-card');
        
        orders.forEach(order => {
            const customerName = order.querySelector('.order-customer-name').textContent.toLowerCase();
            order.style.display = customerName.includes(searchTerm) ? 'block' : 'none';
        });
    });
</script>

@endsection