<?php

namespace App\Enums\RealEstate;

enum DirectionEnum: string
{
    case DONG = 'dong';
    case TAY = 'tay';
    case NAM = 'nam';
    case BAC = 'bac';
    case DONG_NAM = 'dong_nam';
    case TAY_NAM = 'tay_nam';
    case DONG_BAC = 'dong_bac';
    case TAY_BAC = 'tay_bac';

    public function description(): string
    {
        return match($this) {
            self::DONG => 'Đông',
            self::TAY => 'Tây',
            self::NAM => 'Nam',
            self::BAC => 'Bắc',
            self::DONG_NAM => 'Đông Nam',
            self::TAY_NAM => 'Tây Nam',
            self::DONG_BAC => 'Đông Bắc',
            self::TAY_BAC => 'Tây Bắc',
        };
    }

    public static function toArray(): array
    {
        $res = [];
        foreach(self::cases() as $case) {
            $res[$case->value] = $case->description();
        }
        return $res;
    }
}
