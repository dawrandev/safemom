<?php

namespace App\Services;

use App\Models\TelegramConversation;
use Carbon\Carbon;

class ConversationService
{
    /**
     * Start a new conversation
     *
     * @param string $telegramId
     * @param string $step
     * @return TelegramConversation
     */
    public function startConversation(string $telegramId, string $step): TelegramConversation
    {
        // Delete any existing conversations for this user
        TelegramConversation::where('telegram_id', $telegramId)->delete();

        // Create new conversation
        return TelegramConversation::create([
            'telegram_id' => $telegramId,
            'current_step' => $step,
            'data' => [],
            'expires_at' => Carbon::now()->addMinutes(30),
        ]);
    }

    /**
     * Get active conversation for telegram user
     *
     * @param string $telegramId
     * @return TelegramConversation|null
     */
    public function getConversation(string $telegramId): ?TelegramConversation
    {
        return TelegramConversation::where('telegram_id', $telegramId)
            ->active()
            ->first();
    }

    /**
     * Update conversation step
     *
     * @param string $telegramId
     * @param string $step
     * @return void
     */
    public function updateStep(string $telegramId, string $step): void
    {
        TelegramConversation::where('telegram_id', $telegramId)
            ->active()
            ->update([
                'current_step' => $step,
                'expires_at' => Carbon::now()->addMinutes(30),
            ]);
    }

    /**
     * Save data to conversation
     *
     * @param string $telegramId
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function saveData(string $telegramId, string $key, mixed $value): void
    {
        $conversation = $this->getConversation($telegramId);

        if ($conversation) {
            $data = $conversation->data ?? [];
            $data[$key] = $value;

            $conversation->update([
                'data' => $data,
                'expires_at' => Carbon::now()->addMinutes(30),
            ]);
        }
    }

    /**
     * Get conversation data
     *
     * @param string $telegramId
     * @param string|null $key
     * @return mixed
     */
    public function getData(string $telegramId, ?string $key = null): mixed
    {
        $conversation = $this->getConversation($telegramId);

        if (!$conversation) {
            return null;
        }

        $data = $conversation->data ?? [];

        if ($key === null) {
            return $data;
        }

        return $data[$key] ?? null;
    }

    /**
     * Complete and delete conversation
     *
     * @param string $telegramId
     * @return void
     */
    public function completeConversation(string $telegramId): void
    {
        TelegramConversation::where('telegram_id', $telegramId)->delete();
    }

    /**
     * Check if user is in active conversation
     *
     * @param string $telegramId
     * @return bool
     */
    public function isInConversation(string $telegramId): bool
    {
        return TelegramConversation::where('telegram_id', $telegramId)
            ->active()
            ->exists();
    }

    /**
     * Clean up expired conversations
     *
     * @return int Number of deleted conversations
     */
    public function cleanupExpired(): int
    {
        return TelegramConversation::expired()->delete();
    }
}
