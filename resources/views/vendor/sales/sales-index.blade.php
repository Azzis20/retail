@extends('admin.layouts.app')

@section('title', 'Sales & Revenue')

@section('page-title', 'Sales & Revenue')

@section('content')

<div class="order-history-container">
    <!-- Page Title -->
    <h1 class="order-history-title">Sales & Revenue</h1>

    <!-- Revenue Stats Cards -->
    <div class="stats-container">
        <!-- Total Sales This Month -->
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fa-solid fa-calendar-days" style="color:#fefefe"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Monthly</p>
                    <h2 class="stat-value">₱{{ number_format($monthlySales, 2) }}</h2>
                </div>
            </div>
        </div>

        <!-- Total Sales This Year -->
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fa-solid fa-chart-column" style="color:#fefefe;"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Yearly</p>
                    <h2 class="stat-value">₱{{ number_format($yearlySales, 2) }}</h2>
                </div>
            </div>
        </div>

        <!-- Total Payments -->
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="fa-solid fa-sack-dollar" style="color:#fefefe;"></i>
                </div>
                <div class="stat-info">
                    <p class="stat-label">Total Payments</p>
                    <h2 class="stat-value">{{ $totalPayments }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="sales-filter-section">
        <!-- Date Filter Form -->
        <form id="dateFilterForm" method="GET" action="{{ route('admin.sales.index') }}" class="date-filter-form">
            <!-- Hidden filter input to maintain payment status filter -->
            <input type="hidden" name="filter" value="{{ $filter }}">
            
            <div class="filter-row">
                <!-- Date Filter Type -->
                <div class="filter-group">
                    <label for="date_filter" class="filter-label">Date Range</label>
                    <select name="date_filter" id="date_filter" class="filter-select" onchange="handleDateFilterChange()">
                        <option value="all_time" {{ $dateFilter === 'all_time' ? 'selected' : '' }}>All Time</option>
                        <option value="this_month" {{ $dateFilter === 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="this_year" {{ $dateFilter === 'this_year' ? 'selected' : '' }}>This Year</option>
                        <option value="custom_month" {{ $dateFilter === 'custom_month' ? 'selected' : '' }}>Custom Month</option>
                        <option value="custom_year" {{ $dateFilter === 'custom_year' ? 'selected' : '' }}>Custom Year</option>
                    </select>
                </div>

                <!-- Custom Month Selector (Hidden by default) -->
                <div class="filter-group" id="monthSelector" style="display: {{ $dateFilter === 'custom_month' ? 'block' : 'none' }};">
                    <label for="custom_month" class="filter-label">Month</label>
                    <select name="custom_month" id="custom_month" class="filter-select">
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}" {{ $customMonth == $num ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Custom Year Selector (Hidden by default) -->
                <div class="filter-group" id="yearSelector" style="display: {{ in_array($dateFilter, ['custom_month', 'custom_year']) ? 'block' : 'none' }};">
                    <label for="custom_year" class="filter-label">Year</label>
                    <select name="custom_year" id="custom_year" class="filter-select">
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $customYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Apply Button -->
                <div class="filter-group">
                    <label class="filter-label" style="opacity: 0;">Apply</label>
                    <button type="submit" class="btn-apply-filter">
                        <i class="fa-solid fa-filter"></i>
                        Apply Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Payment Status Filter Tabs -->
        <div class="order-filter-tabs">
            <button class="order-tab {{ $filter === 'all' ? 'active' : '' }}" 
                    onclick="updateFilter('all')">
                All
            </button>
            <button class="order-tab {{ $filter === 'paid' ? 'active' : '' }}" 
                    onclick="updateFilter('paid')">
                Paid
            </button>
            <button class="order-tab {{ $filter === 'unpaid' ? 'active' : '' }}" 
                    onclick="updateFilter('unpaid')">
                Unpaid
            </button>
        </div>
    </div>

    <!-- Section Title -->
    <div class="section-title">
        @if($filter === 'unpaid')
            Unpaid Bills
        @else
            Payment History
        @endif
    </div>

    @if($filter === 'unpaid')
        <!-- UNPAID BILLS VIEW -->
        @if($bills->isEmpty())
            <div class="no-products-message">
                <i class="fa-solid fa-circle-check" style="color:#00C896;"></i>
                <p>All bills have been paid!</p>
            </div>
        @else
            <div class="orders-list">
                @foreach($bills as $bill)
                    <div class="payment-card">
                        <!-- Bill Header -->
                        <div class="payment-header">
                            <div class="payment-date">
                                <i>🕒</i>
                                {{ $bill->created_at->format('M d, Y h:i A') }}
                            </div>
                            <div class="payment-amount-badge" style="background: #e74c3c;">
                                ₱{{ number_format($bill->balance, 2) }}
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="payment-customer">
                            <div class="customer-avatar">
                                {{ strtoupper(substr($bill->order->customer->fname, 0, 1)) }}{{ strtoupper(substr($bill->order->customer->lname, 0, 1)) }}
                            </div>
                            <div class="payment-customer-info">
                                <h3 class="payment-customer-name">
                                    {{ $bill->order->customer->fname }} {{ $bill->order->customer->lname }}
                                </h3>
                                <p class="payment-customer-email">{{ $bill->order->customer->email }}</p>
                            </div>
                        </div>

                        <!-- Bill Details -->
                        <div class="payment-bill-details">
                            <div class="payment-detail-row">
                                <span class="payment-detail-label">Order ID:</span>
                                <span class="payment-detail-value">ORD{{ str_pad($bill->order->id, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="payment-detail-row">
                                <span class="payment-detail-label">Bill Total:</span>
                                <span class="payment-detail-value">₱{{ number_format($bill->total_amount, 2) }}</span>
                            </div>
                            <div class="payment-detail-row">
                                <span class="payment-detail-label">Amount Due:</span>
                                <span class="payment-detail-value" style="color: #e74c3c; font-weight: 700;">
                                    ₱{{ number_format($bill->balance, 2) }}
                                </span>
                            </div>
                            <div class="payment-detail-row">
                                <span class="payment-detail-label">Status:</span>
                                <span class="payment-status-badge status-unpaid">
                                    Unpaid
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div style="display: flex; gap: 8px; margin-top: 4px;">
                            <a href="{{ route('admin.order.payment', $bill->order_id) }}" 
                               class="btn-view-order" 
                               style="flex: 1; background: linear-gradient(135deg, rgb(0, 200, 150) 0%, rgb(0, 180, 130) 100%);">
                                <i>💰</i>
                                Record Payment
                            </a>
                            <a href="{{ route('admin.order.show', $bill->order_id) }}" 
                               class="btn-view-order" 
                               style="flex: 1;">
                                <i>👁️</i>
                                View Order
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($bills->hasPages())
                <div class="pagination-wrapper">
                    {{ $bills->links() }}
                </div>
            @endif
        @endif
    @else
        <!-- PAYMENT HISTORY VIEW -->
        @if($payments->isEmpty())
            <div class="no-products-message">
                <i class="fa-solid fa-credit-card" style="color:grey;"></i>
                <p>No payment records found</p>
            </div>
        @else
            <div class="orders-list">
                @foreach($payments as $payment)
                    <div class="payment-card">
                        <!-- Payment Header -->
                        <div class="payment-header">
                            <div class="payment-date">
                                <i>🕒</i>
                                {{ $payment->created_at->format('M d, Y h:i A') }}
                            </div>
                            <div class="payment-amount-badge">
                                ₱{{ number_format($payment->amount, 2) }}
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="payment-customer">
                            <div class="customer-avatar">
                                {{ strtoupper(substr($payment->customer->fname, 0, 1)) }}{{ strtoupper(substr($payment->customer->lname, 0, 1)) }}
                            </div>
                            <div class="payment-customer-info">
                                <h3 class="payment-customer-name">
                                    {{ $payment->customer->fname }} {{ $payment->customer->lname }}
                                </h3>
                                <p class="payment-customer-email">{{ $payment->customer->email }}</p>
                            </div>
                        </div>

                        <!-- Bill Details -->
                        <div class="payment-bill-details">
                            <div class="payment-detail-row">
                                <span class="payment-detail-label">Order ID:</span>
                                <span class="payment-detail-value">ORD{{ str_pad($payment->bill->order->id, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="payment-detail-row">
                                <span class="payment-detail-label">Bill Total:</span>
                                <span class="payment-detail-value">₱{{ number_format($payment->bill->total_amount, 2) }}</span>
                            </div>
                            <div class="payment-detail-row">
                                <span class="payment-detail-label">Remaining Balance:</span>
                                <span class="payment-detail-value payment-balance">
                                    ₱{{ number_format($payment->bill->balance, 2) }}
                                </span>
                            </div>
                            <div class="payment-detail-row">
                                <span class="payment-detail-label">Status:</span>
                                <span class="payment-status-badge status-{{ $payment->bill->payment_status }}">
                                    {{ ucwords(str_replace('_', ' ', $payment->bill->payment_status)) }}
                                </span>
                            </div>
                        </div>

                        <!-- View Order Button -->
                        <a href="{{ route('admin.order.show', $payment->bill->order_id) }}" class="btn-view-order">
                            <i>👁️</i>
                            View Order Details
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($payments->hasPages())
                <div class="pagination-wrapper">
                    {{ $payments->links() }}
                </div>
            @endif
        @endif
    @endif
</div>

@endsection

<script>
    // Handle date filter dropdown changes
    function handleDateFilterChange() {
        const dateFilter = document.getElementById('date_filter').value;
        const monthSelector = document.getElementById('monthSelector');
        const yearSelector = document.getElementById('yearSelector');
        
        // Show/hide selectors based on selected filter
        if (dateFilter === 'custom_month') {
            monthSelector.style.display = 'block';
            yearSelector.style.display = 'block';
        } else if (dateFilter === 'custom_year') {
            monthSelector.style.display = 'none';
            yearSelector.style.display = 'block';
        } else {
            monthSelector.style.display = 'none';
            yearSelector.style.display = 'none';
        }
    }

    // Update payment status filter while maintaining date filter
    function updateFilter(filterValue) {
        const form = document.getElementById('dateFilterForm');
        const filterInput = form.querySelector('input[name="filter"]');
        filterInput.value = filterValue;
        form.submit();
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        handleDateFilterChange();
    });
</script>