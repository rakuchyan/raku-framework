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

        // 生产环境下的数据库错误处理
        if (!config('app.debug') && $e instanceof QueryException) {
            Log::info($e->getMessage());
            return $this->error('Database connection error.', 500, $e);
        }

        // 根据异常类型返回对应的错误响应
        $errorResponses = [
            ValidationException::class => function ($e) {
                $errorList = $e->validator->getMessageBag()->getMessages();
                return $this->error(head(head($errorList)), 422, ['error' => array_keys($errorList)]);
            },

            \BadMethodCallException::class => function ($e) {
                $message = !config('app.debug') ? 'Bad method call exception.' : $e->getMessage();
                Log::info($e->getMessage());
                return $this->error($message, 400);
            },

            RouteNotFoundException::class => function ($e) {
                $message = !config('app.debug') ? 'Route not found exception.' : $e->getMessage();
                Log::info($e->getMessage());
                return $this->error($message, 404);
            },

            ModelNotFoundException::class => function ($e) {
                $message = !config('app.debug') ? 'Model not found exception.' : $e->getMessage();
                Log::info($e->getMessage());
                return $this->error($message, 404);
            },

            NotFoundHttpException::class => function ($e) {
                $message = !config('app.debug') ? 'Http route not found exception.' : $e->getMessage();
                Log::info($e->getMessage());
                return $this->error($message, 404);
            },

            AuthenticationException::class => function () {
                return $this->error('Invalid login information or verification failed.', 401);
            },

            TokenExpiredException::class => function () {
                return $this->error('Invalid login information or verification failed.', 401);
            },

            TokenInvalidException::class => function () {
                return $this->error('Invalid login information or verification failed.', 401);
            },

            HttpException::class => function ($e) {
                return $this->response($e->getMessage(), [], $e->getStatusCode());
            },

            \ErrorException::class => function ($e) {
                return $this->error('System error.', 500, $e);
            },

            \Error::class => function ($e) {
                return $this->error('System error.', 500, $e);
            }
        ];

        // 遍历异常类型并处理
        foreach ($errorResponses as $exceptionClass => $handler) {
            if ($e instanceof $exceptionClass) {
                return $handler($e);
            }
        }

        return parent::render($request, $e);
    }
}
