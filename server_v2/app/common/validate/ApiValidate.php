<?php

namespace app\common\validate;

use app\common\base\BaseValidate;
use app\common\model\Account;
use app\common\model\Characters;

class ApiValidate extends BaseValidate
{

    /**
     * 设置校验规则
     * @var string[]
     */
    protected $rule = [
        'character_name' => 'require|checkCharacterName',
        'item_id' => 'require|number',
        'count' => 'require|number',
        'reason' => 'require',
        'ban_time' => 'require',
        'content' => 'require',
        'exec_time' => 'require|date',
        'mode' => 'require|in:1,2',
        'item_title' => 'require',
        'item_type' => 'require',
        'note' => 'require',
        'account' => 'require|checkAccount',
        'gm_level' => 'require|in:1,2,3,4'
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'character_name' => '角色名称',
        'item_id' => '物品编号',
        'count' => '数量',
        'reason' => '封禁原因',
        'ban_time' => '封禁时间',
        'content' => '公告',
        'exec_time' => '执行时间',
        'mode' => '模式',
        'item_title' => '物品名称',
        'item_type' => '物品类型',
        'account' => '账户',
        'gm_level' => 'gm等级'
    ];

    protected $message  =   [
        'character_name.require' => '角色名称不能为空',
        'item_id.require' => '物品编号不能为空',
        'item_id.number' => '物品编号必须为数字',
        'count.require' => '数量不能为空',
        'count.number' => '数量必须为数字',
        'reason.require' => '封禁原因不能为空',
        'ban_time.require' => '封禁时间不能为空',
        'content.require' => '公告不能为空',
        'exec_time.require' => '执行时间不能为空',
        'mode.require' => '模式不能为空',
        'mode.in' => '模式不正确',
        'item_title.require' => '物品名称不能为空',
        'item_type.require' => '物品类型不能为空',
        'note.require' => '备注不能为空',
        'account.require' => '账户不能为空',
        'gm_level.require' => 'GM等级不能为空',
        'gm_level.in' => 'GM等级不正确',
    ];



    public function checkCharacterName($scene, $rule, $data){
        $characters = Characters::where('name',$data['character_name'])->field('name')->findOrEmpty();
        if($characters->isEmpty()){
            return '角色名称不存在';
        }
        return true;
    }

    public function checkAccount($scene, $rule, $data){
        $characters = Account::where('username',$data['account'])->field('username')->findOrEmpty();
        if($characters->isEmpty()){
            return '账户不存在';
        }
        return true;
    }




    /**
     * @notes 补发物品场景
     * @return ApiValidate
     * @author obama
     * @date 2023/12/03 16:24
     */
    public function sceneItem()
    {
        return $this->only(['character_name','item_id','count']);
    }

    /**
     * @notes 补发金币场景
     * @return ApiValidate
     * @author obama
     * @date 2023/12/03 16:24
     */
    public function sceneGold()
    {
        return $this->only(['character_name','count']);
    }

    /**
     * @notes 封号场景
     * @return ApiValidate
     * @author obama
     * @date 2023/12/03 16:24
     */
    public function sceneBanAccount()
    {
        return $this->only(['character_name','reason','ban_time']);
    }

    /**
     * @notes 公告场景
     * @return ApiValidate
     * @author obama
     * @date 2023/12/03 16:24
     */
    public function sceneNotice()
    {
//        return $this->only(['mode','content','count','exec_time']);
        return $this->only(['mode','content']);
    }


    /**
     * @notes 补发物品场景
     * @return ApiValidate
     * @author obama
     * @date 2023/12/03 16:24
     */
    public function sceneReissue()
    {
        return $this->only(['character_name','item_type','count']);
    }


    /**
     * @notes 设置GM等级
     * @return ApiValidate
     * @author obama
     * @date 2023/12/03 16:24
     */
    public function sceneSetLevel()
    {
        return $this->only(['account','gm_level']);
    }


}