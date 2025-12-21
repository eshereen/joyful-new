<?php
/**
 * Test Order Email Sending
 * Upload to server and run: php test-order-email.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Mail\OrderCreated;

echo "=== Order Email Test ===\n\n";

// 1. Check mail configuration
echo "1. Mail Configuration:\n";
echo "   MAIL_MAILER: " . config('mail.default') . "\n";
echo "   MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "   MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "   MAIL_FROM: " . config('mail.from.address') . "\n\n";

// 2. Get latest order
echo "2. Finding latest order...\n";
try {
    $order = Order::latest()->first();
    
    if (!$order) {
        echo "   ❌ No orders found in database\n";
        echo "   Please create a test order first\n\n";
        exit(0);
    }
    
    echo "   ✅ Found order:\n";
    echo "   - Order ID: {$order->id}\n";
    echo "   - Order Number: {$order->order_number}\n";
    echo "   - Customer Email: {$order->email}\n";
    echo "   - Customer Name: {$order->first_name} {$order->last_name}\n";
    echo "   - Total: {$order->total_amount} EGP\n\n";
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// 3. Test sending email
echo "3. Testing Email Send:\n";
echo "   Enter email to send test to (or press enter for {$order->email}): ";
$testEmail = trim(fgets(STDIN));

if (empty($testEmail)) {
    $testEmail = $order->email;
}

echo "\n   Sending order confirmation email to: {$testEmail}\n";

try {
    // Send email
    Mail::to($testEmail)->send(new OrderCreated($order));
    
    echo "   ✅ Email sent successfully!\n";
    echo "   Check inbox at: {$testEmail}\n";
    echo "   (Also check spam/junk folder)\n\n";
    
} catch (\Exception $e) {
    echo "   ❌ Failed to send email!\n";
    echo "   Error: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    
    echo "   Troubleshooting:\n";
    echo "   1. Check .env mail settings\n";
    echo "   2. Run: php artisan config:clear\n";
    echo "   3. Verify email account exists in cPanel\n";
    echo "   4. Check storage/logs/laravel.log for details\n\n";
}

// 4. Check if OrderObserver is working
echo "4. Checking if OrderObserver is registered:\n";
try {
    $observers = \Illuminate\Support\Facades\Event::getListeners('eloquent.created: App\Models\Order');
    if (empty($observers)) {
        echo "   ⚠️  Warning: OrderObserver might not be registered\n";
        echo "   Check app/Providers/AppServiceProvider.php\n";
    } else {
        echo "   ✅ OrderObserver is registered\n";
    }
} catch (\Exception $e) {
    echo "   ℹ️  Could not verify observer registration\n";
}

echo "\n=== Test Complete ===\n";

