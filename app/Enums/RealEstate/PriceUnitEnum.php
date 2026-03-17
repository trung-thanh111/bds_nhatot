<?php

namespace App\Enums\RealEstate;

enum PriceUnitEnum: string
{
    case TOTAL = 'total';
    case PER_M2 = 'per_m2';
    case PER_MONTH = 'per_month';
    case PER_M2_MONTH = 'per_m2_month';

    public function description(): string
    {
        return match($this) {
            self::TOTAL => 'Tổng cộng',
            self::PER_M2 => 'm²',
            self::PER_MONTH => 'Tháng',
            self::PER_M2_MONTH => 'm²/tháng',
        };
    }

    public static function toArray(): array
    {
        return [
            self::TOTAL->value => self::TOTAL->description(),
            self::PER_M2->value => self::PER_M2->description(),
            self::PER_MONTH->value => self::PER_MONTH->description(),
            self::PER_M2_MONTH->value => self::PER_M2_MONTH->description(),
        ];
    }
}
