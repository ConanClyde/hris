<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

$user = User::first();
Auth::login($user);

$req = Request::create('/api/ai/chat/stream', 'POST', [
    'message' => 'Hello AI, how are you?',
    'model' => 'gemini-3.1-flash-lite-preview',
]);

$controller = app(\App\Features\AIChatbot\Http\Controllers\AIChatbotController::class);

ob_start();
$response = $controller->chatStream($req);
if ($response instanceof \Symfony\Component\HttpFoundation\StreamedResponse) {
    $response->sendContent();
}
$content = ob_get_clean();

echo 'Content received length: '.strlen($content)."\n";
if (strlen($content) > 500) {
    echo 'Content snippet: '.substr($content, -500)."\n";
} else {
    echo 'Content snippet: '.$content."\n";
}

$count = \App\Features\AIChatbot\Models\AIChatbotMessage::where('role', 'assistant')->count();
echo 'Total assistant messages now: '.$count."\n";
