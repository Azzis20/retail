@extends('customer.layouts.app')

@section('title', 'Cart')

@section('page-title', 'Cart')

@section('content')

<div class="product-header">
    <a href="{{ route('customer.product.index') }}" class="back-button">
        <i class="fa-solid fa-chevron-left"></i>
    </a>
    <h1>Shopping Cart</h1>
</div>

<!-- Success/Error Messages -->




@if($cartItems->isEmpty())
    <!-- Empty Cart State -->
    <div class="no-products-message">
        <i class="fa-solid fa-shopping-cart"></i>
        <p>Your cart is empty</p>
        <a href="{{ route('customer.product.index') }}" class="btn-clear-search">Browse Products</a>
    </div>
@else
    <!-- Cart Items -->
    <div class="cart-container">
        @foreach($cartItems as $item)
        <div class="cart-item-card" data-cart-id="{{ $item->id }}">
            <div class="cart-item-main">
                @if($item->product->picture)
                    <img 
                        src="{{ asset('storage/' . $item->product->picture) }}" 
                        alt="{{ $item->product->product_name }}" 
                        class="cart-item-image"
                    >
                @else
                    <div class="no-picture-container-small">
                        No Picture
                    </div>
                @endif

                <div class="cart-item-details">
                    <h3 class="cart-item-name">{{ $item->product->product_name }}</h3>
                    <p class="cart-item-price">₱{{ number_format($item->product->price, 2) }} / {{ $item->product->unit }}</p>
                    
                    <!-- Stock Info -->
                    <p class="stock-info">Available: {{ $item->product->available_stock }} {{ $item->product->unit }}</p>
                    
                    <!-- Quantity Controls -->
                    <div class="quantity-controls">
                        <button 
                            type="button" 
                            class="qty-btn qty-minus" 
                            data-cart-id="{{ $item->id }}"
                        >
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <input 
                            type="number" 
                            class="qty-input" 
                            value="{{ $item->quantity }}" 
                            min="1" 
                            max="{{ $item->product->available_stock }}"
                            data-cart-id="{{ $item->id }}"
                            data-max-stock="{{ $item->product->available_stock }}"
                        >
                        <button 
                            type="button" 
                            class="qty-btn qty-plus" 
                            data-cart-id="{{ $item->id }}"
                            data-max-stock="{{ $item->product->available_stock }}"
                            {{ $item->quantity >= $item->product->available_stock ? 'disabled' : '' }}
                        >
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="cart-item-total">
                    <span class="line-total" data-cart-id="{{ $item->id }}">
                        ₱{{ number_format($item->line_total, 2) }}
                    </span>
                </div>
            </div>

            <!-- Remove Button -->
            <form action="{{ route('customer.product.removeFromCart', $item->id) }}" method="POST" class="remove-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-remove-item">
                    <i class="fa-solid fa-trash"></i>
                    Remove
                </button>
            </form>
        </div>
        @endforeach
    </div>

    <!-- Cart Summary -->
    <div class="cart-summary">
        <div class="summary-card">
            <h3 class="summary-title">Order Summary</h3>
            
            <div class="summary-row">
                <span>Items ({{ $cartItems->count() }})</span>
                <span id="cart-total">₱{{ number_format($total, 2) }}</span>
            </div>

            <div class="summary-divider"></div>

            <div class="summary-row summary-total">
                <span>Total</span>
                <span id="cart-total-final">₱{{ number_format($total, 2) }}</span>
            </div>

            <!-- Notes (Optional) -->
            <form action="{{ route('customer.product.checkout') }}" method="POST" id="checkout-form">
                @csrf
                <div class="form-group">
                    <label for="notes" class="form-label">Order Notes (Optional)</label>
                    <textarea 
                        name="notes" 
                        id="notes" 
                        class="notes-textarea" 
                        placeholder="Any special instructions..."
                        rows="3"
                    ></textarea>
                </div>

                <button type="submit" class="btn-checkout">
                    <i class="fa-solid fa-check"></i>
                    Checkout
                </button>
            </form>
        </div>
    </div>
@endif



<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart script loaded');

    // Quantity minus buttons
    document.querySelectorAll('.qty-minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartId = this.dataset.cartId;
            const input = document.querySelector(`.qty-input[data-cart-id="${cartId}"]`);
            const currentValue = parseInt(input.value);
            
            if (currentValue > 1) {
                input.value = currentValue - 1;
                updateCartItem(cartId, input.value);
                
                // Re-enable plus button if it was disabled
                const plusBtn = document.querySelector(`.qty-plus[data-cart-id="${cartId}"]`);
                plusBtn.disabled = false;
            }
        });
    });

    // Quantity plus buttons
    document.querySelectorAll('.qty-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartId = this.dataset.cartId;
            const maxStock = parseInt(this.dataset.maxStock);
            const input = document.querySelector(`.qty-input[data-cart-id="${cartId}"]`);
            const currentValue = parseInt(input.value);
            
            if (currentValue < maxStock) {
                input.value = currentValue + 1;
                updateCartItem(cartId, input.value);
                
                // Disable plus button if we've reached max stock
                if (parseInt(input.value) >= maxStock) {
                    this.disabled = true;
                }
            } else {
                showStockAlert(`Maximum stock reached (${maxStock} available)`);
            }
        });
    });

    // Direct input change
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            const cartId = this.dataset.cartId;
            const maxStock = parseInt(this.dataset.maxStock);
            let value = parseInt(this.value);
            
            if (value < 1) value = 1;
            if (value > maxStock) {
                value = maxStock;
                showStockAlert(`Only ${maxStock} items available in stock`);
            }
            
            this.value = value;
            updateCartItem(cartId, value);
            
            // Update plus button state
            const plusBtn = document.querySelector(`.qty-plus[data-cart-id="${cartId}"]`);
            plusBtn.disabled = (value >= maxStock);
        });
    });

    // Update cart item via AJAX
    function updateCartItem(cartId, quantity) {
        console.log('Updating cart item:', cartId, 'quantity:', quantity);
        
        const updateUrl = "{{ route('customer.product.updateCart', ':cartId') }}".replace(':cartId', cartId);
        console.log('Update URL:', updateUrl);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found!');
            showStockAlert('CSRF token missing. Please refresh the page.');
            return;
        }
        
        fetch(updateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json().then(data => {
                if (!response.ok) {
                    throw new Error(data.message || `HTTP error! status: ${response.status}`);
                }
                return data;
            });
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Update line total for the updated cart item
                const lineTotalElement = document.querySelector(`.line-total[data-cart-id="${cartId}"]`);
                if (lineTotalElement) {
                    lineTotalElement.textContent = `₱${data.lineTotal}`;
                }

                // Update total cart price
                const cartTotalElement = document.getElementById('cart-total');
                const cartTotalFinalElement = document.getElementById('cart-total-final');
                
                if (cartTotalElement) {
                    cartTotalElement.textContent = `₱${data.cartTotal}`;
                }
                if (cartTotalFinalElement) {
                    cartTotalFinalElement.textContent = `₱${data.cartTotal}`;
                }
                
                console.log('Cart updated successfully');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showStockAlert(error.message);
            
            // Revert input value on error
            const input = document.querySelector(`.qty-input[data-cart-id="${cartId}"]`);
            if (input) {
                location.reload(); // Reload to get correct values
            }
        });
    }

    // Show stock alert
    function showStockAlert(message) {
        // Remove existing alerts
        const existingAlert = document.querySelector('.alert-error.stock-alert');
        if (existingAlert) {
            existingAlert.remove();
        }

        // Create new alert
        const alert = document.createElement('div');
        alert.className = 'alert alert-error stock-alert';
        alert.innerHTML = `
            <i class="fa-solid fa-exclamation-circle"></i>
            ${message}
        `;
        
        const header = document.querySelector('.product-header');
        header.insertAdjacentElement('afterend', alert);
        
        // Auto-hide after 3 seconds
        setTimeout(() => {
            alert.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => alert.remove(), 300);
        }, 3000);
    }

    // Auto-hide messages
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert:not(.stock-alert)');
        alerts.forEach(alert => {
            alert.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => alert.remove(), 300);
        });
    }, 3000);
});
</script>

@endsection