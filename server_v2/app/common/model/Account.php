<?php

namespace app\common\model;

use think\Model;

class Account extends Model
{
    protected $connection = 'auth';

    protected $schema = [
        'salt' => 'binary',
        'verifier' => 'binary',
        'username' => 'varchar'
    ];


    /** 注册账号
     * @param string $username
     * @param string $password
     * @return Account
     * @throws \Exception
     */
    public static function register(string $username,string $password): Account
    {
        $salt = random_bytes(32);
        $account = new Account();
        $account->username = $username;
        $account->salt = $salt;
        $account->verifier = calculateSRP6Verifier($username,$password,$salt);
        $account->joindate = time();
        $account->save();
        return $account;
    }




}