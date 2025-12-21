<?php

namespace App\Observers;

use App\Models\Order;
use App\Mail\OrderCreated;
use App\Mail\OrderShipped;
use App\Events\PaymentStatusChanged;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class OrderObserver
{

    public function created(Order $order): void
    {
        if ($order->coupon_id) {
            $order->coupon->increment('used_count');
        }

        //Send email to customer after order created
        try {
            Log::info('Attempting to send order confirmation email', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_email' => $order->email,
                'customer_name' => $order->first_name . ' ' . $order->last_name,
                'mail_driver' => config('mail.default'),
                'mail_host' => config('mail.mailers.smtp.host'),
                'mail_from' => config('mail.from.address'),
            ]);

            // Send to customer
            $customerMailSent = Mail::to($order->email)->send(new OrderCreated($order));
            
            Log::info('Customer email sent', [
                'order_id' => $order->id,
                'to' => $order->email,
                'result' => $customerMailSent ? 'success' : 'unknown'
            ]);
            
            // Also send copy to admin
            $adminMailSent = Mail::to('info@joyfulegy.com')->send(new OrderCreated($order));
            
            Log::info('Admin email sent', [
                'order_id' => $order->id,
                'to' => 'info@joyfulegy.com',
                'result' => $adminMailSent ? 'success' : 'unknown'
            ]);

            Log::info('Order confirmation emails completed', [
                'order_id' => $order->id,
                'customer_email' => $order->email,
                'admin_email' => 'info@joyfulegy.com'
            ]);
        } catch (Exception $e) {
            Log::error('Email sending failed but continuing with order', [
                'order_id' => $order->id,
                'email' => $order->email,
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Check if payment_status changed
        if ($order->wasChanged('payment_status')) {
            $oldStatus = $order->getOriginal('payment_status');
            $newStatus = $order->payment_status;

            // Fire event for payment status change
            event(new PaymentStatusChanged($order, $oldStatus, $newStatus));
        }

        // Restore stock when order is cancelled
        if ($order->wasChanged('status') && $order->status->value === 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->variant_id) {
                    $variant = \App\Models\Variant::find($item->variant_id);
                    if ($variant) {
                        $variant->increment('stock', $item->quantity);
                        Log::info('Stock restored for cancelled order', [
                            'order_id' => $order->id,
                            'variant_id' => $variant->id,
                            'restored_quantity' => $item->quantity,
                            'new_stock' => $variant->fresh()->stock
                        ]);
                    }
                }
            }
        }

        //Send Mail to customer after shipping
        if($order->wasChanged('status') && $order->status->value === 'shipped'){
            try {
                Log::info('Attempting to send order shipped email', [
                    'order_id' => $order->id,
                    'email' => $order->email
                ]);

                // Send to customer
                Mail::to($order->email)->send(new OrderShipped($order));
                
                // Also send copy to admin
                Mail::to('info@joyfulegy.com')->send(new OrderShipped($order));

                Log::info('Order shipped email sent successfully', [
                    'order_id' => $order->id,
                    'email' => $order->email,
                    'admin_copy' => 'info@joyfulegy.com'
                ]);
            } catch (Exception $e) {
                Log::error('Shipped email sending failed', [
                    'order_id' => $order->id,
                    'email' => $order->email,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
            }
        }
    }
}
