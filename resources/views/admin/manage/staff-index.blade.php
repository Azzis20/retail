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
                placeholder="search for the staff.." 
                value="{{ request('search') }}"
            >
        </div>
    </form>

    <!-- Add Staff Button -->
    <a href="{{ route('admin.add.staff') }}" class="btn-add-product">
        <i class="fa-solid fa-plus"></i>
        <span>Add Staff</span>
    </a>

    <!-- Staff List Container -->
    <div class="staff-list-container">
        @forelse($staffs as $staff)
            <a href="{{ route('admin.manage.show', $staff->id) }}" class="staff-card-link">
                <div class="staff-card">
                    <!-- Avatar -->
                    <div class="staff-avatar">
                        @if($staff->profile_picture)
                            <img src="{{ asset('storage/' . $staff->profile_picture) }}" alt="{{ $staff->firstname }}" class="staff-avatar-img">
                        @else
                            <span class="staff-avatar-initials">
                                {{ strtoupper(substr($staff->fname, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    <!-- Staff Info -->
                    <div class="staff-info">
                        <h3 class="staff-name">{{ $staff->fname }} {{ $staff->fname }}</h3>
                        <p class="staff-role">{{ ucfirst($staff->role) }}</p>
                    </div>

                    <!-- Arrow Icon -->
                    <div class="staff-arrow">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </div>
            </a>
        @empty
            <div class="no-staff-message">
                <i class="fa-solid fa-users"></i>
                <p>No staff staff found</p>
                
            </div>
        @endforelse
    </div>

    <!-- Pagination (if needed) -->
    @if(method_exists($staffs, 'links'))
        <div class="pagination-wrapper">
            {{ $staffs->links() }}
        </div>
    @endif

@endsection