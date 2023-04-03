<?php
/*
 * 用户示例
 */
require_once __DIR__ . '/../autoload.php';
$config = require_once 'config.php';

use Easemob\Auth;
use Easemob\User;
use Easemob\Agora\ServiceRtc;


// 初始化授权对象，环信 token 初始化
$easemob = $config['easemob'];
$auth = new Auth($easemob['app_key'], $easemob['client_id'], $easemob['client_secret']);

// 设置 REST 域名，沙箱环境使用，不是沙箱环境会自动获取
if (isset($easemob['api_uri']) && $easemob['api_uri']) {
    $auth->setApiUri($easemob['api_uri']);
}


/* 
// 初始化授权对象，声网 token 初始化
$agora = $config['agora'];
$auth = new Auth($agora['app_key'], $agora['app_id'], $agora['app_certificate'], true, $agora['expire_time']);
$auth->setApiUri('http://a41.easemob.com');
 */


echo '<pre>';


/* 
// 生成 Easemob userToken
var_dump($auth->getUserToken('user4', 'user4'));
 */


/* 
// 生成仅含 AgoraChat 权限的 Agora userToken
var_dump($auth->getUserToken('bb46ab60-5c07-11ec-8cc6-b5d98ea414b4', null));
 */


/* 
// 生成包含 AgoraChat 权限和 AgoraRTC (JOIN_CHANNEL) 权限的 Agora userToken

// grant rtc privileges
$serviceRtc = new ServiceRtc('7d72365eb983485397e3e3f9d460bdda', '2882341273');
$serviceRtc->addPrivilege($serviceRtc::PRIVILEGE_JOIN_CHANNEL, 600);

$configuration = array(
    $serviceRtc,
);

var_dump($auth->getUserToken('bb46ab60-5c07-11ec-8cc6-b5d98ea414b4', null, 600, $configuration));
 */
