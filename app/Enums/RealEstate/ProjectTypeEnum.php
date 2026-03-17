<?php

namespace App\Enums\RealEstate;

enum ProjectTypeEnum: string
{
    case SALE = 'sale';
    case RENT = 'rent';
    case BOTH = 'both';

    public static function getDescription($value): string
    {
        return match ($value) {
            self::SALE->value => 'Bán',
            self::RENT->value => 'Cho thuê',
            self::BOTH->value => 'Cả hai',
            default => '',
        };
    }
}
