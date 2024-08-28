<?php

namespace App\Constants;

enum CacheKey: string
{
    case SmsLoginPhone = 'smsLogin:phone_%s';


    public function getKey(...$values): string
    {
        return sprintf($this->value, ...$values);
    }
}
