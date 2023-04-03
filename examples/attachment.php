<?php
/*
 * 上传下载附件示例
 */
require_once __DIR__ . '/../autoload.php';
$config = require_once 'config.php';

use Easemob\Auth;
use Easemob\Attachment;

// 初始化授权对象，环信 token 初始化
$easemob = $config['easemob'];
$auth = new Auth($easemob['app_key'], $easemob['client_id'], $easemob['client_secret']);

// 设置 REST 域名，沙箱环境使用，不是沙箱环境会自动获取
if (isset($easemob['api_uri']) && $easemob['api_uri']) {
    $auth->setApiUri($easemob['api_uri']);
}

// 实例化对象
$attachment = new Attachment($auth);

echo '<pre>';


/* 
// 上传文件
$data = $attachment->uploadFile('images/1.png');
var_dump($data);
// array(3) {
//     ["uuid"]=>
//     string(36) "a4364f90-b0d8-11ec-8cfe-b57d5aca4e63"
//     ["type"]=>
//     string(8) "chatfile"
//     ["share-secret"]=>
//     string(48) "pDZPmrDYEeyGTyPdhVc_kj_MzWvSzIfi3bg_fgkvpVjvQAo5"
// }
$data = $attachment->uploadFile('/usr/share/nginx/html/sdk.com/examples/images/1.png', true);
var_dump($data);
// array(3) {
//     ["uuid"]=>
//     string(36) "a4615730-b0d8-11ec-92b5-1fe1ed287b6a"
//     ["type"]=>
//     string(8) "chatfile"
//     ["share-secret"]=>
//     string(48) "pGF-QLDYEeyPS6lef7QYBPQMYwHMCVRt34DkF3KYLMETh4s0"
// }
 */


/* 
// 下载文件
var_dump($attachment->downloadFile('images/11.png', 'a4364f90-b0d8-11ec-8cfe-b57d5aca4e63'));
// 下载文件
var_dump($attachment->downloadFile('images/111/22.png', 'a4615730-b0d8-11ec-92b5-1fe1ed287b6a', 'pGF-QLDYEeyPS6lef7QYBPQMYwHMCVRt34DkF3KYLMETh4s0'));
 */


/* 
// 下载缩略图
var_dump($attachment->downloadThumb('images/11_thumb.png', 'a4364f90-b0d8-11ec-8cfe-b57d5aca4e63'));
var_dump($attachment->downloadThumb('images/111/11_thumb.png', 'a4615730-b0d8-11ec-92b5-1fe1ed287b6a', 'pGF-QLDYEeyPS6lef7QYBPQMYwHMCVRt34DkF3KYLMETh4s0'));
 */
