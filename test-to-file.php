<?php

$logFile = __DIR__ . '/shield-test.log';
file_put_contents($logFile, "Starting test...\n");

error_reporting(E_ALL);
ini_set('display_errors', '1');

try {
    file_put_contents($logFile, "Loading autoloader...\n", FILE_APPEND);
    require __DIR__.'/vendor/autoload.php';
    
    file_put_contents($logFile, "Loading bootstrap...\n", FILE_APPEND);
    $app = require_once __DIR__.'/bootstrap/app.php';
    
    file_put_contents($logFile, "Bootstrapping kernel...\n", FILE_APPEND);
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    file_put_contents($logFile, "Getting panel...\n", FILE_APPEND);
    $panel = Filament\Facades\Filament::getPanel('admin');
    
    file_put_contents($logFile, "Setting current panel...\n", FILE_APPEND);
    Filament\Facades\Filament::setCurrentPanel($panel);
    
    file_put_contents($logFile, "Getting Shield resources...\n", FILE_APPEND);
    $shieldResources = BezhanSalleh\FilamentShield\Facades\FilamentShield::getResources();
    
    file_put_contents($logFile, "\n=== RESULTS ===\n", FILE_APPEND);
    file_put_contents($logFile, "Shield Resources: " . count($shieldResources) . "\n", FILE_APPEND);
    file_put_contents($logFile, "Panel Resources: " . count($panel->getResources()) . "\n", FILE_APPEND);
    
    if (count($shieldResources) === 0) {
        file_put_contents($logFile, "\nERROR: Shield cannot see resources!\n", FILE_APPEND);
        file_put_contents($logFile, "Config discover_all_resources: " . (config('filament-shield.discovery.discover_all_resources') ? 'true' : 'false') . "\n", FILE_APPEND);
    } else {
        file_put_contents($logFile, "\nSUCCESS! Shield resources:\n", FILE_APPEND);
        foreach ($shieldResources as $resource) {
            file_put_contents($logFile, "  - " . ($resource['resource'] ?? 'unknown') . "\n", FILE_APPEND);
        }
    }
    
} catch (Throwable $e) {
    file_put_contents($logFile, "\nEXCEPTION: " . $e->getMessage() . "\n", FILE_APPEND);
    file_put_contents($logFile, "File: " . $e->getFile() . ":" . $e->getLine() . "\n", FILE_APPEND);
    file_put_contents($logFile, $e->getTraceAsString() . "\n", FILE_APPEND);
}

file_put_contents($logFile, "\nTest complete.\n", FILE_APPEND);

echo "Test complete. Check shield-test.log for results.\n";


