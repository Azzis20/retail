
@extends('vendor.layouts.app')

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
        <form id="dateFilterForm" method="GET" action="{{ route('vendor.sales.index') }}" class="date-filter-form">
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
            <!-- Desktop Table View -->
            <div class="sales-table-wrapper">
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Order ID</th>
                            <th>Bill Total</th>
                            <th>Amount Due</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bills as $bill)
                            <tr class="sales-table-row">
                                <td data-label="Date">
                                    <div class="table-cell-content">
                                        <i class="fa-regular fa-clock"></i>
                                        {{ $bill->created_at->format('M d, Y') }}
                                        <span class="table-time">{{ $bill->created_at->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td data-label="Customer">
                                    <div class="table-customer">
                                        <div class="table-customer-avatar">
                                            {{ strtoupper(substr($bill->order->customer->fname, 0, 1)) }}{{ strtoupper(substr($bill->order->customer->lname, 0, 1)) }}
                                        </div>
                                        <div class="table-customer-info">
                                            <div class="table-customer-name">
                                                {{ $bill->order->customer->fname }} {{ $bill->order->customer->lname }}
                                            </div>
                                            <div class="table-customer-email">
                                                {{ $bill->order->customer->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Order ID">
                                    <span class="table-order-id">ORD{{ str_pad($bill->order->id, 2, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td data-label="Bill Total">
                                    <span class="table-amount">₱{{ number_format($bill->total_amount, 2) }}</span>
                                </td>
                                <td data-label="Amount Due">
                                    <span class="table-amount table-amount-due">₱{{ number_format($bill->balance, 2) }}</span>
                                </td>
                                <td data-label="Status">
                                    <span class="table-status-badge status-unpaid">Unpaid</span>
                                </td>
                                <td data-label="Actions">
                                    <div class="table-actions">
                                        <a href="{{ route('vendor.order.payment', $bill->order_id) }}" 
                                           class="btn-table-action btn-payment" 
                                           title="Record Payment">
                                            <i class="fa-solid fa-dollar-sign"></i>
                                            <span>Payment</span>
                                        </a>
                                        <a href="{{ route('vendor.order.show', $bill->order_id) }}" 
                                           class="btn-table-action btn-view" 
                                           title="View Order">
                                            <i class="fa-solid fa-eye"></i>
                                            <span>View</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
            <!-- Desktop Table View -->
            <div class="sales-table-wrapper">
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Order ID</th>
                            <th>Amount Paid</th>
                            <th>Bill Total</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr class="sales-table-row">
                                <td data-label="Date">
                                    <div class="table-cell-content">
                                        <i class="fa-regular fa-clock"></i>
                                        {{ $payment->created_at->format('M d, Y') }}
                                        <span class="table-time">{{ $payment->created_at->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td data-label="Customer">
                                    <div class="table-customer">
                                        <div class="table-customer-avatar">
                                            {{ strtoupper(substr($payment->customer->fname, 0, 1)) }}{{ strtoupper(substr($payment->customer->lname, 0, 1)) }}
                                        </div>
                                        <div class="table-customer-info">
                                            <div class="table-customer-name">
                                                {{ $payment->customer->fname }} {{ $payment->customer->lname }}
                                            </div>
                                            <div class="table-customer-email">
                                                {{ $payment->customer->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Order ID">
                                    <span class="table-order-id">ORD{{ str_pad($payment->bill->order->id, 2, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td data-label="Amount Paid">
                                    <span class="table-amount table-amount-paid">₱{{ number_format($payment->amount, 2) }}</span>
                                </td>
                                <td data-label="Bill Total">
                                    <span class="table-amount">₱{{ number_format($payment->bill->total_amount, 2) }}</span>
                                </td>
                                <td data-label="Balance">
                                    <span class="table-amount table-balance">₱{{ number_format($payment->bill->balance, 2) }}</span>
                                </td>
                                <td data-label="Status">
                                    <span class="table-status-badge status-{{ $payment->bill->payment_status }}">
                                        {{ ucwords(str_replace('_', ' ', $payment->bill->payment_status)) }}
                                    </span>
                                </td>
                                <td data-label="Actions">
                                    <div class="table-actions">
                                        <a href="{{ route('vendor.order.show', $payment->bill->order_id) }}" 
                                           class="btn-table-action btn-view" 
                                           title="View Order">
                                            <i class="fa-solid fa-eye"></i>
                                            <span>View</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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