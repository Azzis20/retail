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
        <h1>Order Processing</h1>
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
                <span class="info-value">{{ $order->id }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Order Date:</span>
                <span class="info-value">{{ $order->created_at->format('d M Y H:i') }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Address:</span>
                <span class="info-value">{{ $order->customer->address ?? N/A}}</span>
            </div>
        </div>
    </section>

    <!-- Items Section -->
    <section class="order-section">
        <h2 class="section-heading">Items</h2>
        
        <div class="info-card items-card">


          
            @foreach($order->items as $item)
                <div class="item-row">
                    <span class="item-name">{{ $item->product->product_name ?? N/A}}</span>
                    <span class="item-quantity">{{ $item->quantity . ' ' . $item->product->unit ?? N/A}}</span>
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
            > {{$order->notes ?? N/A }}</textarea>
        </div>
    </section>

    <!-- Action Button -->
    <div class="action-button-container">
        <button class="btn-ready-pickup">
            Mark as Ready for Pick up
        </button>
    </div>

</div>


<script>
    // Optional: Auto-save notes functionality
    const notesTextarea = document.querySelector('.notes-textarea');
    let saveTimeout;

    if (notesTextarea) {
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
            if (confirm('Mark this order as ready for pick up?')) {
                // Add your logic here
                console.log('Order marked as ready for pick up');
                // You can add AJAX call or form submission here
            }
        }
    });
</script>

@endsection