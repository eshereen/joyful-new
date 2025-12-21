<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Order;
use App\Mail\OrderCreated;
use Exception;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email? : The email address to send test to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration and send test emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Email Configuration Test ===');
        $this->newLine();

        // 1. Show Configuration
        $this->info('1. Current Mail Configuration:');
        $this->line('   MAIL_MAILER: ' . config('mail.default'));
        $this->line('   MAIL_HOST: ' . config('mail.mailers.smtp.host', 'N/A'));
        $this->line('   MAIL_PORT: ' . config('mail.mailers.smtp.port', 'N/A'));
        $this->line('   MAIL_USERNAME: ' . config('mail.mailers.smtp.username', 'N/A'));
        $this->line('   MAIL_ENCRYPTION: ' . config('mail.mailers.smtp.encryption', 'N/A'));
        $this->line('   MAIL_FROM_ADDRESS: ' . config('mail.from.address'));
        $this->line('   MAIL_FROM_NAME: ' . config('mail.from.name'));
        $this->newLine();

        // 2. Check Queue
        $this->info('2. Queue Configuration:');
        $this->line('   QUEUE_CONNECTION: ' . config('queue.default'));

        if (config('queue.default') === 'database') {
            try {
                $jobsCount = DB::table('jobs')->count();
                $this->line('   Pending jobs in queue: ' . $jobsCount);

                if ($jobsCount > 0) {
                    $this->warn('   ⚠️  You have ' . $jobsCount . ' pending jobs. Run: php artisan queue:work');
                }
            } catch (Exception $e) {
                $this->error('   Error checking jobs: ' . $e->getMessage());
            }
        }
        $this->newLine();

        // 3. Check Recent Orders
        $this->info('3. Recent Orders:');
        try {
            $recentOrder = Order::latest()->first();
            if ($recentOrder) {
                $this->line('   Most recent order:');
                $this->line('   - Order ID: ' . $recentOrder->id);
                $this->line('   - Order Number: ' . $recentOrder->order_number);
                $this->line('   - Email: ' . $recentOrder->email);
                $this->line('   - Created: ' . $recentOrder->created_at);
                $this->line('   - Status: ' . $recentOrder->status->value);
                $this->line('   - Payment Status: ' . $recentOrder->payment_status->value);
            } else {
                $this->warn('   No orders found in database');
            }
        } catch (Exception $e) {
            $this->error('   Error: ' . $e->getMessage());
        }
        $this->newLine();

        // 4. Send Test Email
        $email = $this->argument('email');

        if (!$email) {
            $email = $this->ask('Enter email address to test (or press enter to skip)');
        }

        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->info('4. Sending Test Email to: ' . $email);

            try {
                Mail::raw('This is a test email from your Laravel application. If you receive this, your email configuration is working correctly!', function($message) use ($email) {
                    $message->to($email)
                            ->subject('Test Email - Configuration Works!');
                });

                $this->info('   ✅ Test email sent successfully!');

                if (config('queue.default') !== 'sync') {
                    $this->warn('   ⚠️  Email was queued. Run: php artisan queue:work');
                }

            } catch (Exception $e) {
                $this->error('   ❌ Failed to send email!');
                $this->error('   Error: ' . $e->getMessage());
                $this->line('   File: ' . $e->getFile() . ':' . $e->getLine());
            }
            $this->newLine();
        }

        // 5. Test Order Email
        if ($this->confirm('Do you want to test sending order confirmation email?', false)) {
            try {
                $recentOrder = Order::latest()->first();
                if ($recentOrder) {
                    $testEmail = $this->ask('Enter email to send test order email to', $recentOrder->email);

                    $this->info('Sending order confirmation email...');
                    Mail::to($testEmail)->send(new OrderCreated($recentOrder));

                    $this->info('   ✅ Order confirmation email sent!');

                    if (config('queue.default') !== 'sync') {
                        $this->warn('   ⚠️  Email was queued. Run: php artisan queue:work');
                    }
                } else {
                    $this->warn('No orders available to test with');
                }
            } catch (Exception $e) {
                $this->error('   ❌ Failed to send order email!');
                $this->error('   Error: ' . $e->getMessage());
            }
            $this->newLine();
        }

        // 6. Check Queue Status
        if (config('queue.default') !== 'sync') {
            $this->info('5. Process Queued Jobs:');
            if ($this->confirm('Do you want to process queued jobs now?', false)) {
                $this->call('queue:work', ['--once' => true]);
            } else {
                $this->warn('   Remember to run: php artisan queue:work');
            }
        }

        $this->newLine();
        $this->info('=== Test Complete ===');

        return Command::SUCCESS;
    }
}
