<?php

/**
 * Simple Email Test Script
 * Run with: php email-test-simple.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Mail\OrderCreated;

echo "\n";
echo "========================================\n";
echo "   Email Configuration Test\n";
echo "========================================\n\n";

// 1. Show Configuration
echo "📧 MAIL CONFIGURATION:\n";
echo "   Mailer: " . config('mail.default') . "\n";
echo "   Host: " . config('mail.mailers.smtp.host', 'N/A') . "\n";
echo "   Port: " . config('mail.mailers.smtp.port', 'N/A') . "\n";
echo "   Username: " . config('mail.mailers.smtp.username', 'N/A') . "\n";
echo "   Encryption: " . config('mail.mailers.smtp.encryption', 'N/A') . "\n";
echo "   From Address: " . config('mail.from.address') . "\n";
echo "   From Name: " . config('mail.from.name') . "\n";
echo "\n";

// 2. Queue Configuration
echo "⚙️  QUEUE CONFIGURATION:\n";
echo "   Connection: " . config('queue.default') . "\n";

if (config('queue.default') === 'database') {
    try {
        $jobsCount = DB::table('jobs')->count();
        echo "   Pending Jobs: " . $jobsCount . "\n";
        if ($jobsCount > 0) {
            echo "   ⚠️  WARNING: You have pending jobs! Run: php artisan queue:work\n";
        }
    } catch (Exception $e) {
        echo "   ❌ Error checking jobs: " . $e->getMessage() . "\n";
    }
}
echo "\n";

// 3. Recent Orders
echo "📦 RECENT ORDERS:\n";
try {
    $order = Order::latest()->first();
    if ($order) {
        echo "   Order ID: " . $order->id . "\n";
        echo "   Order #: " . $order->order_number . "\n";
        echo "   Email: " . $order->email . "\n";
        echo "   Status: " . $order->status->value . "\n";
        echo "   Payment: " . $order->payment_status . "\n";
        echo "   Date: " . $order->created_at . "\n";
    } else {
        echo "   No orders found\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// 4. Test Email
echo "========================================\n";
echo "Enter email address to test: ";
$email = trim(fgets(STDIN));

if (empty($email)) {
    echo "❌ No email provided. Exiting.\n\n";
    exit(0);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Invalid email address.\n\n";
    exit(1);
}

echo "\n📨 SENDING TEST EMAIL...\n";
try {
    Mail::raw('This is a test email from your Laravel application. If you receive this, your email is working!', function($message) use ($email) {
        $message->to($email)
                ->subject('Test Email - Email Working!');
    });

    echo "✅ Test email sent successfully!\n";

    if (config('queue.default') !== 'sync') {
        echo "⚠️  Email was QUEUED. You must run: php artisan queue:work\n";
    }

} catch (Exception $e) {
    echo "❌ FAILED to send email!\n";
    echo "   Error: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";

    // Show more details
    echo "🔍 TROUBLESHOOTING:\n";
    echo "   1. Check your .env file has correct MAIL_* settings\n";
    echo "   2. Make sure MAIL_MAILER=smtp (not 'log')\n";
    echo "   3. For Gmail: Use App Password, not regular password\n";
    echo "   4. Run: php artisan config:clear\n";
    exit(1);
}
echo "\n";

// 5. Test Order Email
echo "========================================\n";
echo "Test order confirmation email? (y/n): ";
$test = trim(fgets(STDIN));

if (strtolower($test) === 'y') {
    try {
        $order = Order::latest()->first();
        if (!$order) {
            echo "❌ No orders available to test\n\n";
            exit(0);
        }

        echo "Enter email (or press enter for " . $order->email . "): ";
        $orderEmail = trim(fgets(STDIN));
        if (empty($orderEmail)) {
            $orderEmail = $order->email;
        }

        echo "\n📨 SENDING ORDER CONFIRMATION...\n";
        Mail::to($orderEmail)->send(new OrderCreated($order));

        echo "✅ Order confirmation sent!\n";

        if (config('queue.default') !== 'sync') {
            echo "⚠️  Email was QUEUED. Run: php artisan queue:work\n";
        }

    } catch (Exception $e) {
        echo "❌ FAILED!\n";
        echo "   Error: " . $e->getMessage() . "\n";
    }
}

echo "\n";
echo "========================================\n";
echo "   Test Complete!\n";
echo "========================================\n\n";

if (config('queue.default') !== 'sync') {
    echo "⚠️  IMPORTANT: Your emails are QUEUED.\n";
    echo "   To send them, run: php artisan queue:work\n\n";
}


