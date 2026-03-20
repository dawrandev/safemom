<?php

namespace App\Services;

use App\Models\User;

class RegistrationService
{
    /**
     * Check if user is already registered
     *
     * @param string $telegramId
     * @return bool
     */
    public function isRegistered(string $telegramId): bool
    {
        return User::where('telegram_id', $telegramId)->exists();
    }

    /**
     * Get user by telegram ID
     *
     * @param string $telegramId
     * @return User|null
     */
    public function getUserByTelegramId(string $telegramId): ?User
    {
        return User::where('telegram_id', $telegramId)->first();
    }

    /**
     * Create new user
     *
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'phone' => $this->formatPhone($data['phone']),
            'telegram_id' => $data['telegram_id'],
            'role' => $data['role'] ?? 'patient',
            'email' => $data['email'] ?? null,
            'password' => $data['password'] ?? null,
        ]);
    }

    /**
     * Validate phone number
     *
     * @param string $phone
     * @return bool
     */
    public function validatePhone(string $phone): bool
    {
        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $phone);

        // Check if phone has at least 9 digits
        return strlen($phone) >= 9;
    }

    /**
     * Format phone number
     *
     * @param string $phone
     * @return string
     */
    public function formatPhone(string $phone): string
    {
        // Remove all non-digit characters except +
        $phone = preg_replace('/[^\d+]/', '', $phone);

        // Ensure it starts with +
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        return $phone;
    }

    /**
     * Check if phone already exists
     *
     * @param string $phone
     * @return bool
     */
    public function phoneExists(string $phone): bool
    {
        $formattedPhone = $this->formatPhone($phone);
        return User::where('phone', $formattedPhone)->exists();
    }

    /**
     * Validate name or surname
     *
     * @param string $value
     * @return bool
     */
    public function validateName(string $value): bool
    {
        // Check minimum length
        if (strlen(trim($value)) < 2) {
            return false;
        }

        // Check if contains only letters, spaces, and common name characters
        return preg_match("/^[\p{L}\s'-]+$/u", $value);
    }
}
