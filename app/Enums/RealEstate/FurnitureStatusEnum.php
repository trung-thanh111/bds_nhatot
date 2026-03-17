<?php

namespace App\Enums\RealEstate;

enum FurnitureStatusEnum: string
{
    case FULL = 'full';
    case BASIC = 'basic';
    case NONE = 'none';

    public function description(): string
    {
        return match($this) {
            self::FULL => 'Nội thất đầy đủ',
            self::BASIC => 'Nội thất cơ bản',
            self::NONE => 'Không có nội thất',
        };
    }

    public static function toArray(): array
    {
        return [
            self::FULL->value => self::FULL->description(),
            self::BASIC->value => self::BASIC->description(),
            self::NONE->value => self::NONE->description(),
        ];
    }
}
