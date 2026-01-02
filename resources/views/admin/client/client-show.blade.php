@extends('admin.layouts.app')

@section('title', 'Customer Details')

@section('page-title', 'Customer Details')

@section('content')

<div class="staff-details-container">
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

    <!-- Header with Back Button -->
    <div class="staff-details-header">
        <a href="{{ route('admin.manage.index') }}" class="back-button">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <h1>Client Details</h1>
    </div>

  
    <!-- Staff Profile Card -->
    <div class="staff-profile-card">
        <div class="staff-profile-avatar">
            @if($client->profile_picture)
                <img src="{{ asset('storage/' . $client->profile_picture) }}" alt="{{ $client->firstname }}" class="profile-avatar-img">
            @else
                <span class="profile-avatar-initials">
                    {{ strtoupper(substr($client->firstname, 0, 1)) }}{{ strtoupper(substr($client->lastname, 0, 1)) }}
                </span>
            @endif
        </div>
        <div class="staff-profile-info">
            <h2 class="staff-profile-name">{{ $client->fname }} {{ $client->lname }}</h2>
            <p class="staff-profile-email">{{ $client->email }}</p>
        </div>
    </div>

    <!-- Personal Details Section -->
    <div class="details-section">
        <h3 class="details-section-title">Personal Details</h3>
        
        <div class="details-card">
            <div class="detail-item">
                <label class="detail-label">First name</label>
                <p class="detail-value">{{ $client->fname }}</p>
            </div>

            <div class="detail-item">
                <label class="detail-label">Lastname</label>
                <p class="detail-value">{{ $client->lname }}</p>
            </div>

            <div class="detail-item">
                <label class="detail-label">Contact</label>
                <p class="detail-value">{{ $client->contact ?? 'N/A' }}</p>
            </div>

            <div class="detail-item">
                <label class="detail-label">Role</label>
                <p class="detail-value">{{ ucfirst($client->role) }}</p>
            </div>
        </div>
    </div>

    <!-- Saved Location Section -->
    <div class="details-section">
        <h3 class="details-section-title">Saved Location</h3>
        
        <div class="location-card">
            <div class="location-header">
                <i class="fa-solid fa-shop"></i>
                <span class="location-header-text">Address</span>
            </div>
            <div class="location-content">
                <i class="fa-solid fa-location-dot"></i>
                <p class="location-text">{{ $client->address ?? 'No address saved' }}</p>
            </div>
        </div>
    </div>



</div>

@endsection