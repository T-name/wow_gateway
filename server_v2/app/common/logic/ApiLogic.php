<?php

namespace app\common\logic;

use app\common\base\BaseLogic;
use app\common\model\Account;
use app\common\model\CharacterItem;
use app\common\model\Characters;
use app\common\model\ItemInstance;
use app\common\model\ItemTemplate;
use think\facade\Cache;

class ApiLogic extends BaseLogic
{

    /**
     * 补发
     */
    public static function reissue(array $params){
        $allowField = [];
        switch ($params['item_type']){
            case 1:
                $allowField = ['character_name','item_type','item_id','count','note'];
                break;
            case 2:
                $allowField = ['character_name','item_title','item_type','item_id','count','note'];
                break;
            case 4:
            case 5:
                $allowField = ['character_name','item_title','item_type','count','note'];
                break;
        }

        $characterItem = CharacterItem::create($params,$allowField);
        return !$characterItem->isEmpty();
    }

    /**
     * 公告（定时）
     */
//    public static function notice(array $params): bool
//    {
//        $notice_list_cache = Cache::getTagItems('notice_list_cache');
//        if($notice_list_cache){
//            Cache::tag('notice_list_cache')->set('notice_item_' . (count($notice_list_cache) + 1),$params,strtotime($params['exec_time']) - time());
//        }else{
//            Cache::tag('notice_list_cache')->set( 'notice_item_1',$params,strtotime($params['exec_time']) - time());
//        }
//        return true;
//    }

    /**
     * 公告（执行） think run  -p 8000
     */
//    public static function execNotice(array $params): bool
//    {
//        $store = Cache::store('file'); // 或者任何你使用的具体驱动名称
//        $notice_list_cache = $store->handler()->getTagItems('notice_list_cache');
//
//        dump($notice_list_cache);
//
//        exit();
//        foreach ($notice_list_cache as $key => $value){
//            dump($value->count);
//        }
//
//        exit();
//        return true;
//    }



    /**
     * 账户列表
     */
    public static function getAccountList(array $params){
        $query = Account::field('id,username,joindate');
        if(isset($params['keyword']) && $params['keyword'] !== ''){
            $query = $query->where('username','like',"%{$params['keyword']}%");
        }
        if(isset($params['is_online']) &&  $params['is_online'] === '1'){
            $query = $query->where('online',1);
        }
        return [
            'count' => $query->count(),
            'lists' => $query->order('id DESC')->page($params['page_no'], $params['page_size'])->select()->toArray(),
            'page_no' => $params['page_no'],
            'page_size' => $params['page_size'],
        ];
    }


    /**
     * 角色列表
     */
    public static function getCharactersList(array $params){
        $query = Characters::field('guid,account,name,money,creation_date');
        if(isset($params['keyword']) && $params['keyword'] !== ''){
            $query = $query->where('account|name','like',"%{$params['keyword']}%");
        }
        if(isset($params['account_id']) && $params['account_id'] !== ''){
            $query = $query->where('account',$params['account_id']);
        }
        return [
            'count' => $query->count(),
            'lists' => $query->order('guid DESC')->page($params['page_no'], $params['page_size'])->select()->toArray(),
            'page_no' => $params['page_no'],
            'page_size' => $params['page_size'],
        ];
    }

    /**
     * 物品列表
     */
    public static function getItemList(array $params){
        $query = ItemTemplate::field('entry,name,class');
        if(isset($params['keyword']) && $params['keyword'] !== ''){
            $query = $query->where('entry|name','like',"%{$params['keyword']}%");
        }
        $itemInstanceResults = [];
        if(isset($params['characters_id']) && $params['characters_id'] !== ''){
            $itemInstanceResults = ItemInstance::where('owner_guid',$params['characters_id'])->field('itemEntry,count')->select()->toArray();
            $entryResults = $itemInstanceResults?array_column($itemInstanceResults,'itemEntry'):[];
            $query = $query->where('entry','in',$entryResults);
        }
        $lists = $query->order('entry DESC')->page($params['page_no'], $params['page_size'])->select()->toArray();

        foreach ($lists as &$list){
            foreach ($itemInstanceResults as $result){
                if($list['entry'] === $result['itemEntry']){
                    $list['count'] = $result['count'];
                }
            }
        }
        return [
            'count' => $query->count(),
            'lists' => $lists,
            'page_no' => $params['page_no'],
            'page_size' => $params['page_size'],
        ];
    }


}