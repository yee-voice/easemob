<?php
namespace tests;

class UserMetadataTest extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function testMetadataCycles()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();
        $this->assertArrayNotHasKey('code', $this->user->create(array('username' => $username, 'password' => $password)));

        $data = array(
            'nickname' => '昵称',
            'avatar' => 'http://www.easemob.com/avatar.png',
            'phone' => '159',
        );
        $this->assertTrue($this->metadata->setMetadataToUser($username, $data));

        $this->assertArrayNotHasKey('code', $this->metadata->getMetadataFromUser($username));

        $this->assertIsNumeric($this->metadata->getUsage());

        $this->assertIsBool($this->metadata->deleteMetadataFromUser($username));

        $this->assertTrue($this->user->delete($username));
    }
}
