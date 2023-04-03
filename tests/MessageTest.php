<?php
namespace tests;

class MessageTest extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function testMessageSendText()
    {
        $randomFromUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomToUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomFromUsername, 'password' => $randomPassword), array('username' => $randomToUsername, 'password' => $randomPassword))));

        $this->assertArrayNotHasKey('code', $this->message->text('users', array($randomToUsername), array('msg' => 'hello', 'ext' => array('ext1' => 'val1')), $randomFromUsername));

        $this->assertTrue($this->user->delete($randomFromUsername));
        $this->assertTrue($this->user->delete($randomToUsername));
    }

    public function testMessageSendImage()
    {
        $randomFromUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomToUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomFromUsername, 'password' => $randomPassword), array('username' => $randomToUsername, 'password' => $randomPassword))));

        $data = $this->attachment->uploadFile(dirname(__FILE__).'/assets/1.png');
        $this->assertArrayHasKey('uuid', $data);

        $msg = array(
            'filename' => '1.png',
            'uuid' => $data['uuid'],
            'secret' => $data['share-secret'],
            'size' => array(
                'width' => 36,
                'height' => 36,
            ),
        );
        $this->assertArrayNotHasKey('code', $this->message->image('users', array($randomToUsername), $msg, $randomFromUsername));

        $this->assertTrue($this->user->delete($randomFromUsername));
        $this->assertTrue($this->user->delete($randomToUsername));
    }

    public function testMessageSendVoice()
    {
        $randomFromUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomToUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomFromUsername, 'password' => $randomPassword), array('username' => $randomToUsername, 'password' => $randomPassword))));

        $data = $this->attachment->uploadFile(dirname(__FILE__).'/assets/mario.amr');
        $this->assertArrayHasKey('uuid', $data);
        
        $msg = array(
            'filename' => 'mario.amr',
            'uuid' => $data['uuid'],
            'secret' => $data['share-secret'],
            'length' => 89,
        );
        $this->assertArrayNotHasKey('code', $this->message->audio('users', array($randomToUsername), $msg, $randomFromUsername));

        $this->assertTrue($this->user->delete($randomFromUsername));
        $this->assertTrue($this->user->delete($randomToUsername));
    }

    public function testMessageSendVideo()
    {
        $randomFromUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomToUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomFromUsername, 'password' => $randomPassword), array('username' => $randomToUsername, 'password' => $randomPassword))));

        $movie = dirname(__FILE__).'/assets/movie.ogg';
        $data = $this->attachment->uploadFile($movie);
        $this->assertArrayHasKey('uuid', $data);

        $thumb = $this->attachment->uploadFile(dirname(__FILE__).'/assets/1.png');
        $this->assertArrayHasKey('uuid', $thumb);

        $msg = array(
            'filename' => 'movie.ogg',  // 视频文件名称
            'uuid' => $data['uuid'],   // 成功上传视频文件返回的UUID
            'secret' => $data['share-secret'], // 成功上传视频文件后返回的secret
            'thumb_uuid' => $thumb['uuid'],  // 成功上传视频缩略图返回的 UUID
            'thumb_secret' => $thumb['share-secret'],   // 成功上传视频缩略图后返回的secret
            'length' => 3, // 视频播放长度
            'file_length' => filesize($movie),    // 视频文件大小（单位：字节）
        );
        $this->assertArrayNotHasKey('code', $this->message->video('users', array($randomToUsername), $msg, $randomFromUsername));

        $this->assertTrue($this->user->delete($randomFromUsername));
        $this->assertTrue($this->user->delete($randomToUsername));
    }

    public function testMessageSendFile()
    {
        $randomFromUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomToUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomFromUsername, 'password' => $randomPassword), array('username' => $randomToUsername, 'password' => $randomPassword))));

        $data = $this->attachment->uploadFile(dirname(__FILE__).'/assets/1.txt');
        $this->assertArrayHasKey('uuid', $data);

        $msg = array(
            'filename' => '1.txt',  // 文件名称
            'uuid' => $data['uuid'],   // 成功上传文件返回的UUID
            'secret' => $data['share-secret'], // 成功上传文件后返回的secret
        );
        $this->assertArrayNotHasKey('code', $this->message->file('users', array($randomToUsername), $msg, $randomFromUsername));

        $this->assertTrue($this->user->delete($randomFromUsername));
        $this->assertTrue($this->user->delete($randomToUsername));
    }

    public function testMessageSendLocation()
    {
        $randomFromUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomToUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomFromUsername, 'password' => $randomPassword), array('username' => $randomToUsername, 'password' => $randomPassword))));

        $msg = array(
            'lat' => '39.966',  // 纬度
            'lng' => '116.322',   // 经度
            'addr' => '中国北京市海淀区中关村', // 地址
        );
        $this->assertArrayNotHasKey('code', $this->message->location('users', array($randomToUsername), $msg, $randomFromUsername));

        $this->assertTrue($this->user->delete($randomFromUsername));
        $this->assertTrue($this->user->delete($randomToUsername));
    }

    public function testMessageSendCommand()
    {
        $randomFromUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomToUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomFromUsername, 'password' => $randomPassword), array('username' => $randomToUsername, 'password' => $randomPassword))));

        $msg = array(
            'event' => 'notification',  // 自定义键值
            'id' => '123',   // 自定义键值
        );
        $this->assertArrayNotHasKey('code', $this->message->cmd('users', array($randomToUsername), $msg, $randomFromUsername));

        $this->assertTrue($this->user->delete($randomFromUsername));
        $this->assertTrue($this->user->delete($randomToUsername));
    }

    public function testMessageSendCustom()
    {
        $randomFromUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomToUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomFromUsername, 'password' => $randomPassword), array('username' => $randomToUsername, 'password' => $randomPassword))));

        $msg = array(
            // 用户自定义的事件类型，必须是string，值必须满足正则表达式 [a-zA-Z0-9-_/\.]{1,32}，最短1个字符 最长32个字符
            'customEvent' => 'xxx',
            // 用户自定义的事件属性，类型必须是Map<String,String>，最多可以包含16个元素。customExts 是可选的，不需要可以不传
            'customExts' => array(
                'asd' => '123',
            ),
        );
        $this->assertArrayNotHasKey('code', $this->message->custom('users', array($randomToUsername), $msg, $randomFromUsername));

        $this->assertTrue($this->user->delete($randomFromUsername));
        $this->assertTrue($this->user->delete($randomToUsername));
    }

    public function testMessageSendExtension()
    {
        $randomFromUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomToUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomFromUsername, 'password' => $randomPassword), array('username' => $randomToUsername, 'password' => $randomPassword))));

        $msg = array(
            // 用户自定义的事件类型，必须是string，值必须满足正则表达式 [a-zA-Z0-9-_/\.]{1,32}，最短1个字符 最长32个字符
            'customEvent' => 'xxx',
            // 用户自定义的事件属性，类型必须是Map<String,String>，最多可以包含16个元素。customExts 是可选的，不需要可以不传
            'customExts' => array(
                'asd' => '123',
            ),
            'ext' => array(
                "em_apns_ext" => array(
                    "em_push_content" => "自定义推送显示"
                )
            )
        );
        $this->assertArrayNotHasKey('code', $this->message->custom('users', array($randomToUsername), $msg, $randomFromUsername));

        $this->assertTrue($this->user->delete($randomFromUsername));
        $this->assertTrue($this->user->delete($randomToUsername));
    }
}
