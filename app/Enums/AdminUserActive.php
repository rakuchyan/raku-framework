<?php
/**
 * Author: raku <leli@mufan.design>
 * Date: 2023/12/6
 */

namespace App\Enums;

use Illuminate\Support\Arr;

enum AdminUserActive: int
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
            self::Activated->value => '启用',
            self::Disabled->value => '禁用',
        ];
    }
}
