<?php
/**
 * Author: raku <leli@mufan.design>
 * Date: 2023/12/6
 */

namespace App\Enums;

use Illuminate\Support\Arr;

enum AdminUserType: string
{
    // 用户类型:platform,merchant,store
    case Platform = 'platform';
    case Merchant = 'merchant';
    case Store = 'store';

    public function desc(): string
    {
        return Arr::get(self::list(), $this->value);
    }

    public static function list(): array
    {
        return [
            self::Platform->value => '平台',
            self::Merchant->value => '商家',
            self::Store->value => '门店',
        ];
    }
}
