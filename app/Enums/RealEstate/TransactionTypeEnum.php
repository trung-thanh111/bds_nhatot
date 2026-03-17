<?php

namespace App\Enums\RealEstate;

enum TransactionTypeEnum: string
{
    case SALE = 'sale';
    case RENT = 'rent';
    case BOTH = 'both';

    public function description(): string
    {
        return match($this) {
            self::SALE => 'Bán',
            self::RENT => 'Cho thuê',
            self::BOTH => 'Cả hai',
        };
    }

    public static function toArray(): array
    {
        return [
            self::SALE->value => self::SALE->description(),
            self::RENT->value => self::RENT->description(),
            self::BOTH->value => self::BOTH->description(),
        ];
    }
}
