<?php
/**
 * 方法需要php开启 gmp扩展  ,  php版本推荐使用 7.3+
 * srp6 加密
 * @param string $username  账户
 * @param string $password  密码
 * @param string $salt  盐
 * @return string
 */
function calculateSRP6Verifier(string $username,string $password,string $salt): string
{
    // 常数
    $g = gmp_init(7);
    $N = gmp_init('894B645E89E1535BBDAD5B8B290650530801B18EBFBF5E8FAB3C82872A3E9BB7', 16);

    // 计算第一个哈希值
    $h1 = sha1(strtoupper($username . ':' . $password), TRUE);

    // 计算第二个哈希值
    $h2 = sha1($salt.$h1, TRUE);

    // 转整数
    $h2 = gmp_import($h2, 1, GMP_LSW_FIRST);

    // g^h2 mod N
    $verifier = gmp_powm($g, $h2, $N);

    // 转换回字节数组（小端）
    $verifier = gmp_export($verifier, 1, GMP_LSW_FIRST);

    // 填充到 32 字节，在字节序中，零位于末尾！
    $verifier = str_pad($verifier, 32, chr(0), STR_PAD_RIGHT);

    //返回结果
    return $verifier;
}


/**
 * 检测mysql是否连接正常
 * @param string $hostname
 * @param string $database
 * @param string $username
 * @param string $password
 * @return bool|string
 */
function checkDbConnect(array $params = []) : bool|string
{
    try {
        new PDO("mysql:host={$params['hostname']};dbname={$params['database']};charset=utf8", $params['username'], $params['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return true;
    }catch (PDOException $PDOException){
        return $PDOException->getMessage();
    }
}
