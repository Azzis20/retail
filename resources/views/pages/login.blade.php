@extends('pages.layouts.app')

@section('title', 'Login')

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

    <h1 class="page-title">Login</h1>

    <form action="{{ route('login') }}" method="POST">
        @csrf
        
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

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    placeholder="Enter your password"
                >
            </div>
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="forgot-password">
            <a href="{{ route('forgot.password') }}">Forgot Password?</a>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <a href="{{ route('register.page') }}">
        <button type="button" class="btn btn-secondary">Register</button>
    </a>
</div>
@endsection