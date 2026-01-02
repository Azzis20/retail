<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Bill;
use Illuminate\Support\Facades\Auth;


class CustomerDashboardController extends Controller
{
    //

     public function index()
    {
        //
        // $totalBalance = Bill::where('created_at', '>=', now()->subMonth())->sum('balance');
        $totalBalance = Bill::whereHas('order', function($query) {
            $query->where('customer_id', auth()->id());
        })->sum('balance');
        
        $totalOrder = Order::where('customer_id', auth()->id())->count();

        $query = Order::with(['orderItems', 'bill', 'customer', 'processedBy'])
        ->where('customer_id', auth()->id());
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);


        return view('customer.dashboard.dashboard',compact('totalBalance','totalOrder','orders'));
    }



}
