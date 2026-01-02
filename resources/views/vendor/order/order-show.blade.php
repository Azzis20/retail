@extends('vendor.layouts.app')

@section('title', 'Order Processing')

@section('page-title', 'Order Management')

@section('content')

<div class="order-processing-container">
    
    <!-- Header with Back Button -->
    <div class="form-header">
        <a href="{{ route('vendor.order.index') }}" class="back-button">
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
        <form action="{{ route('vendor.orders.deliveried', $order->id) }}" method="POST" id="deliveryForm">
            @csrf
            @method('PATCH')

            <button type="button" 
                    class="btn-ready-pickup {{ in_array($order->status, ['completed', 'out-for-delivery']) ? 'btn-disabled' : '' }}"
                    id="deliveryBtn"
                    @if(!in_array($order->status, ['completed', 'out-for-delivery'])) onclick="showDeliveryConfirmation()" @endif
                    @if(in_array($order->status, ['completed', 'out-for-delivery'])) disabled @endif>
                <i class="fa-solid fa-truck"></i>
                Mark as Out for Delivery
            </button>
        </form>
    </div>

    <!-- Record Payment Button -->
    <div class="action-button-container">
        <a href="{{ route('vendor.order.payment', $order->id) }}" 
           class="btn-record-payment {{ $order->bill->payment_status === 'paid' ? 'btn-disabled' : '' }}"
           id="paymentBtn">
            <i class="fa-solid fa-wallet"></i>
            Record Payment
        </a>
    </div>

</div>

<!-- Delivery Confirmation Modal -->
<div class="delivery-modal-overlay" id="deliveryModal">
    <div class="delivery-modal-content">
        <div class="delivery-modal-icon">
            <i class="fa-solid fa-truck-fast"></i>
        </div>
        
        <h3 class="delivery-modal-title">Mark Order for Delivery?</h3>
        
        <p class="delivery-modal-message">
            This order will be marked as "Out for Delivery". The customer will be notified that their order is on the way.
        </p>
        
        <div class="delivery-modal-actions">
            <button type="button" class="btn-delivery-cancel" onclick="hideDeliveryConfirmation()">
                <i class="fa-solid fa-times"></i>
                Cancel
            </button>
            <button type="button" class="btn-delivery-confirm" onclick="confirmDelivery()">
                <i class="fa-solid fa-check"></i>
                Confirm
            </button>
        </div>
    </div>
</div>

<script>
    // Show delivery confirmation modal
    function showDeliveryConfirmation() {
        const modal = document.getElementById('deliveryModal');
        if (modal) {
            modal.classList.add('active');
            console.log('Modal shown'); // Debug log
        } else {
            console.error('Modal not found'); // Debug log
        }
    }

    // Hide delivery confirmation modal
    function hideDeliveryConfirmation() {
        const modal = document.getElementById('deliveryModal');
        if (modal) {
            modal.classList.remove('active');
            console.log('Modal hidden'); // Debug log
        }
    }

    // Confirm and submit delivery form
    function confirmDelivery() {
        const form = document.getElementById('deliveryForm');
        if (form) {
            console.log('Submitting form'); // Debug log
            form.submit();
        } else {
            console.error('Form not found'); // Debug log
        }
    }

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        
        // Close modal when clicking outside
        const modal = document.getElementById('deliveryModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    hideDeliveryConfirmation();
                }
            });
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideDeliveryConfirmation();
            }
        });

        // Record payment button handler
        const paymentBtn = document.getElementById('paymentBtn');
        if (paymentBtn && paymentBtn.classList.contains('btn-disabled')) {
            paymentBtn.addEventListener('click', function(e) {
                e.preventDefault();
                alert('This order has been fully paid. No additional payments needed.');
            });
        }

        // Optional: Auto-save notes functionality
        const notesTextarea = document.querySelector('.notes-textarea');
        if (notesTextarea && !notesTextarea.hasAttribute('readonly')) {
            let saveTimeout;
            notesTextarea.addEventListener('input', function() {
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(() => {
                    console.log('Auto-saving notes:', this.value);
                    // You can add AJAX call here to save notes
                }, 1000);
            });
        }

    });
</script>

@endsection