<?php

namespace App\Enums\RealEstate;

enum ProjectPropertyGroupEnum: string
{
    case APARTMENT = 'apartment';
    case HOUSE = 'house';
    case LAND = 'land';
    case COMMERCIAL = 'commercial';
    case ROOM = 'room';
    case PROJECT = 'project';

    public static function getDescription($value): string
    {
        return match ($value) {
            self::APARTMENT->value => 'Căn hộ',
            self::HOUSE->value => 'Nhà ở',
            self::LAND->value => 'Đất',
            self::COMMERCIAL->value => 'Thương mại',
            self::ROOM->value => 'Phòng trọ',
            self::PROJECT->value => 'Dự án',
            default => '',
        };
    }
}
