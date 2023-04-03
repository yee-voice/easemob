<?php
/*
 * 联系人示例
 */
require_once __DIR__ . '/../autoload.php';
$config = require_once 'config.php';

use Easemob\Auth;
use Easemob\Contact;

// 初始化授权对象，环信 token 初始化
$easemob = $config['easemob'];
$auth = new Auth($easemob['app_key'], $easemob['client_id'], $easemob['client_secret']);

// 设置 REST 域名，沙箱环境使用，不是沙箱环境会自动获取
if (isset($easemob['api_uri']) && $easemob['api_uri']) {
    $auth->setApiUri($easemob['api_uri']);
}

// 实例化对象
$contact = new Contact($auth);

echo '<pre>';


/* 
var_dump($contact->get('user3'));
// 添加联系人
var_dump($contact->add('user3', 'user4'));
var_dump($contact->add('user3', 'user5'));
var_dump($contact->get('user3'));
 */


/* 
// 获取联系人列表
var_dump($contact->get('user1'));
 */


/* 
var_dump($contact->get('user3'));
// 移除联系人
var_dump($contact->remove('user3', 'user4'));
var_dump($contact->get('user3'));
 */
