<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;


class AdminProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {

         $products = Product::latest()->paginate(8);

   
        return view('admin.product.product-index',compact('products'))->with('category', 'all');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.product.product-create');
    }




    public function search(Request $request){

        $search = $request->input('search');

                $products = Product::query()
                ->when($search, function ($query) use ($search) {
                    $query->where('product_name', 'LIKE', "%{$search}%")
                        ->orWhere('category', 'LIKE', "%{$search}%");
                     
                   
            })
            ->paginate(5);
        return view('admin.product.product-index', compact('products'))->with('category','search');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255',
            'category' => ['required', Rule::in(['grocery', 'vegetable'])],
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'available_stock' => 'required|numeric|min:0',
            'min_threshold' => 'required|numeric|min:0',
            
        ]);

  

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/picture'), $imageName);
            $imagePath = 'storage/picture/' . $imageName;
        }

        // Create the product
        $product = Product::create([
            'picture' => $imagePath,
            'product_name' => $validated['name'],
            'price' => $validated['price'],
            'category' => $validated['category'],
            'unit' => $validated['unit'],
            'available_stock' => $validated['available_stock'],
            'min_threshold' => $validated['min_threshold'],
        ]);

        // Redirect with success message
        return redirect()->route('admin.product.index')
            ->with('success', 'Product added successfully!');
    }




    public function selectVegetable()
    {
        $products = Product::where('category', 'vegetable')
            ->paginate(5);

        return view('admin.product.product-index', compact('products'))->with('category', 'vegetable');
    }

    public function selectGrocery()
    {
        $products = Product::where('category', 'grocery')
            ->paginate(5);

        return view('admin.product.product-index', compact('products'))->with('category', 'grocery');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $product = Product::findOrFail($id);

        // Pass the order to the view
        return view('admin.product.product-edit', compact('product'));
    }

public function update(Request $request, string $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'category' => 'required|in:grocery,vegetable',
        'price' => 'required|numeric|min:0',
        'unit' => 'required|in:kg,piece,pack,bundle',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'available_stock' => 'required|numeric|min:0',
        'min_threshold' => 'required|numeric|min:0'
    ]);

    $product = Product::findOrFail($id);

    $imagePath = $product->picture; // Keep current picture by default

    // Handle image upload
    if ($request->hasFile('image')) {
        // Delete old image if it exists
        if ($product->picture && file_exists(public_path($product->picture))) {
            unlink(public_path($product->picture));
        }

        // Move new uploaded image
        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('storage/picture'), $imageName);
        $imagePath = 'storage/picture/' . $imageName;
    }

    // Update product
    $product->update([
        'product_name' => $validated['name'],
        'category' => $validated['category'],
        'price' => $validated['price'],
        'unit' => $validated['unit'],
        'picture' => $imagePath,
        'available_stock' => $validated['available_stock'],
        'min_threshold' => $validated['min_threshold']
    ]);

    return redirect()->route('admin.product.index')
                     ->with('success', 'Product updated successfully');
}

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $product = Product::findOrFail($id);
        $product->delete();

        // Redirect to staff listing page
        return redirect()->route('admin.product.index')
                        ->with('message', 'Product deleted successfully!');
    }
}
