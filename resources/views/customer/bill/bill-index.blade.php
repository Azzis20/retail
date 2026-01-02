@extends('customer.layouts.app')

@section('title', 'Bills')

@section('page-title', 'Bill and Payment')

@section('content')

<div class="bills-container">
    
    <!-- Bill Summary Section -->
    <div class="bill-summary-section">
        <h2 class="section-title">Bill Summary</h2>
        
        @if($bills->isEmpty())
            <div class="no-bills-card">
                <i>📋</i>
                <p>No bills found</p>
                <span class="no-bills-text">You don't have any bills yet</span>
            </div>
        @else
           
            <div class="bill-card">
                <div class="bill-card-header">
                    <div class="bill-status">
                        @if($totalBalance <= 0)
                            <span class="status-paid">Good</span>
                        @else
                            <span class="status-unpaid">There is Balance Must Be Settled</span>
                        @endif
                    </div>
                </div>

                <div class="bill-amounts" style="padding: 8px 16px;">
                    <div class="amount-row">
                        <span class="amount-label">Total Orders:</span>
                        <span class="amount-value">{{ $totalOrders }}</span>
                    </div>
                </div>

                <div class="bill-amounts" style="padding: 8px 16px;">
                    <div class="amount-row">
                        <span class="amount-label">Total Amount:</span>
                        <span class="amount-value">₱{{ number_format($totalAmount, 2) }}</span>
                    </div>
                </div>
                <div class="bill-amounts" style="padding: 8px 16px;">
                    <div class="amount-row">
                        <span class="amount-label">Total Paid:</span>
                        <span class="amount-value">₱{{ number_format($totalPaid, 2) }}</span>
                    </div>
                </div>
                <div class="bill-amounts"style="padding: 8px 16px;">
                    <div class="amount-row">
                        <span class="amount-label">Total Balance:</span>
                        <span class="amount-value">₱{{ number_format($totalBalance, 2) }}</span>
                    </div>
                </div>
                
            </div>
            
        @endif
    </div>

    <!-- Payment History Section -->
    <div class="payment-history-section">
        <h2 class="section-title">Payment History</h2>
        
    

        @if($allPayments->isEmpty())
            <div class="no-payments-card">
                <i>💳</i>
                <p>No payment history</p>
                <span class="no-payments-text">Your payments will appear here</span>
            </div>
        @else
            <div class="payments-list">
                @foreach($allPayments as $payment)
                <div class="payment-card">
                    <div class="payment-icon">
                        <i>✓</i>
                    </div>
                    <div class="payment-info">
                        <div class="payment-header">
                            <span class="payment-order">Order #{{ $payment->bill->order_id }}</span>
                            <span class="payment-amount">₱{{ number_format($payment->amount, 2) }}</span>
                        </div>
                        <div class="payment-date">
                            {{ $payment->created_at->format('M d, Y • h:i A') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection