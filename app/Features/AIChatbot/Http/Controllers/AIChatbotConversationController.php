<?php

namespace App\Features\AIChatbot\Http\Controllers;

use App\Features\AIChatbot\Models\AIChatbotConversation;
use App\Features\AIChatbot\Models\AIChatbotMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AIChatbotConversationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $status = $request->validate(['status' => 'nullable|in:active,archived,all'])['status'] ?? 'active';
        $perPage = min(50, max(1, (int) $request->input('per_page', 20)));

        $query = AIChatbotConversation::forUser($user->id);

        if ($status !== 'all') {
            $query->where('status', $status);
        } else {
            $query->whereIn('status', ['active', 'archived']);
        }

        $conversations = $query
            ->orderByDesc('last_message_at')
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json([
            'data' => $conversations->map(fn ($c) => [
                'id' => $c->id,
                'title' => $c->title,
                'status' => $c->status,
                'last_message_at' => $c->last_message_at?->toDateTimeString(),
                'created_at' => $c->created_at->toDateTimeString(),
                'message_count' => $c->getMessageCount(),
            ]),
            'meta' => [
                'current_page' => $conversations->currentPage(),
                'last_page' => $conversations->lastPage(),
                'per_page' => $conversations->perPage(),
                'total' => $conversations->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:200',
        ]);

        $conversation = AIChatbotConversation::create([
            'user_id' => $user->id,
            'title' => $validated['title'] ?? 'New Chat',
            'status' => 'active',
            'metadata' => [],
        ]);

        return response()->json([
            'data' => [
                'id' => $conversation->id,
                'title' => $conversation->title,
                'status' => $conversation->status,
                'created_at' => $conversation->created_at->toDateTimeString(),
            ],
        ], 201);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $conversation = AIChatbotConversation::forUser($user->id)
            ->whereIn('status', ['active', 'archived'])
            ->find($id);

        if (! $conversation) {
            return response()->json(['error' => 'Conversation not found'], 404);
        }

        $perPage = min(100, max(1, (int) $request->input('per_page', 50)));
        $messages = $conversation->messages()->paginate($perPage);

        return response()->json([
            'data' => [
                'id' => $conversation->id,
                'title' => $conversation->title,
                'status' => $conversation->status,
                'metadata' => $conversation->metadata,
                'last_message_at' => $conversation->last_message_at?->toDateTimeString(),
                'created_at' => $conversation->created_at->toDateTimeString(),
                'messages' => $messages->map(fn ($m) => $m->toChatArray()),
            ],
            'meta' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
            ],
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $conversation = AIChatbotConversation::forUser($user->id)
            ->whereIn('status', ['active', 'archived'])
            ->find($id);

        if (! $conversation) {
            return response()->json(['error' => 'Conversation not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:200',
        ]);

        if (isset($validated['title'])) {
            $conversation->update(['title' => $validated['title']]);
        }

        return response()->json([
            'data' => [
                'id' => $conversation->id,
                'title' => $conversation->title,
                'status' => $conversation->status,
                'updated_at' => $conversation->updated_at->toDateTimeString(),
            ],
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $conversation = AIChatbotConversation::forUser($user->id)
            ->whereIn('status', ['active', 'archived'])
            ->find($id);

        if (! $conversation) {
            return response()->json(['error' => 'Conversation not found'], 404);
        }

        $conversation->softDelete();

        return response()->json(['message' => 'Conversation deleted']);
    }

    public function archive(string $id): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $conversation = AIChatbotConversation::forUser($user->id)
            ->where('status', 'active')
            ->find($id);

        if (! $conversation) {
            return response()->json(['error' => 'Conversation not found'], 404);
        }

        $conversation->archive();

        return response()->json([
            'message' => 'Conversation archived',
            'data' => [
                'id' => $conversation->id,
                'status' => $conversation->status,
            ],
        ]);
    }

    public function restore(string $id): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $conversation = AIChatbotConversation::forUser($user->id)
            ->where('status', 'archived')
            ->find($id);

        if (! $conversation) {
            return response()->json(['error' => 'Conversation not found'], 404);
        }

        $conversation->restore();

        return response()->json([
            'message' => 'Conversation restored',
            'data' => [
                'id' => $conversation->id,
                'status' => $conversation->status,
            ],
        ]);
    }

    public function storeMessage(Request $request, string $id): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $conversation = AIChatbotConversation::forUser($user->id)
            ->where('status', 'active')
            ->find($id);

        if (! $conversation) {
            return response()->json(['error' => 'Conversation not found or archived'], 404);
        }

        $validated = $request->validate([
            'role' => 'required|in:user,assistant,system',
            'content' => 'required|string|max:10000',
            'sources' => 'nullable|array',
            'tool_used' => 'nullable|string|max:100',
            'tool_data' => 'nullable|array',
        ]);

        $lastSequence = $conversation->messages()->max('sequence_number') ?? 0;

        $message = new AIChatbotMessage([
            'conversation_id' => $conversation->id,
            'role' => $validated['role'],
            'sequence_number' => $lastSequence + 1,
        ]);
        $message->content = $validated['content'];
        $message->sources = $validated['sources'] ?? null;
        $message->tool_used = $validated['tool_used'] ?? null;
        $message->tool_data = $validated['tool_data'] ?? null;
        $message->save();

        $conversation->updateLastMessage();

        if ($conversation->title === 'New Chat' && $validated['role'] === 'user') {
            $conversation->generateTitle($validated['content']);
        }

        return response()->json([
            'data' => $message->toChatArray(),
        ], 201);
    }

    public function recentMessages(string $id, Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $conversation = AIChatbotConversation::forUser($user->id)
            ->whereIn('status', ['active', 'archived'])
            ->find($id);

        if (! $conversation) {
            return response()->json(['error' => 'Conversation not found'], 404);
        }

        $limit = min(50, max(1, (int) $request->input('limit', 20)));

        $messages = $conversation->messages()
            ->orderByDesc('sequence_number')
            ->limit($limit)
            ->get()
            ->sortBy('sequence_number')
            ->values();

        return response()->json([
            'data' => $messages->map(fn ($m) => $m->toChatArray()),
            'meta' => [
                'count' => $messages->count(),
                'limit' => $limit,
            ],
        ]);
    }
}
