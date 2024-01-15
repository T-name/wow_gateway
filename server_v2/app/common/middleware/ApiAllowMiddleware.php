<?php

namespace app\common\middleware;

use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

/**
 * 跨域中间件
 */
class ApiAllowMiddleware implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        if ($request->method() === 'OPTIONS') {
            $response = response('');
        } else {
            $response = $handler($request);
        }
        return $response->withHeaders([
            'Access-Control-Allow-Origin'      => '*', // 注意：在生产环境中应该指定具体的域名而不是 '*'
            'Access-Control-Allow-Methods'     => 'GET, POST, PUT, DELETE, OPTIONS',
//            'Access-Control-Allow-Headers'     => 'X-Requested-With, Content-Type, Accept, Origin, Authorization,Token',
            'Access-Control-Allow-Headers'     => '*',
        ]);
    }
}
