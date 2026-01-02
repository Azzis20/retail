<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;



class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
       $totalOrders = Order::where('customer_id', auth()->id())->count();

        $totalBalance = Bill::whereHas('order', function($query) {
            $query->where('customer_id', auth()->id());
        })->sum('balance');

        $totalAmount = Bill::whereHas('order', function($query) {
            $query->where('customer_id', auth()->id());
        })->sum('total_amount');

        $totalPaid = Payment::whereHas('bill.order', function($query) {
            $query->where('customer_id', auth()->id());
        })->sum('amount');

         $bills = Bill::whereHas('order', function($query) {
            $query->where('customer_id', Auth::id());
        })
        ->with(['order', 'payments' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(5);

        $allPayments = $bills->flatMap(function($bill) {
        return $bill->payments->map(function($payment) use ($bill) {
            $payment->bill = $bill;
            return $payment;
        });
    })->sortByDesc('created_at');
        $perPage = 5;
        $page = request()->get('page', 1);

        // Slice the collection
        $paginatedPayments = new LengthAwarePaginator(
            $allPayments->forPage($page, $perPage),
            $allPayments->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );




        return view('customer.bill.bill-index',compact('bills','totalBalance','totalAmount','totalOrders','totalPaid','allPayments','paginatedPayments'));

    }



    /**
     * Show specific bill details
     */
    public function show($id)
    {
        $bill = Bill::whereHas('order', function($query) {
            $query->where('customer_id', Auth::id());
        })
        ->with(['order', 'payments'])
        ->findOrFail($id);

        return view('customer.bill-details', compact('bill'));
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
