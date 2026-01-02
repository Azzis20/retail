<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Bill;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VendorSalesController extends Controller
{
        
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $dateFilter = $request->get('date_filter', 'all_time');
        $customMonth = $request->get('custom_month');
        $customYear = $request->get('custom_year');
        
        // Calculate monthly sales (current month)
        $monthlySales = Payment::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount');
        
        // Calculate yearly sales (current year)
        $yearlySales = Payment::whereYear('created_at', Carbon::now()->year)
            ->sum('amount');
        
        // Get total payment count
        $totalPayments = Payment::count();
        
        // Generate month/year options for dropdowns
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        
        // Get years from first payment to current year
        $firstPaymentYear = Payment::min(DB::raw('YEAR(created_at)')) ?? Carbon::now()->year;
        $years = range(Carbon::now()->year, $firstPaymentYear);
        
        // For unpaid filter, we need to show bills instead of payments
        if ($filter === 'unpaid') {
            // Get unpaid bills with their orders and customers
            $billsQuery = Bill::with(['order.customer'])
                ->where('payment_status', Bill::STATUS_UNPAID)
                ->orderBy('created_at', 'desc');
            
            // Apply date filters to bills
            $billsQuery = $this->applyDateFilter($billsQuery, $dateFilter, $customMonth, $customYear);
            
            $bills = $billsQuery->paginate(6)->appends($request->except('page'));
            
            return view('vendor.sales.sales-index', compact(
                'bills',
                'monthlySales',
                'yearlySales',
                'totalPayments',
                'filter',
                'dateFilter',
                'months',
                'years',
                'customMonth',
                'customYear'
            ));
        }
        
        // Build payment query with filters for other cases
        $query = Payment::with(['customer', 'bill.order'])
            ->orderBy('created_at', 'desc');
        
        // Apply payment status filter
        if ($filter === 'paid') {
            $query->whereHas('bill', function($q) {
                $q->where('payment_status', Bill::STATUS_PAID);
            });
        }
        
        // Apply date filters
        $query = $this->applyDateFilter($query, $dateFilter, $customMonth, $customYear);
        
        // Paginate results and append query parameters
        $payments = $query->paginate(6)->appends($request->except('page'));
        
        return view('vendor.sales.sales-index', compact(
            'payments',
            'monthlySales',
            'yearlySales',
            'totalPayments',
            'filter',
            'dateFilter',
            'months',
            'years',
            'customMonth',
            'customYear'
        ));
    }
    
    /**
     * Apply date filter to query
     */
    private function applyDateFilter($query, $dateFilter, $customMonth = null, $customYear = null)
    {
        switch ($dateFilter) {
            case 'this_month':
                $query->whereYear('created_at', Carbon::now()->year)
                      ->whereMonth('created_at', Carbon::now()->month);
                break;
            case 'this_year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            case 'custom_month':
                if ($customMonth && $customYear) {
                    $query->whereYear('created_at', $customYear)
                          ->whereMonth('created_at', $customMonth);
                }
                break;
            case 'custom_year':
                if ($customYear) {
                    $query->whereYear('created_at', $customYear);
                }
                break;
            // 'all_time' - no additional filter
        }
        
        return $query;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::findOrFail($id);
       
        return view('admin.sales.sales-show', compact('order'));
    }
}
