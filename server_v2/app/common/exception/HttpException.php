<?php

namespace app\common\exception;

use app\common\service\JsonService;
use support\exception\BusinessException;
use support\exception\Handler;
use Throwable;
use Webman\Http\Request;
use Webman\Http\Response;

class HttpException extends Handler
{

    public function render(Request $request, Throwable $exception): Response
    {
        if(($exception instanceof BusinessException) && ($response = $exception->render($request)))
        {
            return JsonService::fail($exception->getMessage());
        }
        return parent::render($request, $exception);
    }

}