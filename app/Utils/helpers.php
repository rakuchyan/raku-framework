<?php
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

if (!function_exists('redis')) {
    /**
     * @return mixed|\Redis
     */
    function redis(): \Redis
    {
        return Redis::connection()->client();
    }
}

if (!function_exists('strMask')) {

    /**
     * string中间mask
     * @param $string
     * @param string $char
     * @return string
     */
    function strMiddleMask($string, string $char = '*'): string
    {
        $string = Str::padRight($string, 2, $char);
        $strLen = max([Str::length($string) - 2, 1]);
        return Str::mask($string, $char, 1, $strLen);
    }
}
