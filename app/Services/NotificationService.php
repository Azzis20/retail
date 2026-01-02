<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;

class NotificationService
{
    /**
     * Create a new order notification
     */
    public static function createOrderNotification(Order $order)
    {
        return Notification::create([
            'type' => Notification::TYPE_ORDER,
            'title' => 'New Order Received',
            'message' => "Order #{$order->id} from {$order->customer->fname} {$order->customer->lname}",
            'related_id' => $order->id,
            'related_type' => 'Order',
            'created_by' => $order->customer_id,
        ]);
    }

    /**
     * Create an order status change notification
     */
    public static function createOrderStatusNotification(Order $order, $oldStatus, $newStatus)
    {
        $statusText = match($newStatus) {
            'confirmed' => 'confirmed',
            'out-for-delivery' => 'out for delivery',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
            default => $newStatus,
        };

        return Notification::create([
            'type' => Notification::TYPE_ORDER,
            'title' => 'Order Status Updated',
            'message' => "Order #{$order->id} is now {$statusText}",
            'related_id' => $order->id,
            'related_type' => 'Order',
            'created_by' => auth()->id(),
            

        ]);
    }

    /**
     * Create a payment notification
     */
    public static function createPaymentNotification(Payment $payment)
    {
        $bill = $payment->bill;
        $order = $bill->order;

        return Notification::create([
            'type' => Notification::TYPE_PAYMENT,
            'title' => 'Payment Received',
            'message' => "₱" . number_format($payment->amount, 2) . " payment for Order #{$order->id}. Balance: ₱" . number_format($bill->balance, 2),
            'related_id' => $payment->id,
            'related_type' => 'Payment',
            'created_by' => $payment->recorded_by,
        ]);
    }

    /**
     * Create inventory change notification
     */
    public static function createInventoryNotification(Product $product, $oldStock, $newStock)
    {
        $change = $newStock - $oldStock;
        $action = $change > 0 ? 'increased' : 'decreased';
        
        return Notification::create([
            'type' => Notification::TYPE_INVENTORY,
            'title' => 'Inventory Updated',
            'message' => "{$product->product_name} stock {$action} from {$oldStock} to {$newStock}",
            'related_id' => $product->id,
            'related_type' => 'Product',
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Create stock alert notification
     */
    public static function createStockAlertNotification(Product $product)
    {
        return Notification::create([
            'type' => Notification::TYPE_STOCK_ALERT,
            'title' => 'Low Stock Alert',
            'message' => "{$product->product_name} is running low (Current: {$product->available_stock}, Min: {$product->min_threshold})",
            'related_id' => $product->id,
            'related_type' => 'Product',
        ]);
    }

    /**
     * Get unread notification count
     */
    public static function getUnreadCount()
    {
        return Notification::unread()->count();
    }

    /**
     * Get recent notifications
     */
    public static function getRecentNotifications($limit = 10)
    {
        return Notification::with('creator')
            ->recent($limit)
            ->get();
    }

    /**
     * Mark all as read
     */
    public static function markAllAsRead()
    {
        return Notification::unread()->update(['is_read' => true]);
    }
}