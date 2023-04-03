<?php
/*
 * 用户属性示例
 */
require_once __DIR__ . '/../autoload.php';
$config = require_once 'config.php';

use Easemob\Auth;
use Easemob\UserMetadata;

// 初始化授权对象，环信 token 初始化
$easemob = $config['easemob'];
$auth = new Auth($easemob['app_key'], $easemob['client_id'], $easemob['client_secret']);

// 设置 REST 域名，沙箱环境使用，不是沙箱环境会自动获取
if (isset($easemob['api_uri']) && $easemob['api_uri']) {
    $auth->setApiUri($easemob['api_uri']);
}

// 实例化对象
$metadata = new UserMetadata($auth);

echo '<pre>';


/* 
// 获取用户属性
var_dump($metadata->getMetadataFromUser('user3'));
 */


/* 
// 设置用户属性
var_dump($metadata->setMetadataToUser('user3', array('avatar' => 'http://www.easemob.com/avatar2.png', 'nickname' => 'userthree')));
var_dump($metadata->setMetadataToUser('user3', array('userasd' => 'sdf', 'userdfg' => 'fgh')));
var_dump($metadata->setMetadataToUser('user4', array('avatar' => 'http://www.easemob.com/avatar4.png', 'nickname' => 'userfour')));
var_dump($metadata->setMetadataToUser('user4', array('nickname' => 'userfive', 'age' => 20)));
 */


/* 
// 批量获取用户属性
var_dump($metadata->batchGetMetadataFromUser(array('user3', 'user4'), array('avatar', 'nickname', 'age', 'sex', 'asd')));
 */


/* 
// 获取用户属性总量大小
var_dump($metadata->getUsage());
 */

 
/* 
// 删除用户属性
var_dump($metadata->getMetadataFromUser('user3'));
var_dump($metadata->deleteMetadataFromUser('user3'));
var_dump($metadata->getMetadataFromUser('user3'));
 */
