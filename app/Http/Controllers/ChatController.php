<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Exception;

class ChatController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Handle the public chat request from the embed widget.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function chat(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'bot_id' => 'required|exists:bots,id',
            'visitor_id' => 'required|string|alpha_dash|min:10|max:50',
            'visitor_name' => 'nullable|string',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $bot = Bot::findOrFail($request->bot_id);

            // Find or create conversation
            $conversation = Conversation::firstOrCreate(
                [
                    'bot_id' => $bot->id,
                    'visitor_id' => $request->visitor_id,
                ],
                [
                    'visitor_name' => $request->visitor_name ?? 'Visitor',
                    'started_at' => now(),
                ]
            );

            // Save user message
            Message::create([
                'conversation_id' => $conversation->id,
                'role' => 'user',
                'content' => $request->message,
            ]);

            // Get last 10 messages for context
            $history = $conversation->messages()
                ->latest()
                ->take(10)
                ->get()
                ->reverse()
                ->map(function ($msg) {
                    return [
                        'role' => $msg->role,
                        'content' => $msg->content,
                    ];
                })
                ->toArray();

            // Load Knowledge Base (Training Data)
            $knowledge = \App\Models\TrainingData::where('bot_id', $bot->id)
                ->where('status', 'completed')
                ->get()
                ->pluck('content')
                ->filter()
                ->implode("\n\n");

            $systemPrompt = $bot->system_prompt ?? 'You are a helpful assistant.';
            if (!empty($knowledge)) {
                $systemPrompt .= "\n\nUSE THE FOLLOWING KNOWLEDGE TO ANSWER THE USER:\n" . $knowledge;
            }

            // Call AI Service
            $this->aiService->setBotConfig($bot);
            $aiReply = $this->aiService->sendMessage(
                $systemPrompt,
                $history
            );

            // Save AI reply
            Message::create([
                'conversation_id' => $conversation->id,
                'role' => 'assistant',
                'content' => $aiReply,
            ]);

            return response()->json([
                'reply' => $aiReply,
                'conversation_id' => $conversation->id,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to get AI response.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get chat history for the visitor.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function history(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'bot_id' => 'required|exists:bots,id',
            'visitor_id' => 'required|string|alpha_dash|min:10|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $conversation = Conversation::where('bot_id', $request->bot_id)
            ->where('visitor_id', $request->visitor_id)
            ->first();

        if (!$conversation) {
            return response()->json(['messages' => []]);
        }

        $paginator = $conversation->messages()->oldest()->paginate(50, ['role', 'content']);

        return response()->json([
            'messages' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'total' => $paginator->total(),
            ]
        ]);
    }
}
