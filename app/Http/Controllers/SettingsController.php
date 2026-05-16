<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /**
     * Get the current settings.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $aiSettings = ApiKey::all();
        return response()->json([
            'ai_settings' => $aiSettings
        ]);
    }

    /**
     * Update global AI settings.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function updateAiSettings(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'provider' => 'required|string',
            'api_key' => 'required|string',
            'model' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // We use provider as the unique key for global defaults
        $setting = ApiKey::updateOrCreate(
            ['provider' => $request->provider],
            [
                'api_key' => $request->api_key,
                'model' => $request->model,
                'is_active' => true
            ]
        );

        return response()->json([
            'message' => 'Global AI settings updated successfully',
            'data' => $setting
        ]);
    }

    /**
     * Update the admin password.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'errors' => ['current_password' => ['The provided password does not match your current password.']]
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json(['message' => 'Password changed successfully']);
    }
}
