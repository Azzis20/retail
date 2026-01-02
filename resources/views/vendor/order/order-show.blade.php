@extends('admin.layouts.app')

@section('title', 'Order Processing')

@section('page-title', 'Order Management')

@section('content')

<div class="order-processing-container">
    
    <!-- Header with Back Button -->
    <div class="form-header">
        <a href="{{ route('admin.order.index') }}" class="back-button">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <h1>Order Details</h1>
    </div>

    <!-- Order Details Section -->
    <section class="order-section">
        <h2 class="section-heading">Order Details</h2>
        
        <div class="info-card">
            <div class="info-row">
                <span class="info-label">Customer Name:</span>
                <span class="info-value">{{ $order->customer->fname . ' ' . $order->customer->lname }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Order ID:</span>
                <span class="info-value">ORD{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Order Date:</span>
                <span class="info-value">{{ $order->created_at->format('d M Y H:i') }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Address:</span>
                <span class="info-value">{{ $order->customer->address ?? 'N/A'}}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Status:</span>
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
    </section>

    <!-- Invoice Bill Section -->
    <section class="order-section">
        <h2 class="section-heading">Invoice Bill</h2>
        
        <div class="info-card items-card">
            <div class="info-row">
                <span class="info-label">Invoice ID:</span>
                <span class="info-value">INV{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Total:</span>
                <span class="info-value">₱{{ number_format($order->bill->total_amount ?? 0, 2) }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Paid Amount:</span>
                <span class="info-value">₱{{ number_format($order->bill?->payments->sum('amount') ?? 0, 2) }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Balance:</span>
                <span class="info-value" style="color: {{ $order->bill->balance > 0 ? '#e74c3c' : '#00c896' }}; font-weight: 700;">
                    ₱{{ number_format($order->bill->balance ?? 0, 2) }}
                </span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Payment Status:</span>
                <span class="order-status-badge 
                    @if($order->bill->payment_status == 'paid') completed-badge
                    @elseif($order->bill->payment_status == 'unpaid') pending-badge
                    @elseif($order->bill->payment_status == 'partially_paid') delivery-badge
                    @endif">
                    {{ ucwords(str_replace('_', ' ', $order->bill->payment_status)) }}
                </span>
            </div>
        </div>
    </section>

    <!-- Items Section -->
    <section class="order-section">
        <h2 class="section-heading">Items</h2>
        
        <div class="info-card items-card">
            @foreach($order->orderItems as $item)
                <div class="item-row">
                    <span class="item-name">{{ $item->product->product_name ?? 'N/A'}}</span>
                    <span class="item-quantity">{{ $item->quantity . ' ' . $item->product->unit ?? 'N/A'}}</span>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Notes Section -->
    <section class="order-section">
        <h2 class="section-heading">Notes</h2>
        
        <div class="notes-container">
            <textarea 
                class="notes-textarea" 
                placeholder="Add notes about this order..."
                rows="5"
                readonly
            >{{ $order->notes ?? 'N/A' }}</textarea>
        </div>
    </section>

    <!-- Action Buttons -->
    <div class="action-button-container">
        <form action="{{ route('admin.orders.deliveried', $order->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <button type="submit" 
                    class="btn-ready-pickup 
                    {{ in_array($order->status, ['completed', 'out-for-delivery']) ? 'btn-disabled' : '' }}"
                    @if(in_array($order->status, ['completed', 'out-for-delivery'])) disabled @endif>
                <i class="fa-solid fa-truck"></i>
                Mark as Out for Delivery
            </button>
        </form>
    </div>

    <!-- Record Payment Button -->
    <div class="action-button-container">
        <a href="{{ route('admin.order.payment', $order->id) }}" 
           class="btn-record-payment {{ $order->bill->payment_status === 'paid' ? 'btn-disabled' : '' }}"
           @if($order->bill->payment_status === 'paid') onclick="return false;" @endif>
            <i class="fa-solid fa-wallet"></i>
            Record Payment
        </a>
    </div>

</div>

<script>
    // Optional: Auto-save notes functionality
    const notesTextarea = document.querySelector('.notes-textarea');
    let saveTimeout;

    if (notesTextarea && !notesTextarea.hasAttribute('readonly')) {
        notesTextarea.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            
            saveTimeout = setTimeout(() => {
                // Auto-save notes after 1 second of no typing
                console.log('Auto-saving notes:', this.value);
                // You can add AJAX call here to save notes
            }, 1000);
        });
    }

    // Button click handler
    document.querySelector('.btn-ready-pickup')?.addEventListener('click', function(e) {
        if (!this.closest('form')) {
            e.preventDefault();
            
            // Confirm action
            if (confirm('Mark this order as out for delivery?')) {
                console.log('Order marked as out for delivery');
            }
        }
    });

    // Record payment button handler
    document.querySelector('.btn-record-payment')?.addEventListener('click', function(e) {
        if (this.classList.contains('btn-disabled')) {
            e.preventDefault();
            alert('This order has been fully paid. No additional payments needed.');
        }
    });
</script>

@endsection