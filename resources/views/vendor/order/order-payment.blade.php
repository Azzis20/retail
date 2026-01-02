@extends('vendor.layouts.app')

@section('title', 'Record Payment')

@section('page-title', 'Payment Management')

@section('content')

<div class="payment-record-container">
    
    <!-- Header with Back Button -->
    <div class="form-header">
        <a href="{{ route('vendor.order.show', $order->id) }}" class="back-button">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <h1>Record Payment</h1>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        
            
            <button class="alert-close" onclick="this.parentElement.remove()">×</button>
        
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>
                <strong>Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    <!-- Order Summary Card -->
    <section class="payment-section">
        <h2 class="section-heading">Order Summary</h2>
        
        <div class="payment-summary-card">
            <div class="summary-row">
                <span class="summary-label">Order ID:</span>
                <span class="summary-value">ORD{{ str_pad($order->id, 2, '0', STR_PAD_LEFT) }}</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">Customer:</span>
                <span class="summary-value">{{ $order->customer->fname . ' ' . $order->customer->lname }}</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">Order Date:</span>
                <span class="summary-value">{{ $order->created_at->format('d M Y') }}</span>
            </div>
            
            <div class="summary-row summary-highlight">
                <span class="summary-label">Total Amount:</span>
                <span class="summary-value summary-amount">₱{{ number_format($order->bill->total_amount, 2) }}</span>
            </div>
            
            <div class="summary-row summary-highlight">
                <span class="summary-label">Amount Paid:</span>
                <span class="summary-value summary-paid">₱{{ number_format($order->bill->payments->sum('amount'), 2) }}</span>
            </div>
            
            <div class="summary-row summary-highlight balance-row">
                <span class="summary-label">Balance Due:</span>
                <span class="summary-value summary-balance">₱{{ number_format($order->bill->balance, 2) }}</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">Payment Status:</span>
                <span class="payment-status-badge status-{{ $order->bill->payment_status }}">
                    {{ ucwords(str_replace('_', ' ', $order->bill->payment_status)) }}
                </span>
            </div>
        </div>
    </section>

    <!-- Payment Form -->
    @if($order->bill->payment_status !== 'paid')
    <section class="payment-section">
        <h2 class="section-heading">Record New Payment</h2>
        
        <form action="{{ route('vendor.payment.store') }}" method="POST" class="payment-form">
            @csrf
            
            <input type="hidden" name="bill_id" value="{{ $order->bill->id }}">
            <input type="hidden" name="customer_id" value="{{ $order->customer_id }}">
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            
            <!-- Amount Input -->
            <div class="form-group">
                <label for="amount" class="form-label">
                    Payment Amount <span class="required">*</span>
                </label>
                <div class="input-with-icon">
                    <span class="input-icon">₱</span>
                    <input 
                        type="number" 
                        id="amount" 
                        name="amount" 
                        class="form-input payment-amount-input @error('amount') input-error @enderror" 
                        placeholder="0.00"
                        step="0.01"
                        min="0.01"
                        max="{{ $order->bill->balance }}"
                        value="{{ old('amount') }}"
                        required
                    >
                </div>
                @error('amount')
                    <div class="error-message">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
                <div class="input-hint">
                    Maximum amount: ₱{{ number_format($order->bill->balance, 2) }}
                </div>
            </div>

            <!-- Quick Amount Buttons -->
            <div class="form-group">
                <label class="form-label">Quick Amount</label>
                <div class="quick-amount-buttons">
                    @php
                        $balance = $order->bill->balance;
                        $quickAmounts = [];
                        
                        if ($balance >= 100) {
                            $quickAmounts[] = 100;
                        }
                        if ($balance >= 500) {
                            $quickAmounts[] = 500;
                        }
                        if ($balance >= 1000) {
                            $quickAmounts[] = 1000;
                        }
                        $quickAmounts[] = $balance; // Full amount
                    @endphp
                    
                    @foreach($quickAmounts as $amount)
                        <button 
                            type="button" 
                            class="btn-quick-amount"
                            data-amount="{{ $amount }}"
                        >
                            @if($amount == $balance)
                                Full Amount
                            @else
                                ₱{{ number_format($amount) }}
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('vendor.order.show', $order->id) }}" class="btn-cancel">
                    <i class="fa-solid fa-times"></i>
                    Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-check"></i>
                    Record Payment
                </button>
            </div>
        </form>
    </section>
    @else
    <section class="payment-section">
        <div class="payment-complete-card">
            <div class="complete-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h3 class="complete-title">Payment Complete</h3>
            <p class="complete-message">This order has been fully paid. No additional payments needed.</p>
        </div>
    </section>
    @endif

    <!-- Payment History -->
    @if($order->bill->payments->count() > 0)
    <section class="payment-section">
        <h2 class="section-heading">Payment History</h2>
        
        <div class="payment-history-list">
            @foreach($order->bill->payments->sortByDesc('created_at') as $payment)
                <div class="payment-history-item">
                    <div class="payment-history-header">
                        <div class="payment-history-date">
                            <i class="fa-solid fa-calendar"></i>
                            <span>{{ $payment->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <div class="payment-history-amount">
                            ₱{{ number_format($payment->amount, 2) }}
                        </div>
                    </div>
                    
                    <div class="payment-history-details">
                        <div class="payment-detail-row">
                            <span class="detail-icon"><i class="fa-solid fa-user"></i></span>
                            <span class="detail-label">Recorded By:</span>
                            <span class="detail-value">{{ $payment->recordedBy->fname ?? 'System' }} {{ $payment->recordedBy->lname ?? '' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

</div>

<script>
    // Quick amount buttons
    document.querySelectorAll('.btn-quick-amount').forEach(button => {
        button.addEventListener('click', function() {
            const amount = this.dataset.amount;
            document.getElementById('amount').value = amount;
            
            // Remove active class from all buttons
            document.querySelectorAll('.btn-quick-amount').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Add active class to clicked button
            this.classList.add('active');
        });
    });

    // Auto-dismiss success messages
    document.querySelectorAll('.alert-success').forEach(alert => {
        setTimeout(() => {
            alert.style.animation = 'fadeOut 0.3s ease-out forwards';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Form validation
    document.querySelector('.payment-form')?.addEventListener('submit', function(e) {
        const amountInput = document.getElementById('amount');
        const maxAmount = parseFloat(amountInput.max);
        const enteredAmount = parseFloat(amountInput.value);

        if (enteredAmount > maxAmount) {
            e.preventDefault();
            alert(`Payment amount cannot exceed the balance of ₱${maxAmount.toFixed(2)}`);
            return false;
        }

        if (enteredAmount <= 0) {
            e.preventDefault();
            alert('Payment amount must be greater than zero');
            return false;
        }
    });
</script>

@endsection