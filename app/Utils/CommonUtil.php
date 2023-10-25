<?php
/**
 * Author: raku <leli@mufan.design>
 */

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CommonUtil
{
    /**
     * 替换文本中的手机号
     *
     * @Author raku
     *
     * @param string $str
     * @return string
     */
    public static function filterMobile(string $str = ''): string
    {
        return Str::mask($str, "*", 3, 4);
    }

    public static function carbonParse($string, $default = null): ?Carbon
    {
        try {
            return Carbon::parse($string);
        } catch (\Throwable $throwable) {
            if ($default instanceof Carbon) {
                return $default;
            } else {
                if (!is_null($default)) {
                    return static::carbonParse($default);
                }
                return null;
            }
        }

    }

    /**
     * 记录请求日志
     *
     * @Author raku
     *
     * @param $request
     * @return void
     */
    public static function curlCommand($request): void
    {
        // 本地/测试 环境记录日志
        if (App::environment('local')) {
            $curlCommand = 'curl -X ' . $request->method() . ' \'' . $request->getSchemeAndHttpHost() . $request->getRequestUri() . '\'';
            foreach ($request->header() as $name => $values) {
                foreach ($values as $value) {
                    if (in_array($name, ['host', 'origin', 'content-length'])) {
                        continue;
                    }
                    $curlCommand .= ' -H \'' . $name . ': ' . $value . '\'';
                }
            }
            if ($request->method() !== 'GET') {
                $curlCommand .= ' --data-raw \'' . $request->getContent() . '\'';
            }
            Log::info($curlCommand);
        }
    }
}
