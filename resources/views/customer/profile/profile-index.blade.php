@extends('customer.layouts.app')

@section('title', 'Profile')

@section('page-title', 'Profile')

@section('content')

        
<div class="staff-details-container">
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

    <!-- Header with Back Button -->
    <div class="staff-details-header">
       
        <h1>Profile Details</h1>
    </div>

  
    <!-- Staff Profile Card -->
    <div class="staff-profile-card">
        <div class="staff-profile-avatar">
            @if($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->firstname }}" class="profile-avatar-img">
            @else
                <span class="profile-avatar-initials">
                    {{ strtoupper(substr($user->firstname, 0, 1)) }}{{ strtoupper(substr($user->lastname, 0, 1)) }}
                </span>
            @endif
        </div>
        <div class="staff-profile-info">
            <h2 class="staff-profile-name">{{ $user->fname }} {{ $user->lname }}</h2>
            <p class="staff-profile-email">{{ $user->email }}</p>
        </div>
    </div>

    <!-- Personal Details Section -->
    <div class="details-section">
        <div class="haader-details-section">
            <h3 class="details-section-title">Personal Details</h3>
            <h4 class="details-section-Edit">Edit</h4>
        </div>
        
        <div class="details-card">
            <div class="detail-item">
                <label class="detail-label">First name</label>
                <p class="detail-value">{{ $user->fname }}</p>
            </div>

            <div class="detail-item">
                <label class="detail-label">Lastname</label>
                <p class="detail-value">{{ $user->lname }}</p>
            </div>

            <div class="detail-item">
                <label class="detail-label">Contact</label>
                <p class="detail-value">{{ $user->contact ?? 'N/A' }}</p>
            </div>

            <div class="detail-item">
                <label class="detail-label">Role</label>
                <p class="detail-value">{{ ucfirst($user->role) }}</p>
            </div>
        </div>
    </div>

    <!-- Saved Location Section -->
    <div class="details-section">
        
        <div class="haader-details-section">
            <h3 class="details-section-title">Saved Location</h3>
            <h4 class="details-section-Edit">Edit</h4>
        </div>
        
        <div class="location-card">
            <div class="location-header">
                <i class="fa-solid fa-shop"></i>
                <span class="location-header-text">Address</span>
            </div>
            <div class="location-content">
                <i class="fa-solid fa-location-dot"></i>
                <p class="location-text">{{ $user->address ?? 'No address saved' }}</p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    
        
       

    </div>

</div>


@endsection

