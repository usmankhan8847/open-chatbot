<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$bot = \App\Models\Bot::first();
$ai = app(\App\Services\AIService::class);
$ai->setBotConfig($bot);
try {
    echo "Sending...\n";
    echo $ai->sendMessage('test', [['role' => 'user', 'content' => 'hi']]);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
