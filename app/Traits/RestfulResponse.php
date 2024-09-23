<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response as ApiResponse;
use stdClass;

trait RestfulResponse
{
    public $statusSuccess = 200; //响应成功 code

    protected $cookie;
    protected $header;
    public $jsonForceObj = false; // 是否强制返回object型json

    /**
     * 返回 201 已创建 响应
     *
     * @param array|string|null $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function created($data = null, array $headers = []): JsonResponse
    {
        $this->header = $headers;
        return $this->response('successful', $data, 201);
    }

    /**
     * 返回 204 无内容 响应
     *
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function noContent(array $headers = []): JsonResponse
    {
        $this->header = $headers;
        return $this->response('successful', [], 204);
    }

    /**
     * 返回 200 OK 响应
     *
     * @param $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = null, array $headers = [], $message = 'successful'): JsonResponse
    {
        $this->header = $headers;
        return $this->response($message, $data, $this->statusSuccess);
    }

    /**
     *
     * @param $data
     * @param string $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(string $message = '', int $code = 400, $data = [], int $status = 200): JsonResponse
    {
        if (empty($data)) {
            $data = new stdClass();
        }

        return $this->response($message, $data, $code, $status);
    }

    /**
     *
     * @param int $code
     * @param string $message
     * @param $data
     * @return JsonResponse
     */
    public function response(string $message, $data = [], int $code = 200, int $status = 200): JsonResponse
    {
        $msgpack = [
            'code' => $code,
            'message' => $message,
        ];
        if (!($data instanceof stdClass) && empty($data)) {
            $msgpack['data'] = new stdClass();
        } else {
            if (is_array($data)) {
                $msgpack['data'] = $data;
            } elseif (is_object($data)) {
                $additional = [];
                if ($data instanceof AnonymousResourceCollection) {
                    $additional = $data->additional;
                    $data = $data->resource;
                }
                // paginate 分页列表
                if (is_countable($data) && $data instanceof LengthAwarePaginator) {
                    $additional && $msgpack['data'] = $additional;
                    $msgpack['data']['list'] = Arr::get($data->toArray(), 'data', []);
                    $msgpack['data']['meta'] = [
                        'total' => $data->total(),
                        'current_page' => $data->currentPage(),
                        'per_page' => intval($data->perPage()),
                        'last_page' => $data->lastPage(),
                    ];
                } elseif ($data instanceof Collection) {
                    $msgpack['data']['list'] = $data;
                } else {
                    $msgpack['data'] = $data;
                }
            }
        }
        $response = ApiResponse::json($msgpack, $status, [], $this->jsonForceObj ? (JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE) : JSON_UNESCAPED_UNICODE);
        if (!is_null($this->cookie)) {
            $response = $response->withCookie($this->cookie);
        }

        if (!empty($this->header)) {
            foreach ($this->header as $key => $value) {
                $response = $response->header($key, $value);
            }
        }

        return $response;
    }
}
