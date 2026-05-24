<?php

namespace App\Support;

final class ProvincialOffice
{
    public const LA_UNION = 'La Union';
    public const ILOCOS_NORTE = 'Ilocos Norte';
    public const ILOCOS_SUR = 'Ilocos Sur';
    public const PANGASINAN = 'Pangasinan';

    public const ALL = [
        self::LA_UNION,
        self::ILOCOS_NORTE,
        self::ILOCOS_SUR,
        self::PANGASINAN,
    ];

    private function __construct()
    {
    }

    public static function all(): array
    {
        return self::ALL;
    }

    public static function isValid(?string $office): bool
    {
        return in_array((string) $office, self::ALL, true);
    }
}
