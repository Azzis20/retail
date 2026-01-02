<?php
// app/Models/Notification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'related_id',
        'related_type',
        'is_read',
        'created_by',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
    ];

    // Type constants
    const TYPE_ORDER = 'order';
    const TYPE_PAYMENT = 'payment';
    const TYPE_INVENTORY = 'inventory';
    const TYPE_STOCK_ALERT = 'stock_alert';

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helper methods
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function getIconClass()
    {
        return match($this->type) {
            self::TYPE_ORDER => 'fa-basket-shopping',
            self::TYPE_PAYMENT => 'fa-money-bill-wave',
            self::TYPE_INVENTORY => 'fa-boxes-stacked',
            self::TYPE_STOCK_ALERT => 'fa-triangle-exclamation',
            default => 'fa-bell',
        };
    }

    public function getColorClass()
    {
        return match($this->type) {
            self::TYPE_ORDER => 'notification-order',
            self::TYPE_PAYMENT => 'notification-payment',
            self::TYPE_INVENTORY => 'notification-inventory',
            self::TYPE_STOCK_ALERT => 'notification-alert',
            default => 'notification-default',
        };
    }
}