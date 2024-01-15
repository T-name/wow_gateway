<?php

namespace app\controller;

use app\common\base\BaseApiController;
use app\common\logic\ApiLogic;
use app\common\model\Account;
use app\common\model\Characters;
use app\common\model\ItemTemplate;
use app\common\service\JsonService;
use app\common\service\SoapService;
use app\common\validate\ApiValidate;

class ApiController extends BaseApiController
{


    /** 数据概览
     * @return \support\Response
     * @throws \think\db\exception\DbException
     */
    public function overview(): \support\Response
    {
        $accountNum = Account::count("id");
        $charactersNum = Characters::count("guid");
        $onlineNum = Account::where('online',1)->count("id");
        $itemNum = ItemTemplate::count('entry');
        $data = [
            [
                'title' => '账户数量',
                'num'   =>  $accountNum,
                'type'  =>  1
            ],
            [
                'title' => '角色数量',
                'num'   =>  $charactersNum,
                'type'  =>  2
            ],
            [
                'title' => '在线人数',
                'num'   =>  $onlineNum,
                'type'  =>  3
            ],
            [
                'title' => '物品数量',
                'num'   =>  $itemNum,
                'type'  =>  4
            ]
        ];
        return  $this->success('success',$data);
    }


    /**
     * 物品补发
     * @return \support\Response
     */
    public function item(): \support\Response
    {
        $params = (new ApiValidate())->post()->goCheck('item');
        $soap = new SoapService();
        $res = $soap->item($params['character_name'],$params['item_id'],$params['count']);
        if($res){
            return  $this->success('补发成功',[],1,1);
        }
        return $this->fail($soap->msg);
    }

    /**
     * 金币
     * @return \support\Response
     */
    public function gold() : \support\Response
    {
        $params = (new ApiValidate())->post()->goCheck('gold');
        $soap = new SoapService();
        $res = $soap->gold($params['character_name'],$params['count']);
        if($res){
            return  $this->success('补发成功',[],1,1);
        }
        return $this->fail($soap->msg);
    }

    /**
     * 封号
     */
    public function banAccount(): \support\Response
    {
        $params = (new ApiValidate())->post()->goCheck('banAccount');
        $soap = new SoapService();
        $res = $soap->banAccount($params['character_name'],$params['ban_time'],$params['reason']);
        if($res){
            return  $this->success('封禁成功',[],1,1);
        }
        return $this->fail($soap->msg);
    }

    /**
     * 公告
     */
    public function notice(): \support\Response
    {
        $params = (new ApiValidate())->post()->goCheck('notice');
        $soap = new SoapService();
        $res = $soap->notice($params);
        if($res){
            return  $this->success('操作成功',[],1,1);
        }
        return $this->fail('操作失败');
    }


    /**
     * 补发
     */
    public function reissue(): \support\Response
    {
        $params = (new ApiValidate())->post()->goCheck('reissue');
        $res = ApiLogic::reissue($params);
        if($res === false){
            return $this->fail('补发失败');
        }
        return  $this->success('补发成功',[],1,1);

    }

    /**
     * 设置GM等级
     */
    public function setLevel(): \support\Response
    {
        $params = (new ApiValidate())->post()->goCheck('setLevel');
        $soap = new SoapService();
        $res = $soap->setLevel($params);
        if($res){
            return  $this->success('操作成功',[],1,1);
        }
        return $this->fail('操作失败');
    }


    /**
     * 账户列表
     */
    public function getAccountList(): \support\Response
    {
        $params = $this->request->get();
        $params['page_no'] = $params['page_no']??1;
        $params['page_size'] = $params['page_size']??15;
        return  $this->success('success',ApiLogic::getAccountList($params));
    }

    /**
     * 角色列表
     */
    public function getCharactersList(): \support\Response
    {
        $params = $this->request->get();
        $params['page_no'] = $params['page_no']??1;
        $params['page_size'] = $params['page_size']??15;
        return  $this->success('success',ApiLogic::getCharactersList($params));
    }



    /**
     * 物品列表
     */
    public function getItemList(): \support\Response
    {
        $params = $this->request->get();
        $params['page_no'] = $params['page_no']??1;
        $params['page_size'] = $params['page_size']??15;
        return  $this->success('success',ApiLogic::getItemList($params));
    }













}