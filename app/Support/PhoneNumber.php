<?php

namespace App\Support;

class PhoneNumber
{
    public const E164_REGEX = '/^\+[1-9]\d{7,14}$/';

    public static function normalize(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone);

        return $digits ? '+'.$digits : null;
    }
}
