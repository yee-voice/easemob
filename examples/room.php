<?php
/*
 * 聊天室管理示例
 */
require_once __DIR__ . '/../autoload.php';
$config = require_once 'config.php';

use Easemob\Auth;
use Easemob\Room;

// 初始化授权对象，环信 token 初始化
$easemob = $config['easemob'];
$auth = new Auth($easemob['app_key'], $easemob['client_id'], $easemob['client_secret']);

// 设置 REST 域名，沙箱环境使用，不是沙箱环境会自动获取
if (isset($easemob['api_uri']) && $easemob['api_uri']) {
    $auth->setApiUri($easemob['api_uri']);
}

// 实例化对象
$room = new Room($auth);

echo '<pre>';


/* 
// 获取 APP 中所有的聊天室（分页）
var_dump($room->listRooms());
var_dump($room->listRooms(10, 'ZGNiMjRmNGY1YjczYjlhYTNkYjk1MDY2YmEyNzFmODQ6aW06Y2hhdHJvb206MTExNTIxMDkxNTE5MzI3NyNkZW1vOjI'));
 */


/* 
// 获取 APP 中所有的聊天室
var_dump($room->listAllRooms());
 */


/*  
// 获取用户加入的聊天室（分页）
var_dump($room->listRoomsUserJoined('user1'));
var_dump($room->listRoomsUserJoined('user1', 10, 2));
 */


/* 
// 获取用户加入的聊天室
var_dump($room->listAllRoomsUserJoined('user1'));
 */


/* 
// 创建聊天室
var_dump($room->createRoom('测试聊天室1', '测试聊天室描述1', 'user1', array('user2', 'user3')));
// 177630783537155
 */


/* 
for ($i = 0; $i < 20; $i++) {
    var_dump($room->createRoom('测试聊天室' . $i, '测试聊天室描述' . $i, 'user1', array('user2', 'user3')));
}
// string(15) "177630845403137"
// string(15) "177630845403138"
// string(15) "177630846451713"
// string(15) "177630846451714"
// string(15) "177630846451716"
// string(15) "177630846451717"
// string(15) "177630846451719"
// string(15) "177630846451720"
// string(15) "177630847500289"
// string(15) "177630847500290"
// string(15) "177630847500292"
// string(15) "177630847500293"
// string(15) "177630847500294"
// string(15) "177630848548865"
// string(15) "177630848548866"
// string(15) "177630848548867"
// string(15) "177630848548868"
// string(15) "177630849597441"
// string(15) "177630849597442"
// string(15) "177630849597443"
 */


/* 
// 获取聊天室详情
var_dump($room->getRoom('177630845403137'));
var_dump($room->getRoom('177630783537155,177630845403137'));
 */


/* 
var_dump($room->getRoom('177630783537155'));
// 修改聊天室信息
$data = array(
    'room_id' => '177630783537155',
    'name' => '测试聊天室1x',
    'description' => '测试聊天室描述1x',
    'maxusers' => 500,
);
var_dump($room->updateRoom($data));
var_dump($room->getRoom('177630783537155'));
 */


/* 
// 删除聊天室
var_dump($room->getRoom('177630849597443'));
var_dump($room->destroyRoom('177630849597443'));
var_dump($room->getRoom('177630849597443'));
 */


/* 
// 获取聊天室公告
var_dump($room->getRoomAnnouncement('177630783537155'));
 */


/* 
var_dump($room->getRoomAnnouncement('177630783537155'));
// 修改聊天室公告
var_dump($room->updateRoomAnnouncement('177630783537155', '聊天室测试公告'));
var_dump($room->getRoomAnnouncement('177630783537155'));
 */


/* 
// 分页获取聊天室成员
var_dump($room->listRoomMembers('177630783537155', 1));    // 默认第一页 10 条
var_dump($room->listRoomMembers('177630783537155', 1, 2)); // 第二页 10 条
 */


/* 
// 获取聊天室所有成员
var_dump($room->listRoomMembersAll('177630783537155'));
 */


/* 
var_dump($room->listRoomMembersAll('177630783537155'));
// 添加单个聊天室成员
var_dump($room->addRoomMember('177630783537155', 'user10'));
var_dump($room->listRoomMembersAll('177630783537155'));
 */


/* 
var_dump($room->listRoomMembersAll('177630783537155'));
// 批量添加聊天室成员
var_dump($room->addRoomMembers('177630783537155', array('user5', 'user6', 'user7')));
var_dump($room->listRoomMembersAll('177630783537155'));
 */


/* 
var_dump($room->listRoomMembersAll('177630783537155'));
// 删除单个聊天室成员
var_dump($room->removeRoomMember('177630783537155', 'user10'));
var_dump($room->listRoomMembersAll('177630783537155'));
 */


/* 
// 批量删除聊天室成员
var_dump($room->listRoomMembersAll('177630783537155'));
var_dump($room->removeRoomMembers('177630783537155', array('user11', 'user12', 'user13')));
var_dump($room->listRoomMembersAll('177630783537155'));
 */


/* 
// 获取聊天室管理员列表
var_dump($room->listRoomAdminsAll('174712753815556'));
 */


/* 
var_dump($room->listRoomAdminsAll('177630783537155'));
// 添加聊天室管理员
var_dump($room->promoteRoomAdmin('177630783537155', 'user4'));
var_dump($room->listRoomAdminsAll('177630783537155'));
 */


/* 
var_dump($room->listRoomAdminsAll('177630783537155'));
// 移除聊天室管理员
var_dump($room->demoteRoomAdmin('177630783537155', 'user4'));
var_dump($room->listRoomAdminsAll('177630783537155'));
 */


/* 
// 分页获取聊天室超级管理员列表
var_dump($room->listRoomSuperAdmins(2));
var_dump($room->listRoomSuperAdmins(2, 2));
var_dump($room->listRoomSuperAdmins(2, 3));
 */


/* 
var_dump($room->listRoomSuperAdminsAll());
// 添加超级管理员
var_dump($room->promoteRoomSuperAdmin('user3'));
var_dump($room->listRoomSuperAdminsAll());
 */


/* 
// 移除超级管理员
var_dump($room->listRoomSuperAdmins());
var_dump($room->demoteRoomSuperAdmin('user3'));
var_dump($room->listRoomSuperAdmins());
 */
