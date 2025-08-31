<?php

namespace App\Helpers;

class JsonKeyNormalizer
{

    public static function normalizeKeys(array $data): array
    {
        $normalized = [];
        foreach ($data as $key => $value) {
            $normalizedKey = self::normalizeKey($key);

            if (is_array($value)) {
                $value = self::normalizeKeys($value);
            }

            $normalized[$normalizedKey] = $value;
        }

        return $normalized;
    }

    protected static function normalizeKey(string $key): string
    {
        $key = strtolower($key);
        $key = str_replace('/', '_per_', $key);
        $key = preg_replace('/\s+/', '_', $key);
        return preg_replace('/[^a-z0-9_]/', '', $key);
    }

}
