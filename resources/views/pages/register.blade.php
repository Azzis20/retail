<!-- //register.blade.php -->

@extends('pages.layouts.app')

@section('title', 'Create Your Account')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="auth-container">
    <div class="logo-section">
        <div class="logo-circle">
            <i class="fas fa-apple-alt"></i>
        </div>
        <div class="logo-text">SukiOrder</div>
    </div>

    <h1 class="page-title">Create<br>Your Account</h1>
    <p class="page-subtitle">Create and manage your account</p>

    <form action="{{ route('register') }}" method="POST">
        @csrf
        
        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First name</label>
                <input 
                    type="text" 
                    id="first_name" 
                    name="first_name" 
                    required 
                    placeholder="First name"
                    value="{{ old('first_name') }}"
                >
                @error('first_name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name">Last name</label>
                <input 
                    type="text" 
                    id="last_name" 
                    name="last_name" 
                    required 
                    placeholder="Last name"
                    value="{{ old('last_name') }}"
                >
                @error('last_name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <div class="input-wrapper">
                <i class="fas fa-envelope"></i>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required 
                    placeholder="Enter your email"
                    value="{{ old('email') }}"
                >
            </div>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- === START OF PASSWORD FIELDS === --}}
        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    placeholder="Create a password"
                >
            </div>
            {{-- Note: The validation error for 'password' covers min_8, required, and confirmed --}}
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- *** NEW CONFIRM PASSWORD FIELD *** --}}
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    required 
                    placeholder="Confirm your password"
                >
            </div>
            {{-- There is no separate validation error for password_confirmation, as 'confirmed' error attaches to 'password' --}}
        </div>
        {{-- === END OF PASSWORD FIELDS === --}}

        <div class="form-group">
            <label for="contact">Contact</label>
            <div class="input-wrapper">
                <i class="fas fa-phone"></i>
                <input 
                    type="tel" 
                    id="contact" 
                    name="contact" 
                    required 
                    placeholder="Enter your contact number"
                    value="{{ old('contact') }}"
                >
            </div>
            @error('contact')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <div class="input-wrapper">
                <i class="fas fa-map-marker-alt"></i>
                <input 
                    type="text" 
                    id="address" 
                    name="address" 
                    required 
                    placeholder="Enter your address"
                    value="{{ old('address') }}"
                >
            </div>
            @error('address')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
<!-- 
        {{-- Since you are restricting role to vendor/customer, you should add the role selection field here --}}
        {{-- I'm assuming you will add this based on your validation: ['role' => ['required', 'in:vendor,customer']] --}}
        <div class="form-group">
            <label for="role">Register as</label>
            <div class="input-wrapper">
                <i class="fas fa-user-tag"></i>
                <select id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="vendor" {{ old('role') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                </select>
            </div>
            @error('role')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div> -->

        <button type="submit" class="btn btn-primary">Register</button>
    </form>

    <a href="{{ route('login') }}" class="back-to-login">
        <span class="back-icon">
            <i class="fas fa-arrow-left"></i>
        </span>
        Back to Login
    </a>
</div>
@endsection