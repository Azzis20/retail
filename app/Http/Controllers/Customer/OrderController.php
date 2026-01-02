<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\NotificationService; // ADD THIS
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
        // Find the order by ID
        $order = Order::findOrFail($id);

        // Check if the status is 'pending'
        if ($order->status === 'pending') {
            return redirect()->back()
                ->with('error', 'Cannot update order. Status is already pending.');
        }

        // Store old status before update
        $oldStatus = $order->status;

        // Update the status if not pending
        $order->update([
            'status'     => 'completed',
            'updated_at' => now(),
        ]);

        // CREATE NOTIFICATION - Customer manually completed order
        // (This notifies admin that customer confirmed completion)
        NotificationService::createOrderStatusNotification($order, $oldStatus, 'completed');
        

  

        // Redirect back with a success message
        return redirect()->route('customer.order.index')
            ->with('success', 'Order has been completed');
    }

    /**
     * Customer cancels their order
     */
    public function cancel($id)
    {
        // Find the order
        $order = Order::where('customer_id', auth()->id())
                      ->where('id', $id)
                      ->firstOrFail();

        // Only allow cancellation if order is pending
        if ($order->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Cannot cancel order. Order is already being processed.');
        }

        // Store old status
        $oldStatus = $order->status;

        // Update to cancelled
        $order->update([
            'status' => 'cancelled',
            'updated_at' => now(),
        ]);

        // CREATE NOTIFICATION - Customer cancelled order
        NotificationService::createOrderStatusNotification($order, $oldStatus, 'cancelled');

        // Optional: Restore product stock
        foreach ($order->orderItems as $item) {
            $product = $item->product;
            $oldStock = $product->available_stock;
            
            $product->increment('available_stock', $item->quantity);
            
            // CREATE NOTIFICATION - Stock restored
            NotificationService::createInventoryNotification(
                $product->fresh(),
                $oldStock,
                $product->fresh()->available_stock
            );
        }

        return redirect()->route('customer.order.index')
            ->with('success', 'Order cancelled successfully');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}