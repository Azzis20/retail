@extends('layouts.app')

@section('title', 'Reset Password')

@push('styles')
    <link rel="stylesheet" href="{{ asset('auth/admin.css') }}">
@endpush

@section('content')
<div class="auth-container">
    <div class="logo-section">
        <div class="logo-circle">
            <i class="fas fa-apple-alt"></i>
        </div>
        <div class="logo-text">SukiOrder</div>
    </div>

    <h1 class="page-title">Reset<br>Password</h1>
    <p class="page-subtitle">Enter your new password below.</p>

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        
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
                    value="{{ old('email', $email ?? '') }}"
                >
            </div>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">New Password</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    placeholder="Enter new password"
                >
            </div>
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    required 
                    placeholder="Confirm new password"
                >
            </div>
            @error('password_confirmation')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>

    <a href="{{ route('login') }}" class="back-to-login">
        <span class="back-icon">
            <i class="fas fa-arrow-left"></i>
        </span>
        Back to Login
    </a>
</div>
@endsection