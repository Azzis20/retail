@extends('admin.layouts.app')

@section('title', 'Manage Staff')

@section('page-title', 'Manage Staff')

@section('content')

    <div class="product-header">
        <h1>Manage Staff</h1>
    </div>

     <!-- Search Bar -->
    <form action="{{ route('admin.manage.search') }}" method="GET" class="search-form">
        <div class="search-container">
            <button type="submit" class="search-btn" aria-label="Search">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
            </button>
            <input 
                type="text" 
                name="search" 
                class="search-input" 
                placeholder="Search for staff members..." 
                value="{{ request('search') }}"
            >
        </div>
    </form>

    <!-- Add Staff Button -->
    <a href="{{ route('admin.add.staff') }}" class="btn-add-product">
        <i class="fa-solid fa-plus"></i>
        <span>Add New Staff</span>
    </a>

    <!-- Staff Grid Container -->
    <div class="staff-grid-container">
        @forelse($staffs as $staff)
            <a href="{{ route('admin.manage.show', $staff->id) }}" class="staff-card-enhanced">
                <!-- Staff Card Header with Avatar -->
                <div class="staff-card-header-section">
                    <div class="staff-avatar-large">
                        @if($staff->profile_picture)
                            <img src="{{ asset('storage/' . $staff->profile_picture) }}" 
                                 alt="{{ $staff->fname }}" 
                                 class="staff-avatar-img">
                        @else
                            <span class="staff-avatar-initials-large">
                                {{ strtoupper(substr($staff->fname, 0, 1)) }}{{ strtoupper(substr($staff->lname, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    <!-- Role Badge -->
                    <div class="staff-role-badge role-{{ strtolower($staff->role) }}">
                        <i class="fa-solid fa-{{ $staff->role === 'admin' ? 'user-shield' : ($staff->role === 'vendor' ? 'store' : 'user') }}"></i>
                        {{ ucfirst($staff->role) }}
                    </div>
                </div>

                <!-- Staff Info Section -->
                <div class="staff-info-section">
                    <h3 class="staff-name-large">{{ $staff->fname }} {{ $staff->lname }}</h3>
                    
                    <!-- Contact Information -->
                    <div class="staff-contact-info">
                        <div class="staff-info-item">
                            <i class="fa-solid fa-envelope"></i>
                            <span class="staff-info-text">{{ $staff->email }}</span>
                        </div>
                        
                        @if($staff->contact)
                        <div class="staff-info-item">
                            <i class="fa-solid fa-phone"></i>
                            <span class="staff-info-text">{{ $staff->contact }}</span>
                        </div>
                        @endif

                        @if($staff->address)
                        <div class="staff-info-item">
                            <i class="fa-solid fa-location-dot"></i>
                            <span class="staff-info-text">{{ Str::limit($staff->address, 40) }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="staff-card-footer">
                    <span class="staff-view-details">
                        View Details
                        <i class="fa-solid fa-arrow-right"></i>
                    </span>
                </div>
            </a>
        @empty
            <div class="no-staff-message-enhanced">
                <div class="no-staff-icon-wrapper">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h3 class="no-staff-title">No Staff Members Found</h3>
                <p class="no-staff-description">
                    @if(request('search'))
                        No results found for "{{ request('search') }}". Try a different search term.
                    @else
                        Get started by adding your first staff member to the team.
                    @endif
                </p>
                @if(!request('search'))
                <a href="{{ route('admin.add.staff') }}" class="btn-add-first-staff">
                    <i class="fa-solid fa-plus"></i>
                    Add First Staff Member
                </a>
                @else
                <a href="{{ route('admin.manage.index') }}" class="btn-clear-search">
                    <i class="fa-solid fa-xmark"></i>
                    Clear Search
                </a>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(method_exists($staffs, 'links') && $staffs->hasPages())
        <div class="pagination-wrapper">
            {{ $staffs->links() }}
        </div>
    @endif

@endsection