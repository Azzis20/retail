<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Bill; 
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->paginate(8);
        return view('customer.product.product-index', compact('products'))->with('category', 'all');
    }

    /**
     * Display cart page
     */
    public function cart()
    {
        $cartItems = Cart::with('product')
            ->where('customer_id', Auth::id())
            ->get();
        
        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });

        return view('customer.product.product-cart', compact('cartItems', 'total'));
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        // Check if product is out of stock
        if ($product->available_stock <= 0) {
            return redirect()->back()->with('error', 'Sorry, this product is out of stock!');
        }

        // Check if item already exists in cart
        $existingCartItem = Cart::where('customer_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($existingCartItem) {
            // Check if adding 1 more would exceed available stock
            if (($existingCartItem->quantity + 1) > $product->available_stock) {
                return redirect()->back()->with('error', 'Cannot add more. Only ' . $product->available_stock . ' items available in stock!');
            }

            $existingCartItem->quantity += 1;
            $existingCartItem->save();
        } else {
            // Create new cart item
            Cart::create([
                'customer_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request, $cartId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:999'
        ]);

        $cartItem = Cart::where('id', $cartId)
            ->where('customer_id', Auth::id())
            ->with('product')
            ->firstOrFail();
        
        // Check if requested quantity exceeds available stock
        if ($request->quantity > $cartItem->product->available_stock) {
            return response()->json([
                'success' => false,
                'message' => 'Only ' . $cartItem->product->available_stock . ' items available in stock!'
            ], 400);
        }

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        // Refresh the relationship
        $cartItem->load('product');

        return response()->json([
            'success' => true,
            'lineTotal' => number_format($cartItem->line_total, 2),
            'cartTotal' => number_format($this->getCartTotal(), 2)
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($cartId)
    {
        Cart::where('id', $cartId)
            ->where('customer_id', Auth::id())
            ->delete();

        return redirect()->back()->with('success', 'Item removed from cart');
    }

   
    public function checkout(Request $request)
{
    $cartItems = Cart::with('product')
        ->where('customer_id', Auth::id())
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('customer.product.cart')
            ->with('error', 'Your cart is empty');
    }

    // Validate stock availability before checkout
    foreach ($cartItems as $cartItem) {
        if ($cartItem->quantity > $cartItem->product->available_stock) {
            return redirect()->back()
                ->with('error', $cartItem->product->product_name . ' has insufficient stock. Only ' . $cartItem->product->available_stock . ' available.');
        }
    }

    DB::beginTransaction();
    try {
        // Create order
        $order = Order::create([
            'customer_id' => Auth::id(),
            'status' => 'pending',
            'notes' => $request->input('notes', null) 
        ]);

        $totalAmount = 0;

        // Create order items from cart AND DECREMENT STOCK
        foreach ($cartItems as $cartItem) {
            $lineTotal = $cartItem->quantity * $cartItem->product->price;
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->product->price,
                'line_total' => $lineTotal,
                'is_proceseed' => false
            ]);

            // ⭐ DECREMENT PRODUCT STOCK ⭐
            $cartItem->product->decrement('available_stock', $cartItem->quantity);

            $totalAmount += $lineTotal;
        }

        // Create bill
        Bill::create([
            'order_id' => $order->id,
            'total_amount' => $totalAmount,
            'balance' => $totalAmount
        ]);

        

        NotificationService::createOrderNotification($order);

        // Clear cart
        Cart::where('customer_id', Auth::id())->delete();

        DB::commit();

        return redirect()->route('customer.order.index')
            ->with('success', 'Order placed successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->with('error', 'Failed to place order: ' . $e->getMessage());
    }
}

    /**
     * Get cart total
     */
    private function getCartTotal()
    {
        return Cart::with('product')
            ->where('customer_id', Auth::id())
            ->get()
            ->sum(function($item) {
                return $item->quantity * $item->product->price;
            });
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $search = $request->input('search');

        $products = Product::query()
            ->when($search, function ($query) use ($search) {
                $query->where('product_name', 'LIKE', "%{$search}%")
                    ->orWhere('category', 'LIKE', "%{$search}%");
            })
            ->paginate(8);
            
        return view('customer.product.product-index', compact('products'))->with('category', 'search');
    }

    /**
     * Filter by vegetable category
     */
    public function selectVegetable()
    {
        $products = Product::where('category', 'vegetable')->paginate(8);
        return view('customer.product.product-index', compact('products'))->with('category', 'vegetable');
    }

    /**
     * Filter by grocery category
     */
    public function selectGrocery()
    {
        $products = Product::where('category', 'grocery')->paginate(8);
        return view('customer.product.product-index', compact('products'))->with('category', 'grocery');
    }
}