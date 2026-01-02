@extends('admin.layouts.app')

@section('title', 'Customer Section')

@section('page-title', 'Customer Section')

@section('content')

    <div class="product-header">
        <h1>Customer</h1>
    </div>

     <!-- Search Bar -->
    <form action="{{ route('admin.customer.search') }}" method="GET" class="search-form">
        <div class="search-container">
            <button type="submit" class="search-btn" aria-label="Search">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
            </button>
            <input 
                type="text" 
                name="search" 
                class="search-input" 
                placeholder="Search for customers..." 
                value="{{ request('search') }}"
            >
        </div>
    </form>

    

    <!-- Staff Grid Container -->
    <div class="staff-grid-container">
        @forelse($clients as $client)
            <a href="{{ route('admin.customer.show', $client->id) }}" class="staff-card-enhanced">
                <!-- Staff Card Header with Avatar -->
                <div class="staff-card-header-section">
                    <div class="staff-avatar-large">
                        @if($client->profile_picture)
                            <img src="{{ asset('storage/' . $client->profile_picture) }}" 
                                 alt="{{ $client->fname }}" 
                                 class="staff-avatar-img">
                        @else
                            <span class="staff-avatar-initials-large">
                                {{ strtoupper(substr($client->fname, 0, 1)) }}{{ strtoupper(substr($client->lname, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    <!-- Role Badge -->
                    <div class="staff-role-badge role-{{ strtolower($client->role) }}">
                        <i class="fa-solid fa-{{ $client->role === 'admin' ? 'user-shield' : ($client->role === 'customer' ? 'store' : 'user') }}"></i>
                        {{ ucfirst($client->role) }}
                    </div>
                </div>

                <!-- Staff Info Section -->
                <div class="staff-info-section">
                    <h3 class="staff-name-large">{{ $client->fname }} {{ $client->lname }}</h3>
                    
                    <!-- Contact Information -->
                    <div class="staff-contact-info">
                        <div class="staff-info-item">
                            <i class="fa-solid fa-envelope"></i>
                            <span class="staff-info-text">{{ $client->email }}</span>
                        </div>
                        
                        @if($client->contact)
                        <div class="staff-info-item">
                            <i class="fa-solid fa-phone"></i>
                            <span class="staff-info-text">{{ $client->contact }}</span>
                        </div>
                        @endif

                        @if($client->address)
                        <div class="staff-info-item">
                            <i class="fa-solid fa-location-dot"></i>
                            <span class="staff-info-text">{{ Str::limit($client->address, 40) }}</span>
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
                <h3 class="no-staff-title">No Customer found</h3>
                <p class="no-staff-description">
                    @if(request('search'))
                        No results found for "{{ request('search') }}". Try a different search term.
                    @else
                        Get started by adding your first staff member to the team.
                    @endif
                </p>
                @if(!request('search'))
                    <a href="{{ route('admin.customer.index') }}" class="btn-clear-search">
                    <i class="fa-solid fa-xmark"></i>
                    Clear Search
                </a>
                
                
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(method_exists($clients, 'links') && $clients->hasPages())
        <div class="pagination-wrapper">
            {{ $clients->links() }}
        </div>
    @endif

@endsection