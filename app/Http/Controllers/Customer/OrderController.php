<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch orders for the logged-in customer
    $query = Order::with(['orderItems', 'bill', 'customer', 'processedBy'])
    ->where('customer_id', auth()->id());
    
  
    
    $orders = $query->orderBy('created_at', 'desc')->paginate(10);
    
    return view('customer.order.order-index', compact('orders'));
    }


    public function show(string $id)
    {
         $order = Order::findOrFail($id);

   
         return view('customer.order.order-show', compact('order'));

        
    }

public function completed(Request $request, $id)
{
    // 1. Find the order by ID
    $order = Order::findOrFail($id);

    // 2. Check if the status is 'pending'
    if ($order->status === 'pending') {
        // 3. Return back with an error message
        return redirect()->back()->with('error', 'Cannot update order. Status is already pending.');
    }

    // 4. Update the status if not pending
    $order->update([
        'status'     => 'completed',
        'updated_at' => now(),
    ]);

    // 5. Redirect back with a success message
   return redirect()->route('customer.order.index')->with('success', 'Order has been completed');

}

    
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
