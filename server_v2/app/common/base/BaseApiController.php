<?php

declare(strict_types=1);

namespace app\common\base;

use app\common\service\JsonService;

/**
 * 控制器基类
 * Class BaseLikeAdminController
 * @package app\common\controller
 */
class BaseApiController
{

    /**
     * 请求对象
     */
    protected $request;




    /**
     * 免登录
     * @var array
     */
    public $notNeedLogin = [];


    /**
     * 构造方法
     */
    public function __construct()
    {
        // 将当前的Request对象保存为类属性
        $this->request = request();
    }


    /**
     * @notes 操作成功
     * @param string $msg
     * @param array $data
     * @param int $code
     * @param int $show
     * @return \support\Response
     * @author 段誉
     * @date 2021/12/27 14:21
     */
    protected function success(string $msg = 'success', array $data = [], int $code = 1, int $show = 0): \support\Response
    {
        return JsonService::success($msg, $data, $code, $show);
    }

    /**
     * @notes 数据返回
     * @param $data
     * @return \support\Response
     * @author 段誉
     * @date 2021/12/27 14:21\
     */
    protected function data($data): \support\Response
    {
        return JsonService::data($data);
    }



    /**
     * @notes 操作失败
     * @param string $msg
     * @param array $data
     * @param int $code
     * @param int $show
     * @return \support\Response
     * @author 段誉
     * @date 2021/12/27 14:21
     */
    protected function fail(string $msg = 'fail', array $data = [], int $code = 0, int $show = 1): \support\Response
    {
        return JsonService::fail($msg, $data, $code, $show);
    }

    /**
     * @notes 是否免登录验证
     * @return bool
     * @author 段誉
     * @date 2021/12/27 14:21
     */
//    public function isNotNeedLogin() : bool
//    {
//        $notNeedLogin = $this->notNeedLogin;
//        if (empty($notNeedLogin)) {
//            return false;
//        }
//        $action = $this->request->action();
//
//        if (!in_array(trim($action), $notNeedLogin)) {
//            return false;
//        }
//        return true;
//    }



}