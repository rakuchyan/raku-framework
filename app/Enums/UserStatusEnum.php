<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum UserStatusEnum: int
{
    case Activated = 1;
    case Disabled = 2;

    public function desc(): string
    {
        return Arr::get(self::list(), $this->value);
    }

    public static function list(): array
    {
        return [
            self::Activated->value => '已启用',
            self::Disabled->value => '已禁用',
        ];
    }
}
