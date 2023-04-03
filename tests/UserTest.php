<?php
namespace tests;

class UserTest extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function testCreate()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();
        $this->assertArrayNotHasKey('code', $this->user->create(array('username' => $username, 'password' => $password)));
        $this->assertArrayHasKey('uuid', $this->user->get($username));
    }

    public function testBatchCreate()
    {
        $randomUsername = Utils::randomUserName();
        $randomUsername1 = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();

        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomUsername, 'password' => $randomPassword), array('username' => $randomUsername1, 'password' => $randomPassword))));
        $this->assertArrayHasKey('uuid', $this->user->get($randomUsername));
        $this->assertArrayHasKey('uuid', $this->user->get($randomUsername1));
    }

    public function testUserLifeCycles()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();
        $this->assertArrayNotHasKey('code', $this->user->create(array('username' => $username, 'password' => $password)));
        $this->assertArrayHasKey('uuid', $this->user->get($username));
        $this->assertTrue($this->user->delete($username));
        $result = $this->user->get($username);
        $this->assertEquals(404, $result['code']);
    }

    public function testUserForceLogout()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();
        $this->assertArrayNotHasKey('code', $this->user->create(array('username' => $username, 'password' => $password)));
        $this->assertIsBool($this->user->forceLogoutAllDevices($username));
        $this->assertTrue($this->user->delete($username));
    }

    public function testUserUpdatePassword()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();
        $this->assertArrayNotHasKey('code', $this->user->create(array('username' => $username, 'password' => $password)));
        $this->assertIsBool($this->user->updateUserPassword($username, 'password'));
        $this->assertTrue($this->user->delete($username));
    }

    public function testUserListUsers()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();
        $this->assertArrayNotHasKey('code', $this->user->create(array('username' => $username, 'password' => $password)));
        $data = $this->user->listUsers(1);
        $this->assertArrayHasKey('data', $data);
        $this->assertEquals(1, count($data['data']));
        $this->assertArrayHasKey('uuid', $data['data'][0]);
        $this->assertTrue($this->user->delete($username));
    }

    public function testUserContactLifeCycles()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();

        $contactUsername = Utils::randomUserName();
        $contactPassword = Utils::randomPassword();

        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $username, 'password' => $password), array('username' => $contactUsername, 'password' => $contactPassword))));

        $this->assertIsBool($this->contact->add($username, $contactUsername));

        $data = $this->contact->get($username);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(1, count($data));
        $this->assertEquals($data[0], $contactUsername);

        $this->assertIsBool($this->contact->remove($username, $contactUsername));

        $data = $this->contact->get($username);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(0, count($data));

        $this->assertTrue($this->user->delete($username));
        $this->assertTrue($this->user->delete($contactUsername));
    }

    public function testUserGetUsersBlockedFromSendMsg()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();

        $randomUsernameCodeJack = Utils::randomUserName();

        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $username, 'password' => $password), array('username' => $randomUsernameCodeJack, 'password' => $password))));

        $this->assertTrue($this->block->blockUserSendMsgToUser($username, array($randomUsernameCodeJack)));

        $data = $this->block->getUsersBlockedFromSendMsgToUser($username);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(1, count($data));
        $this->assertEquals($data[0], $randomUsernameCodeJack);

        $this->assertTrue($this->user->delete($username));
        $this->assertTrue($this->user->delete($randomUsernameCodeJack));
    }

    public function testUserBlockUserSendMsg()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();

        $randomUsernameCodeJack = Utils::randomUserName();

        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $username, 'password' => $password), array('username' => $randomUsernameCodeJack, 'password' => $password))));

        $this->assertIsBool($this->contact->add($username, $randomUsernameCodeJack));

        $data = $this->contact->get($username);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(1, count($data));
        $this->assertEquals($data[0], $randomUsernameCodeJack);

        $this->assertTrue($this->block->blockUserSendMsgToUser($username, array($randomUsernameCodeJack)));

        $data = $this->block->getUsersBlockedFromSendMsgToUser($username);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(1, count($data));
        $this->assertEquals($data[0], $randomUsernameCodeJack);

        $data = $this->contact->get($username);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(1, count($data));
        $this->assertEquals($data[0], $randomUsernameCodeJack);

        $this->assertTrue($this->user->delete($username));
        $this->assertTrue($this->user->delete($randomUsernameCodeJack));
    }

    public function testUserUnblockUserSendMsg()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();

        $randomUsernameCodeJack = Utils::randomUserName();

        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $username, 'password' => $password), array('username' => $randomUsernameCodeJack, 'password' => $password))));

        $this->assertIsBool($this->contact->add($username, $randomUsernameCodeJack));

        $data = $this->contact->get($username);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(1, count($data));
        $this->assertEquals($data[0], $randomUsernameCodeJack);

        $this->assertTrue($this->block->blockUserSendMsgToUser($username, array($randomUsernameCodeJack)));

        $data = $this->block->getUsersBlockedFromSendMsgToUser($username);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(1, count($data));
        $this->assertEquals($data[0], $randomUsernameCodeJack);

        $this->assertTrue($this->block->unblockUserSendMsgToUser($username, $randomUsernameCodeJack));

        $data = $this->block->getUsersBlockedFromSendMsgToUser($username);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(0, count($data));

        $data = $this->contact->get($username);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(1, count($data));
        $this->assertEquals($data[0], $randomUsernameCodeJack);

        $this->assertTrue($this->user->delete($username));
        $this->assertTrue($this->user->delete($randomUsernameCodeJack));
    }

    public function testUserCountMissedMessages()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();

        $randomUsernameCodeJack = Utils::randomUserName();

        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $username, 'password' => $password), array('username' => $randomUsernameCodeJack, 'password' => $password))));

        $this->assertArrayNotHasKey('code', $this->message->text('users', array($randomUsernameCodeJack), array('msg' => 'CountMissedMessages'), $username));

        $data = $this->message->countMissedMessages($randomUsernameCodeJack);
        $this->assertIsInt($data);
        $this->assertEquals(1, $data);

        $this->assertTrue($this->user->delete($username));
        $this->assertTrue($this->user->delete($randomUsernameCodeJack));
    }

    public function testUserBlockLogin()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();
        $this->assertArrayNotHasKey('code', $this->user->create(array('username' => $username, 'password' => $password)));

        $this->assertTrue($this->block->blockUserLogin($username));
        $result = $this->user->get($username);
        $this->assertFalse($result['activated']);

        $this->assertTrue($this->block->unblockUserLogin($username));
        $result = $this->user->get($username);
        $this->assertTrue($result['activated']);

        $this->assertTrue($this->user->delete($username));
    }

    public function testUserOnlineStatus()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();
        
        $this->assertArrayNotHasKey('code', $this->user->create(array('username' => $username, 'password' => $password)));

        $this->assertIsBool($this->user->isUserOnline($username));

        $this->assertTrue($this->user->delete($username));
    }

    public function testUsersOnlineStatus()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();

        $randomUsernameCodeJack = Utils::randomUserName();

        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $username, 'password' => $password), array('username' => $randomUsernameCodeJack, 'password' => $password))));

        $this->assertArrayNotHasKey('code', $this->user->isUsersOnline(array($username, $randomUsernameCodeJack)));

        $this->assertTrue($this->user->delete($username));
        $this->assertTrue($this->user->delete($randomUsernameCodeJack));
    }

    public function testGetUserToken()
    {
        $username = Utils::randomUserName();
        $password = Utils::randomPassword();
        
        $this->assertArrayNotHasKey('code', $this->user->create(array('username' => $username, 'password' => $password)));

        $this->assertArrayHasKey('access_token', $this->auth->getUserToken($username, $password));

        $this->assertTrue($this->user->delete($username));
    }
}
