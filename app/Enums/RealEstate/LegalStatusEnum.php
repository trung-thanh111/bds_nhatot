<?php

namespace App\Enums\RealEstate;

enum LegalStatusEnum: string
{
    case SO_HONG = 'so_hong';
    case SO_DO = 'so_do';
    case HOP_DONG_MUA_BAN = 'hop_dong_mua_ban';
    case DANG_CHO_SO = 'dang_cho_so';
    case DU_AN = 'du_an';

    public function description(): string
    {
        return match($this) {
            self::SO_HONG => 'Sổ hồng',
            self::SO_DO => 'Sổ đỏ',
            self::HOP_DONG_MUA_BAN => 'Hợp đồng mua bán',
            self::DANG_CHO_SO => 'Đang chờ sổ',
            self::DU_AN => 'Pháp lý dự án',
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
