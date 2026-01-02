@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')

    <div class="dashboard-header">
        <p class="date-text">Today, {{ date('d F') }}</p>
        <h1 class="greeting">{{ auth()->user()->fname . ' ' . auth()->user()->lname }}</h1>
    </div>

    <h2 class="section-title">Today's Overview</h2>
    
    <div class="stats-container"> 

        <!-- Fixed: Removed action-card class wrapper, using stat-card-link instead -->
        <a href="{{ route('admin.order.index') }}" class="stat-card-link">
            <div class="stat-card">  
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fa-solid fa-basket-shopping"></i>
                    </div>
                    <div class="stat-info">
                        <p class="stat-label">Total Orders Today</p>
                        <h3 class="stat-value">{{$countOrder}}</h3>
                    </div>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.product.index') }}" class="stat-card-link"> 
            <div class="stat-card">  
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <div class="stat-info">
                        <p class="stat-label">Inventory Alert</p>
                        <h3 class="stat-value">{{$stock_alert_count}}</h3>
                    </div>
                </div>
            </div>
        </a>

    </div>
    
        <div class="stats-container"> 

        <!-- Fixed: Removed action-card class wrapper, using stat-card-link instead -->
        <a href="{{ route('admin.sales.index') }}" class="stat-card-link">
            <div class="stat-card">  
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div class="stat-info">
                        <p class="stat-label">Montly Sales</p>
                        <h3 class="stat-value">{{$monthlySales}}</h3>
                    </div>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.customer.index') }}" class="stat-card-link"> 
            <div class="stat-card">  
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fa-solid fa-user"></i>    
                    </div>
                    <div class="stat-info">
                        <p class="stat-label">Client</p>
                        <h3 class="stat-value">{{$totalCustomer}}</h3>
                    </div>
                </div>
            </div>
        </a>

    </div>

    

 

    
    <div class="orders-header">
        <h2 class="section-title">Recent Orders</h2>
        <a href="{{ route('admin.order.index') }}" class="view-all-link">View All</a>
    </div>

    <div class="orders-container">
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

        <a href="{{ route('admin.order.show',$order->id) }}">
            <div class="order-card-item">
                
                <div class="order-info">
                    
                    <p class="order-number">Order: {{$order->id}}</p>
                    <div class="customer-info">
                        <div class="customer-avatar">{{ substr($order->customer->fname, 0, 1) }}</div>
                        <span class="customer-name">{{ $order->customer->fname . ' ' . $order->customer->lname }}</span>
                    </div>
                </div>
               
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
                
            </div>
        </a>
        @endforeach
    @endif  

    </div>

@endsection