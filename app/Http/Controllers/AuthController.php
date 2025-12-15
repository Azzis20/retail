<?php

namespace App\Http\Controllers; // Keep this one, as your class is App\Http\Controllers\Auth\AuthController

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
    class AuthController extends Controller
    {
    
        public function loginPage()
        {
            return view('pages.login');
        }

        public function registerShow()
        {
            return view('pages.register');
        }
        public function forgotPassword()
        {
            return view('pages.forgot-password');
        }

        public function register(Request $request)
        {
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'contact' => ['required', 'string', 'max:20'],
                'address' => ['required', 'string', 'max:500'],
                // 'role' => ['required', 'in:vendor,customer'], // Only allow vendor or customer registration
            
            ]);

            $user = User::create([
                'fname' => $validated['first_name'],
                'lname' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'contact' => $validated['contact'],
                'address' => $validated['address'],
                'role' => 'customer',
            ]);

            // Automatically log in the user
            Auth::login($user);

            // Redirect based on role
            return match($user->role) {
                'vendor' => redirect()->route('vendor.dashboard')->with('success', 'Registration successful! Welcome to SukiOrder.'),
                'customer' => redirect()->route('customer.dashboard')->with('success', 'Registration successful! Welcome to SukiOrder.'),
                default => redirect()->route('login'),
            };
        }

        public function login(Request $request)
        {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();

                // Redirect based on user role
                return $this->redirectBasedOnRole();
            }

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        /**
         * Redirect user based on their role
         */
        protected function redirectBasedOnRole()
        {
            $user = Auth::user();

            return match($user->role) {
                'admin' => redirect()->intended(route('admin.dashboard')),
                'vendor' => redirect()->intended(route('vendor.dashboard')),
                'customer' => redirect()->intended(route('customer.dashboard')),
                default => redirect()->route('login')->with('error', 'Invalid role assigned.'),
            };
        }
        /**
         * Handle logout
         */
        public function logout(Request $request)
        {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('status', 'You have been logged out successfully.');
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
