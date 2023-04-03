<?php
namespace tests;

class PushTest extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function testUpdateUserNickname()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();
        $this->assertArrayNotHasKey('code', $this->user->create(array('username' => $username, 'password' => $password)));
        $this->assertTrue($this->push->updateUserNickname($username, sprintf("nickname-%s", $username)));
        $data = $this->user->get($username);
        $this->assertArrayHasKey('uuid', $data);
        $this->assertEquals('nickname-' . $data['username'], $data['nickname']);
        $this->assertTrue($this->user->delete($username));
    }

    public function testNotificationDisplayStyle()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();
        $this->assertArrayNotHasKey('code', $this->user->create(array('username' => $username, 'password' => $password)));

        $this->assertTrue($this->push->setNotificationDisplayStyle($username, 0));
        $data = $this->user->get($username);
        $this->assertEquals('0', $data['notification_display_style']);

        $this->assertTrue($this->push->setNotificationDisplayStyle($username));
        $data = $this->user->get($username);
        $this->assertEquals('1', $data['notification_display_style']);

        $this->assertTrue($this->user->delete($username));
    }

    public function testOpenCloseNotificationNoDisturbing()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();
        $this->assertArrayNotHasKey('code', $this->user->create(array('username' => $username, 'password' => $password)));

        $this->assertTrue($this->push->openNotificationNoDisturbing($username, 10, 19));
        $data = $this->user->get($username);
        $this->assertTrue($data['notification_no_disturbing']);

        $this->assertTrue($this->push->closeNotificationNoDisturbing($username));
        $data = $this->user->get($username);
        $this->assertFalse($data['notification_no_disturbing']);

        $this->assertTrue($this->user->delete($username));
    }
}