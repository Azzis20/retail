<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash; 

class AdminManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {

        $staffs = User::where('role', 'vendor')->paginate(5);

        return view('admin.manage.staff-index',compact('staffs'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function addStaff()
    {
        //
        return view('admin.manage.staff-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
                'first_name' => 'required|string|min:3|max:255',
                'last_name' => 'required|string|min:3|max:255',
                'address' => 'nullable|string|max:500',
                'contact' => 'nullable|string|max:20',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required',
            ], [
                'name.required' => 'Please enter your full name.',
                'name.min' => 'Name must be at least 3 characters.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already registered.',
                'role.required' => 'Please select an account type.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
                'contact.max' => 'Contact number must not exceed 20 characters.',
                'address.max' => 'Address must not exceed 500 characters.',
            ]);

            // Create new user (defaults to 'client' role from migration)
                   $user = User::create([
                    'fname' => $validated['first_name'],
                    'lname' => $validated['last_name'],
                    'address' => $validated['address'] ?? null,
                    'contact' => $validated['contact'] ?? null,
                    'email' => $validated['email'],
                    'role' => 'vendor',
                    'password' => Hash::make($validated['password']),
                ]);

        return redirect()->route('admin.manage.index')->with('success', 'Employee successfully Added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //\$order = Order::with(['customer', 'processor'])->findOrFail($id);
        $staff = User::findOrFail($id);

        return view('admin.manage.staff-show', compact('staff'));

    }
     public function search(Request $request){

        $search = $request->input('search');

        $staffs = User::query()
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('fname', 'LIKE', "%{$search}%")
                ->orWhere('lname', 'LIKE', "%{$search}%");
            });
        })
        ->where('role', 'vendor') 
        ->paginate(6);
        return view('admin.manage.staff-index', compact('staffs'))->with('search');
    }
    



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
        $staff = User::findOrFail($id);
        $staff->delete();

        // Redirect to staff listing page
        return redirect()->route('admin.manage.index')
                        ->with('message', 'Staff deleted successfully!');
    }

}
