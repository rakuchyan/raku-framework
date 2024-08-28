<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BuildCurlLog
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);
        if (app()->isLocal()) {
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
            logger()->channel('request_curl')->info($curlCommand);
        }
        return $response;
    }
}
