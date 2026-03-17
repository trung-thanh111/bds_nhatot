<?php

namespace App\Enums\RealEstate;

enum ProjectStatusEnum: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case REJECTED = 'rejected';
    case HIDDEN = 'hidden';

    public function description(): string
    {
        return match($this) {
            self::DRAFT => 'Tin nháp',
            self::PENDING => 'Chờ duyệt',
            self::ACTIVE => 'Đang hiển thị',
            self::REJECTED => 'Từ chối',
            self::HIDDEN => 'Tạm ẩn',
        };
    }

    public static function toArray(): array
    {
        return [
            self::DRAFT->value => self::DRAFT->description(),
            self::PENDING->value => self::PENDING->description(),
            self::ACTIVE->value => self::ACTIVE->description(),
            self::REJECTED->value => self::REJECTED->description(),
            self::HIDDEN->value => self::HIDDEN->description(),
        ];
    }
}
