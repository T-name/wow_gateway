<?php

namespace app\controller;

use app\common\base\BaseApiController;
use app\common\model\Account;
use app\common\model\AccountAccess;
use support\Response;
use think\facade\Db;


class IndexController extends BaseApiController
{

    public  $notNeedLogin = ['configuration'];


    /**
     * 心跳
     * @return Response
     */
    public function index(): Response
    {
        return $this->success("状态正常");
    }

    /**
     * 配置
     */
    public function configuration(){
        $project_path = dirname(base_path());
        $lockPath = "{$project_path}./install.lock";
        if($this->request->isPost()){
            if(file_exists($lockPath)){
               return  $this->fail('可能已经安装过本系统了，请删除目录下面的install.lock文件重新安装');
            }
            $params = $this->request->post();

            $auth = [
                // 数据库类型
                'type'            =>  'mysql',
                // 服务器地址
                'hostname'    => $params['auth_hostname'],
                // 数据库名
                'database'    => $params['auth_database'],
                // 数据库用户名
                'username'    => $params['auth_username'],
                // 数据库密码
                'password'    => $params['auth_password'],
                // 端口
                'hostport'    => $params['auth_port'],
                //默认编码
                'charset'     => 'utf8',
            ];
           $connect =  checkDbConnect($auth);
           if($connect !== true){
               return $this->fail("auth库连接失败 {$connect}");
           }

           $characters = [
                // 数据库类型
                'type'            =>  'mysql',
                // 服务器地址
                'hostname'    => $params['characters_hostname'],
                // 数据库名
                'database'    => $params['characters_database'],
                // 数据库用户名
                'username'    => $params['characters_username'],
                // 数据库密码
                'password'    => $params['characters_password'],
                // 端口
                'hostport'    => $params['characters_port'],
                //默认编码
                'charset'     => 'utf8',
            ];
            $connect =  checkDbConnect($characters);
            if($connect !== true){
                return $this->fail("characters库连接失败 {$connect}");
            }

            $world = [
                // 数据库类型
                'type'            =>  'mysql',
                // 服务器地址
                'hostname'    => $params['world_hostname'],
                // 数据库名
                'database'    => $params['world_database'],
                // 数据库用户名
                'username'    => $params['world_username'],
                // 数据库密码
                'password'    => $params['world_password'],
                // 端口
                'hostport'    => $params['world_port'],
                //默认编码
                'charset'     => 'utf8',
            ];
            $connect =  checkDbConnect($world);
            if($connect !== true){
                return $this->fail("world库连接失败 {$connect}");
            }

            //设置连接配置
            Db::setConfig([
                // 数据库连接信息
                'connections' => [
                    'auth' => $auth,
                    'characters' => $characters,
                    'world' => $world,
                ],
            ]);

            if($params['soap_is_open'] === 1){
                $connection = @fsockopen($params['soap_hostname'], $params['soap_port'], $errno, $errstr, 10);
                if (!is_resource($connection)) {
                    return $this->fail("soap设置不正确，无法连接到服务，错误: [$errno]");
                }
                fclose($connection); //关闭连接
            }

            $account = Account::where('username',$params['soap_username'])->find();
            if(!$account){
                $account = Account::register( $params['soap_username'],$params['soap_password']);
            }

            $accountAccess =  AccountAccess::where('id',$account->id)->find();
            if(!$accountAccess){
                AccountAccess::create([
                    'id' => $account->id,
                    'gmlevel' => 3,
                    'RealmID' => -1,
                ]);
            }

            unset($params['soap_is_open']);
            //取服务器IP
            $params['app_ip'] = @file_get_contents('http://ipecho.net/plain');

            $envFilePath = base_path().'\.env';
            $content = '';
            $lastPrefix = '';

            foreach ($params as $index => $value) {
                @list($prefix, $key) = explode('_', $index);
                $prefix = strtoupper($prefix);
                $key = strtoupper($key);

                if ($prefix != $lastPrefix && $key != null) {
                    if ($lastPrefix != '')
                        $content .= "\n";
                    $lastPrefix = $prefix;
                }

                $content .= $prefix.'_';
                if ($prefix != $lastPrefix && $key == null) {
                    $content .= "$index = \"$value\"\n";
                } else {
                    $content .= "$key = \"$value\"\n";
                }
            }
            if (!empty($content)) {
                file_put_contents($envFilePath, $content);
            }

            touch($lockPath);
            return $this->success('设置成功');
        }
        if(file_exists($lockPath)){
            return '可能已经安装过本系统了，请删除目录下面的install.lock文件重新安装';
        }

        return view('index/configuration',[]);
    }

}