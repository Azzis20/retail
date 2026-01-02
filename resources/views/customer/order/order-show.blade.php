@extends('customer.layouts.app')

@section('title', 'Order Details')

@section('page-title', 'Order Section')

@section('content')



<div class="order-processing-container">
    
    <!-- Header with Back Button -->
    <div class="form-header">
        <a href="{{ route('customer.order.index') }}" class="back-button">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <h1>Order Details</h1>
    </div>

  
    

    <!-- Order Info Card -->
    <div class="order-info-section">
        <div class="info-card">
            <div class="info-row">
                <span class="info-label">Order Number</span>
                <span class="info-value">#{{ $order->id }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Order Date</span>
                <span class="info-value">{{ $order->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                    <span class="order-status-badge 
                        @if($order->status == 'pending') pending-badge
                
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
            @if($order->processed_by)
                <div class="info-row">
                    <span class="info-label">Processed By</span>
                    <span class="info-value">{{ $order->processedBy->fname }} {{ $order->processedBy->lname }}</span>
                </div>
            @endif
        </div>
    </div>



    <!-- Order Items Section -->
    <div class="order-items-section" style="padding-top:30px;">
        
        <h2 class="section-heading">Order Items</h2>
        <div class="info-card">
            @foreach($order->orderItems as $item)
                <div class="order-item-row" style = "padding:16px 0px;">

                    <div class="info-row">
                        <span class="item-name" >{{ $item->product->product_name }} × {{ $item->quantity }}  </span>
                        <span class="item-meta" ></span>
                        <span class="item-total">
                            <h4>
                                ₱{{ number_format($item-> line_total, 2) }}
                            </h4>
                        </span>
                    </div>

                    
                    
                </div>
            @endforeach
        </div>
    </div>

    <!-- Bill Summary Section -->
    <div class="bill-section" style="padding-top:16px">
        <h2 class="section-heading">Bill Summary</h2>
        <div class="info-card">
           
            
            <div class="info-row total-row">
                <span class="info-label-bold">Total Amount</span>
                <span class="info-value-bold">
                    <h4>
                    ₱{{ number_format(($order->bill->total_amount ?? 0) + ($order->bill->adjusted_amount ?? 0), 2) }}
                    </h4>
                </span>
            </div>
           
        </div>
    </div>

    <!-- Delivery Address Section -->
    <div class="delivery-section" style="padding-top:16px">
        <h2 class="section-heading">Delivery Information</h2>
        <div class="info-card">
            <div class="info-row">
                <span class="info-label">Delivery Address</span>
                <span class="info-value">{{ $order->customer->address }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Contact</span>
                <span class="info-value">{{ $order->customer->contact }}</span>
            </div>
        </div>
    </div>

    <!-- Notes Section (if any) -->
    @if($order->notes)
    
        <div class="notes-section" style="padding-top:16px">
            <h2 class="section-heading">Order Notes</h2>
            <div class="info-card">
                <p class="notes-text">{{ $order->notes }}</p>
            </div>
            
        </div>
    
    @endif

    <div class="action-button-container">
        <form action="{{ route('customer.orders.completed', $order->id) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <button type="submit" 
                    class="btn-ready-pickup 
                    {{ $order->status === 'pending' || $order->status === 'completed' ? 'btn-disabled' : '' }}"
                    @if($order->status === 'pending' || $order->status === 'completed') disabled @endif>
                Order Received
            </button>
        </form>
    </div>

   

</div>

@endsection