<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------
declare (strict_types=1);

namespace app\common\middleware;

use app\common\service\JsonService;
use support\fast\Http;
use think\Exception;
use think\facade\Cache;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;


class LoginMiddleware implements MiddlewareInterface
{

    /**
     * @notes 初始化
     * @param $request
     * @param \Closure $next
     * @return mixed
     * @author 段誉
     * @date 2022/9/6 18:17
     */
    public function process(Request $request, callable $handler): Response
    {
        $controller = $request->controller;
        if ($controller) {
            $controller = new \ReflectionClass($request->controller);
            $notNeedLogin = $controller->getDefaultProperties()['notNeedLogin'] ?? [];
            // 访问的方法需要登录
            if (!in_array($request->action, $notNeedLogin)) {
                //获取token
                $token = $request->header('token');
                if(empty($token) || !getenv('APP_TOKEN')){
                   return JsonService::fail('请求参数缺少通讯密钥');
                }
                if($token !== md5(getenv('APP_TOKEN'))){
                    return JsonService::fail('通讯密钥错误');
                }
            }

            $ip = getenv('APP_IP');
            if(!Cache::get($ip)){
                $apiUrl = config('app.base_url')."/api/gateway/report?ip={$ip}";
                $res = Http::get($apiUrl);
                try {
                    $res = json_decode($res,true);
                    if($res['code'] !== 1){
                        return JsonService::fail($res['msg']);
                    }
                    Cache::set($ip,true,86400);
                }catch (Exception $exception){
                    return JsonService::fail($exception->getMessage());
                }
            }
        }
        return $handler($request);
    }

}