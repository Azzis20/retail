@extends('admin.layouts.app')

@section('title', 'Notifications')

@section('page-title', 'Notifications')

@section('content')

<div class="notifications-container">

    <!-- Header with Actions -->
    <div class="notifications-header">
        <h1 class="notifications-title">Notifications</h1>
        <div class="notifications-header-actions">
            @if($notifications->count() > 0)
                <!-- Mark All as Read Button -->
                @if($unreadCount > 0)
                    <form action="{{ route('admin.notifications.markAllRead') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-mark-all-read">
                            <i class="fa-solid fa-check-double"></i>
                            Mark All as Read
                        </button>
                    </form>
                @endif

                <!-- Delete All Button - Opens Modal -->
                <button type="button" class="btn-delete-all" id="openDeleteAllModalBtn">
                    <i class="fa-solid fa-trash"></i>
                    Delete All
                </button>
            @endif
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="notification-filter-tabs">
        <a href="{{ route('admin.notifications.index', ['type' => 'all']) }}" 
           class="notification-tab {{ $type === 'all' ? 'active' : '' }}">
            All
        </a>
        <a href="{{ route('admin.notifications.index', ['type' => 'order']) }}" 
           class="notification-tab {{ $type === 'order' ? 'active' : '' }}">
            <i class="fa-solid fa-basket-shopping"></i>
            Orders
        </a>
        <a href="{{ route('admin.notifications.index', ['type' => 'payment']) }}" 
           class="notification-tab {{ $type === 'payment' ? 'active' : '' }}">
            <i class="fa-solid fa-money-bill-wave"></i>
            Payments
        </a>
        <a href="{{ route('admin.notifications.index', ['type' => 'inventory']) }}" 
           class="notification-tab {{ $type === 'inventory' ? 'active' : '' }}">
            <i class="fa-solid fa-boxes-stacked"></i>
            Inventory
        </a>
        <a href="{{ route('admin.notifications.index', ['type' => 'stock_alert']) }}" 
           class="notification-tab {{ $type === 'stock_alert' ? 'active' : '' }}">
            <i class="fa-solid fa-triangle-exclamation"></i>
            Alerts
        </a>
    </div>

    <!-- Notifications List -->
    <div class="notifications-list">
        @forelse($notifications as $notification)
            <div class="notification-item {{ $notification->is_read ? 'read' : 'unread' }} {{ $notification->getColorClass() }}">
                <div class="notification-icon">
                    <i class="fa-solid {{ $notification->getIconClass() }}"></i>
                </div>

                <div class="notification-content">
                    <div class="notification-header-row">
                        <h3 class="notification-title">{{ $notification->title }}</h3>
                        <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="notification-message">{{ $notification->message }}</p>
                    @if($notification->creator)
                        <p class="notification-creator">
                            <i class="fa-solid fa-user"></i>
                            {{ $notification->creator->fname }} {{ $notification->creator->lname }}
                        </p>
                    @endif
                </div>

                <div class="notification-actions">
                    @if(!$notification->is_read)
                        <form action="{{ route('admin.notifications.markRead', $notification->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-notification-action" title="Mark as read">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </form>
                    @endif
                    
                    @if($notification->related_id && $notification->related_type === 'Order')
                        <a href="{{ route('admin.order.show', $notification->related_id) }}" 
                           class="btn-notification-action" title="View order">
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    @elseif($notification->related_id && $notification->related_type === 'Product')
                        <a href="{{ route('admin.product.index') }}" 
                           class="btn-notification-action" title="View product">
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    @endif

                    <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-notification-action btn-delete" 
                                title="Delete" 
                                onclick="return confirm('Delete this notification?')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="no-notifications">
                <i class="fa-solid fa-bell-slash"></i>
                <p>No notifications yet</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="pagination-wrapper">
            {{ $notifications->appends(['type' => $type])->links() }}
        </div>
    @endif

</div>

<!-- Delete All Confirmation Modal -->
<div class="delete-all-modal-overlay" id="deleteAllModal">
    <div class="delete-all-modal-content">
        <div class="delete-all-modal-icon">
            <i class="fa-solid fa-trash-can"></i>
        </div>
        <h2 class="delete-all-modal-title">Delete {{ $type === 'all' ? 'All' : ucfirst($type) }} Notifications?</h2>
        <p class="delete-all-modal-message">
            @if($type === 'all')
                This will permanently delete all notifications. This action cannot be undone.
            @else
                This will permanently delete all {{ $type }} notifications. This action cannot be undone.
            @endif
        </p>
        <div class="delete-all-modal-actions">
            <button type="button" class="btn-delete-all-cancel" id="closeDeleteAllModalBtn">
                <i class="fa-solid fa-xmark"></i>
                Cancel
            </button>
            <form action="{{ route('admin.notifications.destroyAll', ['type' => $type]) }}" 
                  method="POST" 
                  style="flex: 1;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete-all-confirm">
                    <i class="fa-solid fa-trash"></i>
                    Delete All
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteAllModal');
    const openBtn = document.getElementById('openDeleteAllModalBtn');
    const closeBtn = document.getElementById('closeDeleteAllModalBtn');

    // Open Modal
    if (openBtn) {
        openBtn.addEventListener('click', function() {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    // Close Modal
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    // Close when clicking outside modal
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

    // Close with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
});
</script>

@endsection