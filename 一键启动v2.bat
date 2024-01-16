@echo off
chcp 65001
echo =====================================

echo 若未自动打开设置页面 请手动在浏览器中打开下面地址进行设置

echo 配置地址： http://localhost:9996/index/configuration

echo 服务器请开放 9996 端口
echo 需要重新配置则请删除目录下的install.lock文件

echo =====================================

//设置环境变量
set "PATH=P:\gateway_php_v2\php7.2.9nts;%PATH%"



//启动网关
if exist install.lock (
    echo 网关已经安装成功！！
) else (
    start http://localhost:9996/index/configuration
)

.\php7.2.9nts\php.exe .\server_v2\windows.php



pause
