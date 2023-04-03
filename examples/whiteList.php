<?php
require_once __DIR__ . '/../autoload.php';
$config = require_once 'config.php';

use Easemob\Auth;
use Easemob\WhiteList;

// 初始化授权对象
$easemob = $config['easemob'];
$auth = new Auth($easemob['app_key'], $easemob['client_id'], $easemob['client_secret']);

// 设置 REST 域名，沙箱环境使用，不是沙箱环境会自动获取
if (isset($easemob['api_uri']) && $easemob['api_uri']) {
    $auth->setApiUri($easemob['api_uri']);
}

$whiteList = new WhiteList($auth);

echo '<pre>';


/* 
// 查询群组白名单
var_dump($whiteList->getGroupWhiteList('177627101986819'));
 */


/* 
var_dump($whiteList->getGroupWhiteList('177627101986819'));
// 添加单个用户至群组白名单
var_dump($whiteList->addUserToGroupWhiteList('177627101986819', 'user3'));
var_dump($whiteList->getGroupWhiteList('177627101986819'));
 */


/* 
var_dump($whiteList->getGroupWhiteList('177627101986819'));
// 批量添加用户至群组白名单
var_dump($whiteList->addUsersToGroupWhiteList('177627101986819', array('user4', 'user5')));
var_dump($whiteList->getGroupWhiteList('177627101986819'));
 */


/* 
var_dump($whiteList->getGroupWhiteList('177627101986819'));
// 将用户移除群组白名单
var_dump($whiteList->removeUsersFromGroupWhiteList('177627101986819', 'user3'));
var_dump($whiteList->getGroupWhiteList('177627101986819'));
// 将用户移除群组白名单
var_dump($whiteList->removeUsersFromGroupWhiteList('177627101986819', 'user4,user5'));
var_dump($whiteList->getGroupWhiteList('177627101986819'));
 */


/* 
// 查询聊天室白名单
var_dump($whiteList->getRoomWhiteList('177630783537155'));
 */


/* 
var_dump($whiteList->getRoomWhiteList('177630783537155'));
// 添加单个用户至聊天室白名单
var_dump($whiteList->addUserToRoomWhiteList('177630783537155', 'user2'));
var_dump($whiteList->getRoomWhiteList('177630783537155'));
 */


/* 
var_dump($whiteList->getRoomWhiteList('177630783537155'));
// 批量添加用户至聊天室白名单
var_dump($whiteList->addUsersToRoomWhiteList('177630783537155', array('user3', 'user4')));
var_dump($whiteList->getRoomWhiteList('177630783537155'));
 */


/* 
var_dump($whiteList->getRoomWhiteList('177630783537155'));
// 将用户移除聊天室白名单
var_dump($whiteList->removeUsersFromRoomWhiteList('177630783537155', 'user4'));
var_dump($whiteList->getRoomWhiteList('177630783537155'));
// 将用户移除聊天室白名单
var_dump($whiteList->removeUsersFromRoomWhiteList('177630783537155', 'user2,user3'));
var_dump($whiteList->getRoomWhiteList('177630783537155'));
 */
