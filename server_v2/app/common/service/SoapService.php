<?php

namespace app\common\service;

class SoapService
{
    private  $client = null;

    /**
     * 消息
     */
    public  $msg = '';

    /**
     * 初始化
     */
    public function __construct(){
        try {
            $config = [
                'location' => "http://" . \config('app.soap_hostname') . ':' . \config('app.soap_port'),
                'uri' => 'urn:AC',
                'style' => SOAP_RPC,
                'login' => \config('app.soap_username'),
                'password' => \config('app.soap_password'),
            ];
            $this->client = new \SoapClient(null, $config);
             \support\Log::info(print_r($config, true));
        } catch (\SoapFault $e) {
             \support\Log::error($e->getMessage());
        }
    }

    /**
     * 物品邮箱
     * @param string $character_name
     * @param string $item_id
     * @param string $count
     * @return bool
     */
    public function item(string $character_name = '',int $item_id = 0,int $count = 1): bool
    {
        try {
            $command = "send items {$character_name} 物品补发 来自管理员的物品补发邮件 {$item_id}:{$count}";
            \support\Log::info("SOAP命令:".$command);
            $result = $this->client->executeCommand(new \SoapParam($command, 'command'));
            \support\Log::info($result);
            return true;
        } catch (\SoapFault $e) {
             \support\Log::error($e->getMessage());
            var_dump($e->getMessage());

            $this->msg = $e->getMessage();
            return false;
        }
    }



    /**
     * 金币
     * @param string $character_name
     * @param string $count
     * @return bool
     */
    public function gold(string $character_name = '',int $count = 1): bool
    {
        try {
            $command = "send money {$character_name} 金币补发 来自管理员的金币补发邮件 {$count}";
             \support\Log::notice("SOAP命令:".$command);
            $result = $this->client->executeCommand(new \SoapParam($command, 'command'));
             \support\Log::info($result);
            return true;
        } catch (\SoapFault $e) {
             \support\Log::error($e->getMessage());
            $this->msg = $e->getMessage();
            return false;
        }
    }

    /**
     * 封号
     */
    public function banAccount(string $character_name,string $ban_time,string $reason): bool
    {
        try {
            $banTime = strtotime($ban_time);
            $command = ".ban playeraccount $character_name {$banTime} {$reason}";
             \support\Log::notice("SOAP命令:".$command);
            $result = $this->client->executeCommand(new \SoapParam($command, 'command'));
             \support\Log::info($result);
            return true;
        } catch (\SoapFault $e) {
             \support\Log::error($e->getMessage());
            $this->msg = $e->getMessage();
            return false;
        }
    }

    /**
     * 公告 (实时)
     */
    public function notice(array $params){
        try {
            $command = ".announce {$params['content']}";
            if($params['mode'] === 1){
                $command = ".notify {$params['content']}";
            }
             \support\Log::notice("SOAP命令:".$command);
            $result = $this->client->executeCommand(new \SoapParam($command, 'command'));
             \support\Log::info($result);
            return true;
        } catch (\SoapFault $e) {
             \support\Log::error($e->getMessage());
            $this->msg = $e->getMessage();
            return false;
        }
    }

    /**
     * 设置GM等级
     */
    public function setLevel(array $params){
        try {
            $command = ".account set gmlevel {$params['account']} {$params['gm_level']} -1";
             \support\Log::notice("SOAP命令:".$command);
            $result = $this->client->executeCommand(new \SoapParam($command, 'command'));
             \support\Log::info($result);
            return true;
        } catch (\SoapFault $e) {
             \support\Log::error($e->getMessage());
            $this->msg = $e->getMessage();
            return false;
        }
    }



}