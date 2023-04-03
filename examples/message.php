<?php
/*
 * 发送消息示例
 */
require_once __DIR__ . '/../autoload.php';
$config = require_once 'config.php';

use Easemob\Auth;
use Easemob\Message;

// 初始化授权对象，环信 token 初始化
$easemob = $config['easemob'];
$auth = new Auth($easemob['app_key'], $easemob['client_id'], $easemob['client_secret']);

// 设置 REST 域名，沙箱环境使用，不是沙箱环境会自动获取
if (isset($easemob['api_uri']) && $easemob['api_uri']) {
    $auth->setApiUri($easemob['api_uri']);
}

// 实例化对象
$message = new Message($auth);

echo '<pre>';


/* 
// 获取用户离线消息数
var_dump($message->countMissedMessages('user1'));
 */


/* 
var_dump($message->countMissedMessages('user4'));
var_dump($message->countMissedMessages('user5'));
// 发送文本消息
$msg = array(
    'msg' => 'testmessage',
    // 扩展，可以没有这个字段，但是如果有，值不能是 “ext:null” 这种形式，否则出错。
    'ext' => array(
        'ext1' => 'val1',
        'ext2' => 'val2',
    ),
);
var_dump($message->text('users', array('user4', 'user5'), $msg, 'user3'));
// array(2) {
//     ["user5"]=>
//     string(18) "992810797698646052"
//     ["user4"]=>
//     string(18) "992810797698650148"
// }
var_dump($message->countMissedMessages('user4'));
var_dump($message->countMissedMessages('user5'));
 */


/* 
// 发送文本消息（仅发送给在线用户，消息同步给发送发）
$msg = array(
    'msg' => 'testmessage',
    // 扩展，可以没有这个字段，但是如果有，值不能是 “ext:null” 这种形式，否则出错。
    'ext' => array(
        'ext1' => 'val1',
        'ext2' => 'val2',
    ),
);
var_dump($message->text('users', array('user4', 'user5'), $msg, 'user3', true, true));
 */


/* 
var_dump($message->countMissedMessages('user4'));
var_dump($message->countMissedMessages('user5'));
// 发送图片消息
$msg = array(
    'filename' => '1.png',   // 图片名称
    'uuid' => 'a4364f90-b0d8-11ec-8cfe-b57d5aca4e63',   // 成功上传文件返回的UUID
    'secret' => 'pDZPmrDYEeyGTyPdhVc_kj_MzWvSzIfi3bg_fgkvpVjvQAo5', // 成功上传文件后返回的secret
    'size' => array(    // 图片尺寸；height：高度，width：宽
        'width' => 36,
        'height' => 36,
    ),
);
var_dump($message->image('users', array('user4', 'user5'), $msg, 'user3'));
// array(2) {
//     ["user5"]=>
//     string(18) "992037625382568852"
//     ["user4"]=>
//     string(18) "992037625382572948"
// }
var_dump($message->countMissedMessages('user4'));
var_dump($message->countMissedMessages('user5'));
 */


/* 
var_dump($message->countMissedMessages('user4'));
var_dump($message->countMissedMessages('user5'));
// 发送语音消息
$msg = array(
    'filename' => '1.aud.silk',   // 语音名称
    'uuid' => 'a4364f90-b0d8-11ec-8cfe-b57d5aca4e63',   // 成功上传文件返回的UUID
    'secret' => 'pDZPmrDYEeyGTyPdhVc_kj_MzWvSzIfi3bg_fgkvpVjvQAo5', // 成功上传文件后返回的secret
    'length' => 10, // 语音时间（单位：秒）
);
var_dump($message->audio('users', array('user4', 'user5'), $msg, 'user3'));
// array(2) {
//     ["user5"]=>
//     string(18) "992037819046167688"
//     ["user4"]=>
//     string(18) "992037819046171784"
// }
var_dump($message->countMissedMessages('user4'));
var_dump($message->countMissedMessages('user5'));
 */


/* 
var_dump($message->countMissedMessages('user4'));
var_dump($message->countMissedMessages('user5'));
// 发送视频消息
$msg = array(
    'filename' => 'movie.mp4',  // 视频文件名称
    'uuid' => 'a4364f90-b0d8-11ec-8cfe-b57d5aca4e63',   // 成功上传文件返回的UUID
    'secret' => 'pDZPmrDYEeyGTyPdhVc_kj_MzWvSzIfi3bg_fgkvpVjvQAo5', // 成功上传文件后返回的secret
    'thumb_uuid' => 'a4364f90-b0d8-11ec-8cfe-b57d5aca4e63',  // 成功上传视频缩略图返回的 UUID
    'thumb_secret' => 'pDZPmrDYEeyGTyPdhVc_kj_MzWvSzIfi3bg_fgkvpVjvQAo5',   // 成功上传视频缩略图后返回的secret
    'length' => 13, // 视频播放长度
    'file_length' => 318465,    // 视频文件大小（单位：字节）
);
var_dump($message->video('users', array('user4', 'user5'), $msg, 'user3'));
// array(2) {
//     ["user5"]=>
//     string(18) "992038184839807128"
//     ["user4"]=>
//     string(18) "992038184839811224"
// }
var_dump($message->countMissedMessages('user4'));
var_dump($message->countMissedMessages('user5'));
 */


/* 
var_dump($message->countMissedMessages('user4'));
var_dump($message->countMissedMessages('user5'));
// 发送文件消息
$msg = array(
    'filename' => '1.txt',  // 文件名称
    'uuid' => 'a4364f90-b0d8-11ec-8cfe-b57d5aca4e63',   // 成功上传文件返回的UUID
    'secret' => 'pDZPmrDYEeyGTyPdhVc_kj_MzWvSzIfi3bg_fgkvpVjvQAo5', // 成功上传文件后返回的secret
);
var_dump($message->file('users', array('user4', 'user5'), $msg, 'user3'));
// array(2) {
//     ["user5"]=>
//     string(18) "992038408064860188"
//     ["user4"]=>
//     string(18) "992038408064864284"
// }
var_dump($message->countMissedMessages('user4'));
var_dump($message->countMissedMessages('user5'));
 */


/* 
var_dump($message->countMissedMessages('user4'));
var_dump($message->countMissedMessages('user5'));
// 发送位置消息
$msg = array(
    'lat' => '39.966',  // 纬度
    'lng' => '116.322',   // 经度
    'addr' => '中国北京市海淀区中关村', // 地址
);
var_dump($message->location('users', array('user4', 'user5'), $msg, 'user3'));
// array(2) {
//     ["user5"]=>
//     string(18) "992038552529272984"
//     ["user4"]=>
//     string(18) "992038552529277080"
// }
var_dump($message->countMissedMessages('user4'));
var_dump($message->countMissedMessages('user5'));
 */


/* 
var_dump($message->countMissedMessages('user4'));
var_dump($message->countMissedMessages('user5'));
// 发送透传消息
$msg = array(
    'event' => 'notification',  // 自定义键值
);
var_dump($message->cmd('users', array('user4', 'user5'), $msg, 'user3'));
// array(2) {
//     ["user5"]=>
//     string(18) "992038709236860040"
//     ["user4"]=>
//     string(18) "992038709236864136"
// }
var_dump($message->countMissedMessages('user4'));
var_dump($message->countMissedMessages('user5'));
 */


/* 
var_dump($message->countMissedMessages('user4'));
var_dump($message->countMissedMessages('user5'));
// 发送自定义消息
$msg = array(
    'customEvent' => 'xxx',  // 用户自定义的事件类型，必须是string，值必须满足正则表达式 [a-zA-Z0-9-_/\.]{1,32}，最短1个字符 最长32个字符
    'customExts' => array(   // 用户自定义的事件属性，类型必须是Map<String,String>，最多可以包含16个元素。customExts 是可选的，不需要可以不传
        'asd' => '123',
    ),
    'ext' => array(
        'test' => 'test111',
    ),
);
var_dump($message->custom('users', array('user4', 'user5'), $msg, 'user3'));
// array(2) {
//     ["user5"]=>
//     string(18) "992038835590266892"
//     ["user4"]=>
//     string(18) "992038835590270988"
// }
var_dump($message->countMissedMessages('user4'));
var_dump($message->countMissedMessages('user5'));
 */


/* 
// 获取某条离线消息状态
var_dump($message->isMessageDeliveredToUser('user4', '992810797698650148'));
var_dump($message->isMessageDeliveredToUser('user5', '992810797698646052'));
 */


/* 
date_default_timezone_set('PRC');
// 获取历史消息文件
var_dump($message->getHistoryAsUri(2022032916));
 */


/* 
// 下载消息历史文件到本地
$message->getHistoryAsLocalFile(2022032916, '111.gz');
 */


/* 
$msg = array(
    'msg' => 'testmessage',
    // 扩展，可以没有这个字段，但是如果有，值不能是 “ext:null” 这种形式，否则出错。
    'ext' => array(
        'ext1' => 'val1',
        'ext2' => 'val2',
    ),
);
var_dump($message->text('users', array('user4', 'user5'), $msg, 'user3'));
// array(2) {
//     ["user5"]=>
//     string(18) "992813372330214544"
//     ["user4"]=>
//     string(18) "992813372330218640"
// }
 */


/* 
// 撤回消息
$msg = array(
    'msg_id' => '992813372330218640',
    'chat_type' => 'chat',
    'force' => true
);
var_dump($message->withdraw($msg));
 */


/* 
// 服务端单向删除会话
var_dump($message->deleteSession('user3', '992813372330218640', 'chat'));
 */
