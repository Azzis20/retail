<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;//Product
use App\Models\Product;
use App\Models\User;

use Carbon\Carbon;

class AdminDashboardController extends Controller
{
   
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
               ->take(4)                              // get only 4 records
               ->get();

        return view('admin.dashboard.dashboard',compact('orders','countOrder','monthlySales','totalCustomer','stock_alert_count'));
    }

    /**
     * Show the form for creating a new resource.
     */

     public function totalOrders()
    {
        
        return view('admin.order.order-index');//with status != Out-for-delivery
    }

     public function pending()
    {
        //
        return view('admin.order.order-index');//with status pending
    }

     public function addStaff()
    {
        //
        return view('admin.manage.staff-create');
    }
     public function addProduct()
    {
        //
        return view('admin.product.product-create');
    }




    public function showOrder(string $id)
    {
        //

        //$product = getfindiorfail(id)

        return view('admin.order.order-show'); //compact
    }


    
     public function update(Request $request, string $id)
    {
        //
    }


    public function store(Request $request)
    {
       
    }

   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
