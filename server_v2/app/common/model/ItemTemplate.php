<?php

namespace app\common\model;

use think\Model;

class ItemTemplate extends Model
{
    protected $connection = 'world';

    protected static  $class = [
        0 => '消耗品',
        1 => '容器',
        2 => '武器',
        3 => '珠宝',
        4 => '盔甲',
        5 => '试剂',
        6 => '弹药',
        7 => '商业物品(材料)',
        8 => '普通(废弃)',
        9 => '食谱配方',
        10 => '金钱(废弃)',
        11 => '箭弹药袋',
        12 => '任务物品',
        13 => '钥匙',
        14 => '永久(做废)',
        15 => '其它',
        16 => '雕文',
    ];

    public function getClassAttr($value){
        return self::$class[$value];
    }


}