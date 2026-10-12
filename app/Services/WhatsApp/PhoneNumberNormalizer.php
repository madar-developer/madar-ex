<?php

namespace App\Services\WhatsApp;

use App\Exceptions\WhatsAppException;

class PhoneNumberNormalizer
{
    /**
     * Normalize a phone number to WhatsApp Cloud API format (digits only, E.164 without '+').
     *
     * Does not assume a country. Numbers without a clear international country code are rejected.
     *
     * @throws WhatsAppException
     */
    public function normalize(string $phone): string
    {
        $original = trim($phone);

        if ($original === '') {
            throw new WhatsAppException('Phone number is required.', 422, [], false, 422);
        }

        $hasPlus = str_starts_with($original, '+');
        $digits = preg_replace('/\D+/', '', $original) ?? '';

        if ($digits === '') {
            throw new WhatsAppException('Phone number is invalid.', 422, [], false, 422);
        }

        // Strip a single leading international truncator "00"
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
            $hasPlus = true;
        }

        // Reject local-looking numbers that lack an international prefix.
        // WhatsApp requires a country code; we never invent one.
        if (! $hasPlus && strlen($digits) <= 10) {
            throw new WhatsAppException(
                'Phone number must include a country code in international format (e.g. +9665XXXXXXX).',
                422,
                [],
                false,
                422
            );
        }

        // Typical E.164 max length is 15 digits.
        if (strlen($digits) < 8 || strlen($digits) > 15) {
            throw new WhatsAppException('Phone number length is invalid.', 422, [], false, 422);
        }

        return $digits;
    }
}
