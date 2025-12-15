@extends('admin.layouts.app')

@section('title', 'Manage Order')

@section('page-title', 'Order Management')

@section('content')

<div class="order-history-container">
    <h1 class="order-history-title">Order History</h1>

    <!-- Search Bar -->
    <div class="search-container">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input type="text" class="search-input" placeholder="search product..">
    </div>

    <!-- Filter Tabs -->
    <div class="order-filter-tabs">
        <button class="order-tab active" data-filter="all">All</button>
        <button class="order-tab" data-filter="completed">Completed</button>
        <button class="order-tab" data-filter="in-progress">In progress</button>
    </div>

    <!-- Orders List -->
    <div class="orders-list">

    

        @foreach($orders as $order)
        <div class="order-card" data-status="{{ strtolower($order->status) }}">
            <a href="{{ route('admin.order.show',$order->id) }}">
            <div class="order-card-header">
                <div class="order-date">
                    <i>📅</i>
                    <span>{{ $order->created_at->format('F d, Y') }}</span>
                </div>
                <div class="order-total">
                    <span class="total-label">{{ $order->status === 'cancelled' ? 'Total Paid' : 'Total' }}</span>
                    <span class="total-amount">₱{{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <h3 class="order-customer-name">{{ $order->customer->fname . ' ' . $order->customer->lname }}</h3>



            <div class="order-status-badge {{ strtolower($order->status) }}-badge">
                {{ ucfirst($order->status) }}
            </div>

            <!-- <button class="order-confirm-btn {{ $order->status === 'cancelled' ? 'disabled-btn' : 'active-btn' }}" 
                    {{ $order->status === 'cancelled' ? 'disabled' : '' }}>
                Confirm
            </button> -->
        </a>
        </div>
        @endforeach

        

        

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
                } else if (filter === 'in-progress') {
                    // Show in-progress orders
                    order.style.display = order.dataset.status === 'pending' ? 'block' : 'none';
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