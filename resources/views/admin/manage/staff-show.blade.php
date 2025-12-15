@extends('admin.layouts.app')

@section('title', 'Staff Details')

@section('page-title', 'Staff Details')

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
        <h1>Staff Details</h1>
    </div>

  
    <!-- Staff Profile Card -->
    <div class="staff-profile-card">
        <div class="staff-profile-avatar">
            @if($staff->profile_picture)
                <img src="{{ asset('storage/' . $staff->profile_picture) }}" alt="{{ $staff->firstname }}" class="profile-avatar-img">
            @else
                <span class="profile-avatar-initials">
                    {{ strtoupper(substr($staff->firstname, 0, 1)) }}{{ strtoupper(substr($staff->lastname, 0, 1)) }}
                </span>
            @endif
        </div>
        <div class="staff-profile-info">
            <h2 class="staff-profile-name">{{ $staff->fname }} {{ $staff->lname }}</h2>
            <p class="staff-profile-email">{{ $staff->email }}</p>
        </div>
    </div>

    <!-- Personal Details Section -->
    <div class="details-section">
        <h3 class="details-section-title">Personal Details</h3>
        
        <div class="details-card">
            <div class="detail-item">
                <label class="detail-label">First name</label>
                <p class="detail-value">{{ $staff->fname }}</p>
            </div>

            <div class="detail-item">
                <label class="detail-label">Lastname</label>
                <p class="detail-value">{{ $staff->lname }}</p>
            </div>

            <div class="detail-item">
                <label class="detail-label">Contact</label>
                <p class="detail-value">{{ $staff->contact ?? 'N/A' }}</p>
            </div>

            <div class="detail-item">
                <label class="detail-label">Role</label>
                <p class="detail-value">{{ ucfirst($staff->role) }}</p>
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
                <p class="location-text">{{ $staff->address ?? 'No address saved' }}</p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="staff-actions">
        <!-- <a href="{{ route('admin.manage.edit', $staff->id) }}" class="btn-edit-staff">
            <i class="fa-solid fa-pen-to-square"></i>
            <span>Edit Staff</span>
        </a> -->
        
        <form action="{{ route('admin.manage.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-delete-staff">
                <i class="fa-solid fa-trash"></i>
                <span>Delete Staff</span>
            </button>
        </form>

    </div>

</div>

@endsection