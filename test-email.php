<?php

/**
 * Email Configuration Test Script
 * Run this with: php test-email.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

echo "\n=== Email Configuration Test ===\n\n";

// 1. Check Mail Configuration
echo "1. Checking Mail Configuration:\n";
echo "   MAIL_MAILER: " . config('mail.default') . "\n";
echo "   MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "   MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "   MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
echo "   MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption') . "\n";
echo "   MAIL_FROM_ADDRESS: " . config('mail.from.address') . "\n";
echo "   MAIL_FROM_NAME: " . config('mail.from.name') . "\n\n";

// 2. Check Queue Configuration
echo "2. Checking Queue Configuration:\n";
echo "   QUEUE_CONNECTION: " . config('queue.default') . "\n\n";

// 3. Check if jobs table exists (for database queue)
if (config('queue.default') === 'database') {
    try {
        $jobsCount = DB::table('jobs')->count();
        echo "3. Database Queue:\n";
        echo "   Pending jobs in queue: " . $jobsCount . "\n\n";
    } catch (Exception $e) {
        echo "3. Database Queue ERROR:\n";
        echo "   " . $e->getMessage() . "\n\n";
    }
}

// 4. Test sending email
echo "4. Testing Email Send:\n";
$testEmail = readline("   Enter your email address to test: ");

if (filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
    try {
        echo "   Sending test email to: $testEmail\n";

        Mail::raw('This is a test email from your Laravel application. If you receive this, your email configuration is working!', function($message) use ($testEmail) {
            $message->to($testEmail)
                    ->subject('Test Email - Email Configuration Works!');
        });

        echo "   ✅ Email sent successfully!\n";

        if (config('queue.default') !== 'sync') {
            echo "   ⚠️  Email was queued. Make sure queue worker is running: php artisan queue:work\n";
        }

    } catch (Exception $e) {
        echo "   ❌ Email sending failed!\n";
        echo "   Error: " . $e->getMessage() . "\n";
        echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
} else {
    echo "   ❌ Invalid email address\n";
}

echo "\n5. Checking Recent Order Emails:\n";
try {
    $recentOrder = DB::table('orders')->latest('created_at')->first();
    if ($recentOrder) {
        echo "   Most recent order:\n";
        echo "   - Order ID: " . $recentOrder->id . "\n";
        echo "   - Email: " . $recentOrder->email . "\n";
        echo "   - Created: " . $recentOrder->created_at . "\n";
    } else {
        echo "   No orders found in database\n";
    }
} catch (Exception $e) {
    echo "   Error checking orders: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n\n";

