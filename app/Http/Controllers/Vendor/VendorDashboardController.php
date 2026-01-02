<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;//Product
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class VendorDashboardController extends Controller
{
    //

     public function index()
    {
        //
       $monthlySales = Payment::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                       ->sum('amount');
       $totalCustomer = User::where('role', 'customer')->distinct()->count('id');

        $stock_alert_count = Product::whereColumn('available_stock', '<=', 'min_threshold')->count();
        //       'min_threshold',
        // 'available_stock'

        $countOrder = Order::where('status', '!=', 'completed')
            ->whereDate('created_at', Carbon::today()) // orders created today
            ->count();
        //  $pendingCount = Order::where('status', 'pending')->count();


        $orders = Order::where('status', '!=', 'completed')  // status not completed
               ->latest()                             // order by created_at descending
               ->take(2)                              // get only 2 records
               ->get();

        return view('vendor.dashboard.dashboard',compact('orders','countOrder','monthlySales','totalCustomer','stock_alert_count'));
    }

    
}
