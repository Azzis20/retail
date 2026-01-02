<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Payment;
use App\Services\NotificationService; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::latest()
               ->with(['customer', 'processedBy', 'bill']) 
               ->paginate(6);
   
        return view('admin.order.order-index', compact('orders'))->with('status','all');
    }

    public function selectCompleted()
    {
        $orders = Order::where('status', 'completed')
            ->paginate(5);

        return view('admin.order.order-index', compact('orders'))->with('status', 'completed');
    }

    public function selectOutForDelivery()
    {
        $orders = Order::where('status', 'Out-for-delivery')
            ->paginate(5);

        return view('admin.order.order-index', compact('orders'))->with('status', 'Out-for-delivery');
    }
    
    public function selectPending()
    {
        $orders = Order::where('status', 'pending')
            ->paginate(5);

        return view('admin.order.order-index', compact('orders'))->with('status', 'pending');
    }

    public function create()
    {
        return view('admin.manage.staff-create');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $orders = Order::query()
            ->with('customer')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('customer', function ($subQuery) use ($search) {
                    $subQuery->where('fname', 'LIKE', "%{$search}%")
                            ->orWhere('lname', 'LIKE', "%{$search}%");
                });
            })
            ->paginate(5)
            ->appends(['search' => $search]);

        return view('admin.order.order-index', compact('orders'))->with('status','search');
    }

    public function outForDelivery(Request $request, $id)
    {
        // Find the order by ID
        $order = Order::findOrFail($id);
        
        // Store old status for notification
        $oldStatus = $order->status;
        
        // Update order status
        $order->update([
            'status'       => 'out-for-delivery',
            'processed_by' => auth()->id(),
            'updated_at' => now(), 
        ]);

        // CREATE NOTIFICATION - Status changed
        NotificationService::createOrderStatusNotification($order, $oldStatus, 'out-for-delivery');

        // Redirect back with a success message
        return redirect()->route('admin.order.index')
            ->with('success', 'Order is now out for delivery!');
    }

    public function store(Request $request)
    {
        // If you implement order creation in admin panel, add notification here
        // NotificationService::createOrderNotification($order);
    }

    public function show(string $id)
    {
        // Fetch the order with customer and processor relationships
        $order = Order::with(['customer', 'processedBy'])->findOrFail($id);

        // Pass the order to the view
        return view('admin.order.order-show', compact('order'));
    }

    public function recordPayment(string $id)
    {
        $order = Order::with([
            'customer', 
            'processedBy', 
            'bill.payments.recordedBy'
        ])->findOrFail($id);

        return view('admin.order.order-payment', compact('order'));
    }

    public function paymentStore(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'bill_id' => 'required|exists:bills,id',
            'customer_id' => 'required|exists:users,id',
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Get the bill and verify balance
        $bill = \App\Models\Bill::findOrFail($validated['bill_id']);
        
        if ($validated['amount'] > $bill->balance) {
            return back()
                ->withErrors(['amount' => 'Payment amount cannot exceed the balance of ₱' . number_format($bill->balance, 2)])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create the payment
            $payment = Payment::create([
                'bill_id' => $validated['bill_id'],
                'customer_id' => $validated['customer_id'],
                'amount' => $validated['amount'],
                'recorded_by' => auth()->id(),
            ]);

            // CREATE NOTIFICATION - Payment recorded
            NotificationService::createPaymentNotification($payment);

            // Check if order should be marked as completed
            $order = Order::findOrFail($validated['order_id']);
            
            // Store old status before potential change
            $oldStatus = $order->status;
            
            // If bill is fully paid and order is out for delivery, mark as completed
            if ($bill->fresh()->payment_status === 'paid' && $order->status === 'out-for-delivery') {
                $order->update(['status' => 'completed']);
                
                // CREATE NOTIFICATION - Order completed
                NotificationService::createOrderStatusNotification($order, $oldStatus, 'completed');
            }

            DB::commit();

            return back()->with('success', 'Payment of ₱' . number_format($payment->amount, 2) . ' recorded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment recording failed: ' . $e->getMessage());
            
            return back()
                ->withErrors(['error' => 'Failed to record payment. Please try again.'])
                ->withInput();
        }
    }
}