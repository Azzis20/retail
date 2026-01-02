@extends('customer.layouts.app')


@section('title', 'Order Management')

@section('page-title', 'Order Section')

@section('content')





<div class="order-page-container">
    
    <!-- Page Header -->
    <div class="order-page-header">
        <h1 class="order-page-title">My Orders</h1>
    </div>


 
    <!-- Orders List -->
    @if($orders->count() > 0)
        <div class="orders-list">
            @foreach($orders as $order)
                <a href="{{ route('customer.order.show', $order->id) }}" class="order-card-link">
                    <div class="order-card">
                        
                        <!-- Order Header -->
                        <div class="order-card-header">
                            <div class="order-date">
                                <i class="fa-solid fa-calendar-days"></i>
                                <span>{{ $order->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="order-total">
                                <span class="total-label">Total</span>
                                <span class="total-amount">₱{{ number_format($order->bill->total_amount ?? 0, 2) }}</span>
                            </div>
                        </div>

                        <!-- Order Number -->
                        <h3 class="order-number-text">Order #{{ $order->id }}</h3>

                        <!-- Items Count -->
                        <p class="order-items-count">
                            {{ $order->orderItems->count() }} {{ $order->orderItems->count() == 1 ? 'item' : 'items' }}
                        </p>

                        <!-- Status Badge -->
                        <span class="order-status-badge 
                            @if($order->status == 'pending') pending-badge
                            @elseif($order->status == 'confirmed') confirmed-badge
                            @elseif($order->status == 'out-for-delivery') delivery-badge
                            @elseif($order->status == 'completed') completed-badge
                            @elseif($order->status == 'cancelled') cancelled-badge
                            @endif">
                            @if($order->status == 'pending') Pending
                            @elseif($order->status == 'confirmed') Confirmed
                            @elseif($order->status == 'out-for-delivery') Out for Delivery
                            @elseif($order->status == 'completed') Completed
                            @elseif($order->status == 'cancelled') Cancelled
                            @endif
                        </span>

                        <!-- View Details Arrow -->
                        

                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="pagination-wrapper">
                {{ $orders->links() }}
            </div>
        @endif

    @else
        <!-- No Orders Message -->
        <div class="no-orders-message">
            <i>📦</i>
            <p>No orders found</p>
            @if(request('search') || request('status'))
                <a href="{{ route('customer.order.index') }}" class="btn-clear-filters">Clear Filters</a>
            @endif
        </div>
    @endif

</div>

@endsection