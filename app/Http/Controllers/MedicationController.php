<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicationRequest;
use App\Models\Medication;
use App\Models\User;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    /**
     * Store a new medication
     */
    public function store(StoreMedicationRequest $request)
    {
        $telegramUser = $request->attributes->get('telegram_user', []);

        $user = User::where('telegram_id', $telegramUser['id'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $medication = $user->medications()->create([
            'name' => $request->name,
            'dosage' => $request->dosage,
            'time_to_take' => $request->time_to_take,
            'start_date' => now()->toDateString(),
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Medication added successfully',
            'data' => $medication,
        ]);
    }

    /**
     * Delete a medication
     */
    public function destroy(Request $request, Medication $medication)
    {
        $telegramUser = $request->attributes->get('telegram_user', []);

        $user = User::where('telegram_id', $telegramUser['id'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        // Check if medication belongs to user
        if ($medication->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $medication->delete();

        return response()->json([
            'success' => true,
            'message' => 'Medication deleted successfully',
        ]);
    }
}
