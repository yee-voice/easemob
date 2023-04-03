<?php
require_once __DIR__ . '/../autoload.php';
$config = require_once 'config.php';

use Easemob\Auth;
use Easemob\User;
use Easemob\Cache\RedisCache;
use Easemob\Cache\FileCache;

// 初始化授权对象
$easemob = $config['easemob'];
$auth = new Auth($easemob['app_key'], $easemob['client_id'], $easemob['client_secret']);
// 连接到redis
$redis = new \Redis();
$redis->connect('localhost', '6379');
$redis->auth('ydBjIffJaUzvtwzb');

// 初始化redis对象
$redisCache = RedisCache::getInstance($redis);

// $fileCache = new FileCache();
// 设置需要使用的缓存
$auth->setCache($redisCache);

// 设置 REST 域名，沙箱环境使用，不是沙箱环境会自动获取
if (isset($easemob['api_uri']) && $easemob['api_uri']) {
    $auth->setApiUri($easemob['api_uri']);
}

$user = new User($auth);

echo '<pre>';

// var_dump($user->get('user2'));

// 注册单个用户
// $data = array(
//     'username' => 'user2',
//     'password' => 'user1',
// );
// var_dump($user->create($data));

$result = $user->token(array('grant_type' => 'inherit', 'username' => 'online_a1', 'autoCreateUser' => true, 'ttl' => 0));
print_r($result);
// $user->token(array('grant_type'=> 'password', 'username'=>'user1', 'password'=>'user1', 'ttl'=>0));



/* 
// 批量注册用户
$data = array();
for ($i = 2; $i< 21; $i++) {
$data[] = array(
'username' => 'user' . $i,
'password' => 'user' . $i
);
}
var_dump($user->create($data));
*/


/* 
// 获取单个用户
var_dump($user->get('user1'));
*/


/* 
// 批量获取用户
$data = $user->listUsers(2);
var_dump($data);
$data = $user->listUsers(2, 'ZGNiMjRmNGY1YjczYjlhYTNkYjk1MDY2YmEyNzFmODQ6aW06dXNlcjoxMTE1MjEwOTE1MTkzMjc3I2RlbW86OTU5MDkzMjc2ODMxNTc0MjMz');
var_dump($data);
// 批量获取被封禁的用户
$data = $user->listUsers(2, '', false);
var_dump($data);
*/


/* 
// 删除单个用户
var_dump($user->delete('user18'));
*/


/* 
// 批量删除用户
var_dump($user->batchDelete(2));
*/


/* 
// 修改用户密码
var_dump($user->updateUserPassword('user1', 'userOne'));
*/


/* 
// 获取用户在线状态
// true: 在线；false: 离线；
var_dump($user->isUserOnline('user1'));
*/


/* 
// 批量获取用户在线状态
// true: 在线；false: 离线；
var_dump($user->isUsersOnline(array('user1', 'user2')));
*/


/* 
// 强制下线
var_dump($user->forceLogoutAllDevices('user1'));
*/