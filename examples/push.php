<?php
/*
 * 推送 API
 */
require_once __DIR__ . '/../autoload.php';
$config = require_once 'config.php';

use Easemob\Auth;
use Easemob\User;
use Easemob\Push;

// 初始化授权对象，环信 token 初始化
$easemob = $config['easemob'];
$auth = new Auth($easemob['app_key'], $easemob['client_id'], $easemob['client_secret']);

// 设置 REST 域名，沙箱环境使用，不是沙箱环境会自动获取
if (isset($easemob['api_uri']) && $easemob['api_uri']) {
    $auth->setApiUri($easemob['api_uri']);
}

// 实例化对象
$user = new User($auth);
$push = new Push($auth);

echo '<pre>';


/* 
var_dump($user->get('user3'));
// 设置用户推送昵称
var_dump($push->updateUserNickname('user3', 'userthree'));
var_dump($user->get('user3'));
 */


/* 
// 设置推送消息展示方式
var_dump($user->get('user3'));
var_dump($push->setNotificationDisplayStyle('user3', 0));
var_dump($user->get('user3'));
var_dump($push->setNotificationDisplayStyle('user3'));
var_dump($user->get('user3'));
 */


/* 
// 设置免打扰
var_dump($user->get('user3'));
// 开启免打扰，设置免打扰时间
var_dump($push->openNotificationNoDisturbing('user3', 10, 19));
var_dump($user->get('user3'));
// 取消免打扰
var_dump($push->closeNotificationNoDisturbing('user3'));
var_dump($user->get('user3'));
 */
