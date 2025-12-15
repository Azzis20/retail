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
        
        <a href="{{ route('admin.order.index') }}" class="stat-card-link"> 
            <div class="stat-card">  
                <div class="stat-content">
                    <div class="stat-icon">
                        <i class="fa-solid fa-ellipsis"></i>
                    </div>
                    <div class="stat-info">
                        <p class="stat-label">Pending</p>
                        <h3 class="stat-value">{{$pendingCount}}</h3>
                    </div>
                </div>
            </div>
        </a>

    </div>

    <h2 class="section-title">Quick Actions</h2>

    <div class="actions-container">
        <a href="{{ route('admin.add.staff') }}" class="action-card">
            <i class="fa-solid fa-user-plus"></i>
            <span>Add Staff</span>
        </a>
        
        <a href="{{ route('admin.product.create') }}" class="action-card">
            <i class="fa-solid fa-circle-plus"></i>
            <span>Add Product</span>
        </a>
    </div>

    
    <div class="orders-header">
        <h2 class="section-title">Recent Orders</h2>
        <a href="{{ route('admin.order.index') }}" class="view-all-link">View All</a>
    </div>

    <div class="orders-container">

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
               
                <span class="status-badge status-pending">{{$order->status}}</span>
                
            </div>
        </a>
        @endforeach

    </div>

@endsection