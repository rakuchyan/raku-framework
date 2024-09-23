<?php

namespace App\Exceptions;

use App\Traits\RestfulResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    use RestfulResponse;

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     *
     *
     * @param $request
     * @param Throwable $e
     * @return Response|JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        // dd( get_class($e));

        // 正式环境需要去敏感
        if (!config('app.debug')) {
            if ($e instanceof QueryException) {
                return $this->error('数据库连接错误', 500, $e);
            }
        }

        // validate 参数验证
        if ($e instanceof ValidationException) {
            $errorList = $e->validator->getMessageBag()->getMessages();
            return $this->error(head(head($errorList)), 422, ['error' => array_keys($errorList)]);
        }

        if ($e instanceof RouteNotFoundException || $e instanceof \BadMethodCallException) {
            return $this->error('路由不存在', 404);
        }

        if ($e instanceof ModelNotFoundException) {
            return $this->error('数据不存在', 404);
        }

        if ($e instanceof AuthenticationException || $e instanceof TokenExpiredException || $e instanceof TokenInvalidException) {
            return $this->error('登录信息无效或校验失败', 401);
        }

        if ($e instanceof HttpException) {
            return $this->response($e->getMessage(), [], $e->getStatusCode());
        }

        if ($e instanceof \ErrorException || $e instanceof \Error) {
            return $this->error('系统错误', 500, $e);
        }

        // 参数验证异常
        /*if ($e instanceof ValidationException) {
            $errorList = $e->validator->getMessageBag()->getMessages();
            return $this->error(head(head($errorList)), 422, ['error' => array_keys($errorList)]);
        } else if ($e instanceof RouteNotFoundException || $e instanceof \BadMethodCallException) {
            return $this->error('路由不存在', 404);
        } else if ($e instanceof ModelNotFoundException) {
            return $this->error('数据不存在', 404);
        } else if ($e instanceof AuthenticationException) {
            return $this->error('请先登录', 401);
        } else if ($e instanceof TokenExpiredException) {
            return $this->error('登录信息无效', 401);
        } else if ($e instanceof TokenInvalidException) {
            return $this->error('登录校验失败', 401);
        } else if ($e instanceof HttpException) {
            return $this->response($e->getMessage(), [], $e->getStatusCode());
        } else if ($e instanceof \ErrorException) {
            return $this->error('系统错误' . $e->getMessage(), 500);
        } else if ($e instanceof \Error) {
            return $this->error('系统错误' . $e->getMessage(), 500);
        } else if ($e instanceof QueryException) {
            return $this->error('系统错误' . $e->getMessage(), 500);
        }*/

        return parent::render($request, $e);
    }
}
