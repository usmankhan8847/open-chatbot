<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Get an overview of platform analytics.
     *
     * @return JsonResponse
     */
    public function overview(): JsonResponse
    {
        return response()->json([
            'total_bots' => Bot::count(),
            'total_conversations' => Conversation::count(),
            'total_messages' => Message::count(),
            'total_tokens_used' => Message::sum('tokens_used'),
        ]);
    }

    /**
     * Get detailed analytics for a specific bot.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function botStats(int $id): JsonResponse
    {
        $bot = Bot::find($id);

        if (!$bot) {
            return response()->json(['message' => 'Bot not found'], 404);
        }

        // Get daily message counts for the last 30 days
        $startDate = Carbon::now()->subDays(30);
        
        $dailyMessages = Message::whereHas('conversation', function ($query) use ($id) {
                $query->where('bot_id', $id);
            })
            ->where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return response()->json([
            'bot_name' => $bot->name,
            'conversation_count' => $bot->conversations()->count(),
            'message_count' => Message::whereHas('conversation', function ($query) use ($id) {
                $query->where('bot_id', $id);
            })->count(),
            'daily_messages' => $dailyMessages,
        ]);
    }
}
