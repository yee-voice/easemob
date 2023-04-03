<?php
namespace tests;

class RoomTest extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function testRoomCycles()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $roomId = $this->room->createRoom("chat room", "room description", $randomOwnerUsername, $members, 200);
        $this->assertIsString($roomId);

        $data = $this->room->getRoom($roomId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals($data['name'], "chat room");

        $this->assertTrue($this->room->updateRoom(array('room_id' => $roomId, 'name' => "room chat")));

        $data = $this->room->getRoom($roomId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals($data['name'], "room chat");

        $this->assertTrue($this->room->destroyRoom($roomId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testRoomListAll()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $roomId = $this->room->createRoom("chat room", "room description", $randomOwnerUsername, $members, 200);
        $this->assertIsString($roomId);

        $this->assertArrayNotHasKey('code', $this->room->listAllRooms());
        $this->assertArrayNotHasKey('code', $this->room->listRooms(1));

        $this->assertTrue($this->room->destroyRoom($roomId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testRoomUserJoinedList()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $roomId = $this->room->createRoom("chat room", "room description", $randomOwnerUsername, $members, 200);
        $this->assertIsString($roomId);

        $data = $this->room->listAllRoomsUserJoined($randomOwnerUsername);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(1, count($data));

        $this->assertTrue($this->room->destroyRoom($roomId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testRoomMembers()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array();
        $roomId = $this->room->createRoom("chat room", "room description", $randomOwnerUsername, $members, 200);
        $this->assertIsString($roomId);

        $this->assertTrue($this->room->addRoomMember($roomId, $randomMemberUsername));

        $data = $this->room->listRoomMembersAll($roomId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(2, count($data));

        $this->assertTrue($this->room->removeRoomMember($roomId, $randomMemberUsername));

        $data = $this->room->listRoomMembersAll($roomId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(1, count($data));

        $this->assertArrayNotHasKey('code', $this->room->listRoomMembers($roomId, 2));

        $this->assertTrue($this->room->destroyRoom($roomId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testRoomAdmins()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $roomId = $this->room->createRoom("chat room", "room description", $randomOwnerUsername, $members, 200);
        $this->assertIsString($roomId);

        $this->assertTrue($this->room->promoteRoomAdmin($roomId, $randomMemberUsername));

        $data = $this->room->listRoomAdminsAll($roomId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(1, count($data));

        $this->assertTrue($this->room->demoteRoomAdmin($roomId, $randomMemberUsername));

        $data = $this->room->listRoomAdminsAll($roomId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(0, count($data));

        $this->assertTrue($this->room->destroyRoom($roomId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testRoomSuperAdmins()
    {
        $randomUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $this->assertArrayNotHasKey('code', $this->user->create(array('username' => $randomUsername, 'password' => $randomPassword)));

        $this->assertTrue($this->room->promoteRoomSuperAdmin($randomUsername));

        $data = $this->room->listRoomSuperAdminsAll();
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(1, count($data));

        $this->assertTrue($this->room->demoteRoomSuperAdmin($randomUsername));

        $data = $this->room->listRoomSuperAdminsAll();
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(0, count($data));

        $this->assertTrue($this->user->delete($randomUsername));
    }

    public function testRoomUsersBlockedJoin()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $roomId = $this->room->createRoom("chat room", "room description", $randomOwnerUsername, $members, 200);
        $this->assertIsString($roomId);

        $this->assertTrue($this->block->blockUserJoinRoom($roomId, $randomMemberUsername));

        $data = $this->block->getUsersBlockedJoinRoom($roomId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals($data[0], $randomMemberUsername);

        $this->assertTrue($this->block->unblockUserJoinRoom($roomId, $randomMemberUsername));
        $data = $this->block->getUsersBlockedJoinRoom($roomId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(count($data), 0);

        $this->assertTrue($this->room->destroyRoom($roomId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testRoomBlockUserSendMsg()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $roomId = $this->room->createRoom("chat room", "room description", $randomOwnerUsername, $members, 200);
        $this->assertIsString($roomId);

        $this->assertTrue($this->block->blockUserSendMsgToRoom($roomId, $members, 30000));

        $data = $this->block->getUsersBlockedSendMsgToRoom($roomId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(1, count($data));

        $this->assertTrue($this->block->unblockUserSendMsgToRoom($roomId, $members));

        $data = $this->block->getUsersBlockedSendMsgToRoom($roomId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(0, count($data));

        $this->assertTrue($this->room->destroyRoom($roomId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }
}
