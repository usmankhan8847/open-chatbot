<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class BotController extends Controller
{
    /**
     * Display a listing of the bots.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $bots = Bot::withCount('conversations')->get();
        return response()->json($bots);
    }

    /**
     * Store a newly created bot in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|string',
            'system_prompt' => 'nullable|string',
            'ai_provider' => 'nullable|string',
            'ai_model' => 'nullable|string',
            'api_key' => 'nullable|string',
            'temperature' => 'nullable|numeric|min:0|max:1',
            'max_tokens' => 'nullable|integer|min:1',
            'welcome_message' => 'nullable|string',
            'allowed_domains' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $bot = Bot::create($request->all());

        return response()->json($bot, 201);
    }

    /**
     * Display the specified bot.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $bot = Bot::find($id);

        if (!$bot) {
            return response()->json(['message' => 'Bot not found'], 404);
        }

        return response()->json($bot);
    }

    /**
     * Update the specified bot in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $bot = Bot::find($id);

        if (!$bot) {
            return response()->json(['message' => 'Bot not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'avatar' => 'nullable|string',
            'system_prompt' => 'nullable|string',
            'ai_provider' => 'nullable|string',
            'ai_model' => 'nullable|string',
            'api_key' => 'nullable|string',
            'temperature' => 'nullable|numeric|min:0|max:1',
            'max_tokens' => 'nullable|integer|min:1',
            'welcome_message' => 'nullable|string',
            'allowed_domains' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $bot->update($request->all());

        return response()->json($bot);
    }

    /**
     * Remove the specified bot from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $bot = Bot::find($id);

        if (!$bot) {
            return response()->json(['message' => 'Bot not found'], 404);
        }

        $bot->delete();

        return response()->json(['message' => 'Bot deleted successfully']);
    }
}
