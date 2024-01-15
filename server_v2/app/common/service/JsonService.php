<?php

declare(strict_types=1);


namespace app\common\service;

use support\exception\BusinessException;
use support\Response;

class JsonService
{

    /**
     * @notes 接口操作成功，返回信息
     * @param string $msg
     * @param array $data
     * @param int $code
     * @param int $show
     * @return Response
     * @author 段誉
     * @date 2021/12/24 18:28
     */
    public static function success(string $msg = 'success', array $data = [], int $code = 1, int $show = 1): Response
    {
        return self::result($code, $show, $msg, $data);
    }


    /**
     * @notes 接口操作失败，返回信息
     * @param string $msg
     * @param array $data
     * @param int $code
     * @param int $show
     * @return Response
     * @author 段誉
     * @date 2021/12/24 18:28
     */
    public static function fail(string $msg = 'fail', array $data = [], int $code = 0, int $show = 1): Response
    {
        return self::result($code, $show, $msg, $data);
    }


    /**
     * @notes 接口返回数据
     * @param $data
     * @return Response
     * @author 段誉
     * @date 2021/12/24 18:29
     */
    public static function data($data): Response
    {
        return self::success('', $data, 1, 0);
    }


    /**
     * @notes 接口返回信息
     * @param int $code
     * @param int $show
     * @param string $msg
     * @param array $data
     * @param int $httpStatus
     * @return Response
     * @author 段誉
     * @date 2021/12/24 18:29
     */
    private static function result(int $code, int $show, string $msg = 'OK', array $data = [], int $httpStatus = 200): Response
    {
        $result = compact('code', 'show', 'msg', 'data');
        return json($result, $httpStatus);
    }


    /**
     * @notes 抛出异常json
     * @param string $msg
     * @throws BusinessException
     */
    public static function throw(string $msg = 'fail',$httpStatus = 500)
    {
        throw new BusinessException($msg,$httpStatus);
    }
}