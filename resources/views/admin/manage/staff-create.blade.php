@extends('admin.layouts.app')

@section('title', 'Add Staff')

@section('page-title', 'Manage Staff')

@section('content')

<div class="staff-form-container">
    
    <!-- Header with Back Button -->
    <div class="form-header">
        <a href="{{ route('admin.manage.index') }}" class="back-button">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
        <h1>Add Staff</h1>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif
    

    <!-- Error Summary -->
    @if($errors->any())
    <div class="alert alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        <div>
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    <!-- Staff Form -->
    <form action="{{ route('admin.manage.store') }}" method="POST" class="staff-form">
        @csrf

        <!-- Hidden role field set to vendor -->
        <input type="hidden" name="role" value="vendor">

        <!-- Personal Information Section -->
        <div class="form-section">
            <h2 class="form-section-title">Personal Information</h2>

            <div class="form-row">
                <div class="form-group">
                    <label for="first_name" class="form-label">
                        First Name <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="first_name" 
                        name="first_name" 
                        class="form-input @error('first_name') input-error @enderror" 
                        value="{{ old('first_name') }}"
                        placeholder="Enter first name"
                        required
                    >
                    @error('first_name')
                    <span class="error-message">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="last_name" class="form-label">
                        Last Name <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="last_name" 
                        name="last_name" 
                        class="form-input @error('last_name') input-error @enderror" 
                        value="{{ old('last_name') }}"
                        placeholder="Enter last name"
                        required
                    >
                    @error('last_name')
                    <span class="error-message">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Address</label>
                <input 
                    type="text" 
                    id="address" 
                    name="address" 
                    class="form-input @error('address') input-error @enderror" 
                    value="{{ old('address') }}"
                    placeholder="Enter complete address"
                >
                @error('address')
                <span class="error-message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $message }}
                </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="contact" class="form-label">Contact Number</label>
                <input 
                    type="tel" 
                    id="contact" 
                    name="contact" 
                    class="form-input @error('contact') input-error @enderror" 
                    value="{{ old('contact') }}"
                    placeholder="09XX XXX XXXX"
                    maxlength="20"
                >
                @error('contact')
                <span class="error-message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $message }}
                </span>
                @enderror
            </div>
        </div>

        <!-- Account Information Section -->
        <div class="form-section">
            <h2 class="form-section-title">Account Information</h2>

            <div class="form-group">
                <label for="email" class="form-label">
                    Email Address <span class="required">*</span>
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input @error('email') input-error @enderror" 
                    value="{{ old('email') }}"
                    placeholder="email@example.com"
                    required
                >
                @error('email')
                <span class="error-message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ $message }}
                </span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">
                        Password <span class="required">*</span>
                    </label>
                    <div class="password-input-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input @error('password') input-error @enderror" 
                            placeholder="Minimum 8 characters"
                            required
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                    <span class="error-message">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">
                        Confirm Password <span class="required">*</span>
                    </label>
                    <div class="password-input-wrapper">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="form-input @error('password_confirmation') input-error @enderror" 
                            placeholder="Re-enter password"
                            required
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                    <span class="error-message">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('admin.manage.index') }}" class="btn-cancel">
                Cancel
            </a>
            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-user-plus"></i>
                Add Staff Member
            </button>
        </div>
    </form>

</div>

<script>
    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const button = field.parentElement.querySelector('.password-toggle i');
        
        if (field.type === 'password') {
            field.type = 'text';
            button.classList.remove('fa-eye');
            button.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            button.classList.remove('fa-eye-slash');
            button.classList.add('fa-eye');
        }
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
</script>

@endsection