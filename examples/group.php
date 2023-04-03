<?php
/*
 * 群组管理示例
 */
require_once __DIR__ . '/../autoload.php';
$config = require_once 'config.php';

use Easemob\Auth;
use Easemob\Group;

// 初始化授权对象，环信 token 初始化
$easemob = $config['easemob'];
$auth = new Auth($easemob['app_key'], $easemob['client_id'], $easemob['client_secret']);

// 设置 REST 域名，沙箱环境使用，不是沙箱环境会自动获取
if (isset($easemob['api_uri']) && $easemob['api_uri']) {
    $auth->setApiUri($easemob['api_uri']);
}

// 实例化对象
$group = new Group($auth);

echo '<pre>';


/* 
// 创建公开群
var_dump($group->createPublicGroup('user1', 'public_group', 'public_group_desc', array('user2', 'user3')));
// 177627064238081
// 创建私有群
var_dump($group->createPrivateGroup('user1', 'private_group', 'private_group_desc', array('user2', 'user3')));
// 177627064238082
 */


/* 
for ($i = 0; $i < 20; $i++) {
    var_dump($group->createPublicGroup('user1', 'public_group_' . $i, 'public_group_desc_' . $i, array('user2', 'user3')));
}
// string(15) "177627101986819"
// string(15) "177627103035393"
// string(15) "177627103035394"
// string(15) "177627103035396"
// string(15) "177627103035397"
// string(15) "177627104083969"
// string(15) "177627104083970"
// string(15) "177627104083971"
// string(15) "177627104083973"
// string(15) "177627104083974"
// string(15) "177627105132545"
// string(15) "177627105132546"
// string(15) "177627105132547"
// string(15) "177627105132548"
// string(15) "177627105132549"
// string(15) "177627106181121"
// string(15) "177627106181122"
// string(15) "177627106181123"
// string(15) "177627106181124"
// string(15) "177627106181125"
// string(15) "177627106181126"
// string(15) "177627107229697"
 */


/* 
// 分页获取 App 中所有的群组
var_dump($group->listGroups(2));
var_dump($group->listGroups(2, 'ZGNiMjRmNGY1YjczYjlhYTNkYjk1MDY2YmEyNzFmODQ6aW06Z3JvdXA6MTExNTIxMDkxNTE5MzI3NyNkZW1vOjI'));
 */


/*  
// 获取 App 中所有的群组
var_dump($group->listAllGroups());
 */


/* 
// 分页获取单个用户加入的所有群组，第 1 页
var_dump($group->listGroupsUserJoined('user1', 1, 1));
// 分页获取单个用户加入的所有群组，第 2 页
var_dump($group->listGroupsUserJoined('user1', 1, 2));
 */


/* 
// 获取单个用户加入的所有群组
var_dump($group->listAllGroupsUserJoined('user1'));
 */


/* 
// 获取群组详情
var_dump($group->getGroup('177627064238081'));
var_dump($group->getGroup('177627064238081,177627064238082'));
 */


/* 
var_dump($group->getGroup('177627064238081'));
// 修改群组信息
$data = array(
    'group_id' => '177627064238081',
    'groupname' => 'test group',
    'description' => 'test description',
    'maxusers' => 400,
    'membersonly' => true,
    'allowinvites' => true,
    'custom' => 'test custom',
);
var_dump($group->updateGroup($data));
var_dump($group->getGroup('177627064238081'));
 */


/* 
// 删除群组
var_dump($group->getGroup('177627107229697'));
var_dump($group->destroyGroup('177627107229697'));
var_dump($group->getGroup('177627107229697'));
 */


/* 
// 获取群组公告
var_dump($group->getGroupAnnouncement('177627064238081'));
 */


/* 
var_dump($group->getGroupAnnouncement('177627064238081'));
// 修改群组公告
var_dump($group->updateGroupAnnouncement('177627064238081', 'test 公告内容xxx'));
var_dump($group->getGroupAnnouncement('177627064238081'));
 */


/* 
// 获取群组共享文件
var_dump($group->getGroupShareFiles('177627064238081'));
 */


/* 
// 上传群组共享文件
var_dump($group->uploadGroupShareFile('177627064238081', './images/1.png'));
array(6) {
    ["file_url"]=>
    string(120) "https://a1.easemob.com/1115210915193277/demo/chatgroups/177627064238081/share_files/59f53fc0-b18e-11ec-9abc-6766e56acf4e"
    ["group_id"]=>
    string(15) "177627064238081"
    ["file_name"]=>
    string(14) "./images/1.png"
    ["created"]=>
    int(1648798549436)
    ["file_id"]=>
    string(36) "59f53fc0-b18e-11ec-9abc-6766e56acf4e"
    ["file_size"]=>
    int(19161)
}
 */


/* 
// 下载群组共享文件
var_dump($group->downloadGroupShareFile('llxx.png', '177627064238081', '59f53fc0-b18e-11ec-9abc-6766e56acf4e'));
 */


/* 
var_dump($group->getGroupShareFiles('177627064238081'));
// 删除群组共享文件
var_dump($group->deleteGroupShareFile('177627064238081', '59f53fc0-b18e-11ec-9abc-6766e56acf4e'));
var_dump($group->getGroupShareFiles('177627064238081'));
 */


/* 
// 分页获取群组成员
var_dump($group->listGroupMembers('177627064238081', 1));
var_dump($group->listGroupMembers('177627064238081', 1, 2));
 */


/*  
// 获取群组全部成员
var_dump($group->listAllGroupMembers('177627101986819'));
 */


/* 
// 添加单个群组成员
var_dump($group->listAllGroupMembers('177627064238081'));
var_dump($group->addGroupMember('177627064238081', 'user4'));
var_dump($group->listAllGroupMembers('177627064238081'));
 */


/* 
// 批量添加群组成员
var_dump($group->listAllGroupMembers('177627101986819'));
var_dump($group->addGroupMembers('177627101986819', array('user4', 'user5', 'user6', 'user7')));
var_dump($group->listAllGroupMembers('177627101986819'));
 */


/* 
var_dump($group->listAllGroupMembers('177627064238081'));
// 移除单个群组成员
var_dump($group->removeGroupMember('177627064238081', 'user7'));
var_dump($group->listAllGroupMembers('177627064238081'));
 */


/* 
var_dump($group->listAllGroupMembers('177627064238081'));
// 批量移除群组成员
var_dump($group->removeGroupMembers('177627064238081', array('user5', 'user6')));
var_dump($group->listAllGroupMembers('177627064238081'));
 */


/* 
// 获取群管理员列表
var_dump($group->listGroupAdmins('177627064238081'));
 */


/* 
var_dump($group->listGroupAdmins('177627064238081'));
// 添加群管理员
var_dump($group->addGroupAdmin('177627064238081', 'user4'));
var_dump($group->addGroupAdmin('177627064238081', 'user3'));
var_dump($group->listGroupAdmins('177627064238081'));
 */


/* 
var_dump($group->listGroupAdmins('177627064238081'));
// 移除群管理员
var_dump($group->removeGroupAdmin('177627064238081', 'user4'));
var_dump($group->listGroupAdmins('177627064238081'));
 */


/* 
var_dump($group->getGroup('177627064238081'));
// 转让群组
var_dump($group->updateGroupOwner('177627064238081', 'user2'));
var_dump($group->getGroup('177627064238081'));
 */
