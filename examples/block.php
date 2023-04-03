<?php
/*
 * 用于限制访问(将用户加入黑名单、群组/聊天室禁言等)
 */
require_once __DIR__ . '/../autoload.php';
$config = require_once 'config.php';

use Easemob\Auth;
use Easemob\User;
use Easemob\Block;

// 初始化授权对象，环信 token 初始化
$easemob = $config['easemob'];
$auth = new Auth($easemob['app_key'], $easemob['client_id'], $easemob['client_secret']);

// 设置 REST 域名，沙箱环境使用，不是沙箱环境会自动获取
if (isset($easemob['api_uri']) && $easemob['api_uri']) {
    $auth->setApiUri($easemob['api_uri']);
}

// 实例化对象
$block = new Block($auth);
$user = new User($auth);

echo '<pre>';


/* 
// 获取用户黑名单
var_dump($block->getUsersBlockedFromSendMsgToUser('user3'));
 */


/* 
// 添加用户黑名单
var_dump($block->getUsersBlockedFromSendMsgToUser('user1'));
var_dump($block->blockUserSendMsgToUser('user1', array('user2', 'user3')));
var_dump($block->getUsersBlockedFromSendMsgToUser('user1'));
 */


/* 
var_dump($block->getUsersBlockedFromSendMsgToUser('user1'));
// 移除用户黑名单
var_dump($block->unblockUserSendMsgToUser('user1', 'user3'));
var_dump($block->getUsersBlockedFromSendMsgToUser('user1'));
 */


/* 
var_dump($user->get('user1'));
// 用户账号禁用
var_dump($block->blockUserLogin('user1'));
var_dump($user->get('user1'));
// 用户账号解禁
var_dump($block->unblockUserLogin('user1'));
var_dump($user->get('user1'));
 */


/* 
// 设置用户全局禁言
var_dump($block->blockUserSendMsg('user3'));
 */


/* 
// 取消用户全局禁言
var_dump($block->unblockUserSendMsg('user3'));
 */


/* 
// 查询单个帐号全局禁言
var_dump($block->getUserBlocked('user3'));
 */


/* 
// 查询APPKEY的用户禁言
var_dump($block->getAppBlocked());
 */


// Group

 /* 
// 查询群组黑名单
var_dump($block->getUsersBlockedJoinGroup('177627064238081'));
 */


/* 
var_dump($block->getUsersBlockedJoinGroup('177627064238081'));
// 添加单个用户至群组黑名单
var_dump($block->blockUserJoinGroup('177627064238081', 'user4'));
var_dump($block->getUsersBlockedJoinGroup('177627064238081'));
 */


/* 
var_dump($block->getUsersBlockedJoinGroup('177627064238081'));
// 批量添加用户至群组黑名单
var_dump($block->blockUsersJoinGroup('177627064238081', array('user1', 'user3')));
var_dump($block->getUsersBlockedJoinGroup('177627064238081'));
 */


/* 
var_dump($block->getUsersBlockedJoinGroup('177627064238081'));
// 从群组黑名单移除单个用户
var_dump($block->unblockUserJoinGroup('177627064238081', 'user4'));
var_dump($block->getUsersBlockedJoinGroup('177627064238081'));
 */


/* 
// 批量从群组黑名单移除用户
var_dump($block->getUsersBlockedJoinGroup('177627064238081'));
var_dump($block->unblockUsersJoinGroup('177627064238081', array('user1', 'user3')));
var_dump($block->getUsersBlockedJoinGroup('177627064238081'));
 */


/* 
var_dump($block->getUsersBlockedSendMsgToGroup('177627064238081'));
// 添加群禁言
var_dump($block->blockUserSendMsgToGroup('177627064238081', array('user4')));
var_dump($block->getUsersBlockedSendMsgToGroup('177627064238081'));
var_dump($block->blockUserSendMsgToGroup('177627064238081', array('user5', 'user6')));
var_dump($block->getUsersBlockedSendMsgToGroup('177627064238081'));
 */


/* 
var_dump($block->getUsersBlockedSendMsgToGroup('177627064238081'));
// 移除群禁言
var_dump($block->unblockUserSendMsgToGroup('177627064238081', array('user4')));
var_dump($block->getUsersBlockedSendMsgToGroup('177627064238081'));
var_dump($block->unblockUserSendMsgToGroup('177627064238081', array('user5', 'user6')));
var_dump($block->getUsersBlockedSendMsgToGroup('177627064238081'));
 */

 
/* 
// 获取群禁言列表
var_dump($block->getUsersBlockedSendMsgToGroup('177627064238081'));
 */


/* 
// 禁言群组全体成员
var_dump($block->blockAllUserSendMsgToGroup('177627064238081'));
 */


/* 
// 解除群组全员禁言
var_dump($block->unblockAllUserSendMsgToGroup('177627064238081'));
 */


// Room

/*  
// 查询聊天室黑名单
var_dump($block->getUsersBlockedJoinRoom('177630845403137'));
 */


/* 
var_dump($block->getUsersBlockedJoinRoom('177630845403137'));
// 添加单个用户至聊天室黑名单
var_dump($block->blockUserJoinRoom('177630845403137', 'user11'));
var_dump($block->getUsersBlockedJoinRoom('177630845403137'));
 */


/* 
var_dump($block->getUsersBlockedJoinRoom('177630845403137'));
// 批量添加用户至聊天室黑名单
var_dump($block->blockUsersJoinRoom('177630845403137', array('user12', 'user13')));
var_dump($block->getUsersBlockedJoinRoom('177630845403137'));
 */


/* 
var_dump($block->getUsersBlockedJoinRoom('177630845403137'));
// 从聊天室黑名单移除单个用户
var_dump($block->unblockUserJoinRoom('177630845403137', 'user11'));
var_dump($block->getUsersBlockedJoinRoom('177630845403137'));
 */


/* 
// 批量从聊天室黑名单移除用户
var_dump($block->getUsersBlockedJoinRoom('177630845403137'));
var_dump($block->unblockUsersJoinRoom('177630845403137', array('user12', 'user13')));
var_dump($block->getUsersBlockedJoinRoom('177630845403137'));
 */


/* 
var_dump($block->getUsersBlockedSendMsgToRoom('177630783537155'));
// 添加聊天室禁言
var_dump($block->blockUserSendMsgToRoom('177630783537155', array('user4')));
var_dump($block->getUsersBlockedSendMsgToRoom('177630783537155'));
var_dump($block->blockUserSendMsgToRoom('177630783537155', array('user2', 'user3')));
var_dump($block->getUsersBlockedSendMsgToRoom('177630783537155'));
 */


/* 
var_dump($block->getUsersBlockedSendMsgToRoom('177630783537155'));
// 移除聊天室禁言
var_dump($block->unblockUserSendMsgToRoom('177630783537155', array('user4')));
var_dump($block->getUsersBlockedSendMsgToRoom('177630783537155'));
var_dump($block->unblockUserSendMsgToRoom('177630783537155', array('user2', 'user3')));
var_dump($block->getUsersBlockedSendMsgToRoom('177630783537155'));
 */

 
/* 
// 获取聊天室禁言列表
var_dump($block->getUsersBlockedSendMsgToRoom('177630783537155'));
 */


/* 
// 禁言聊天室全体成员
var_dump($block->blockAllUserSendMsgToRoom('177630783537155'));
 */


/* 
// 解除聊天室全员禁言
var_dump($block->unblockAllUserSendMsgToRoom('177630783537155'));
 */
