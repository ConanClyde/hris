<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Features\AIChatbot\Models\AIChatbotConversation;
use App\Features\AIChatbot\Models\AIChatbotMessage;

$conv = AIChatbotConversation::first();
if (! $conv) {
    echo "No conversation found.\n";
    exit;
}

try {
    $msg = new AIChatbotMessage([
        'conversation_id' => $conv->id,
        'role' => 'assistant',
        'sequence_number' => 999,
    ]);
    $msg->content = 'This is a test message';
    $msg->save();
    echo 'Saved message with ID: '.$msg->id."\n";
    $count = AIChatbotMessage::where('role', 'assistant')->count();
    echo 'Total assistant messages now: '.$count."\n";
} catch (\Exception $e) {
    echo 'Error saving message: '.$e->getMessage()."\n";
}
