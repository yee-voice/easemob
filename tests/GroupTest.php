<?php
namespace tests;

class GroupTest extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function testGroupCreatePublic()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();

        $randomMemberUsername = Utils::randomUserName();

        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPublicGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $data = $this->group->listAllGroupMembers($groupId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(count($data) - 1, count($members));

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testGroupCreatePublicWithCustom()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();

        $randomMemberUsername = Utils::randomUserName();

        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPublicGroup($randomOwnerUsername, "group", "group description", $members, 200, true, 'custom');
        $this->assertIsString($groupId);

        $data = $this->group->listAllGroupMembers($groupId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(count($data) - 1, count($members));

        $result = $this->group->getGroup($groupId);

        $this->assertArrayNotHasKey('code', $result);
        $this->assertEquals($result['custom'], 'custom');

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testGroupCreatePrivate()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();

        $randomMemberUsername = Utils::randomUserName();

        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $data = $this->group->listAllGroupMembers($groupId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(count($data) - 1, count($members));

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testGroupCreatePrivateWithCustom()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();

        $randomMemberUsername = Utils::randomUserName();

        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true, 'custom');
        $this->assertIsString($groupId);

        $data = $this->group->listAllGroupMembers($groupId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(count($data) - 1, count($members));

        $group = $this->group->getGroup($groupId);
        $this->assertArrayNotHasKey('code', $group);
        $this->assertEquals($group['custom'], 'custom');

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testGroupDestroy()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertArrayHasKey('code', $this->group->getGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testGroupListAllGroups()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $this->assertArrayNotHasKey('code', $this->group->listAllGroups());

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testGroupListGroups()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $this->assertArrayNotHasKey('code', $this->group->listGroups(1));

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testGroupGet()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $randomAdminUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword), array('username' => $randomAdminUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername, $randomAdminUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $this->assertTrue($this->group->addGroupAdmin($groupId, $randomAdminUsername));
        $group = $this->group->getGroup($groupId);
        $this->assertArrayNotHasKey('code', $group);
        $this->assertEquals($group['affiliations_count'], 3);
        $this->assertNotNull($group['affiliations']);

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
        $this->assertTrue($this->user->delete($randomAdminUsername));
    }

    public function testGroupUpdate()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $maxUsers = 400;
        $this->assertTrue($this->group->updateGroup(array('group_id' => $groupId, 'maxusers' => $maxUsers)));

        $group = $this->group->getGroup($groupId);
        $this->assertArrayNotHasKey('code', $group);
        $this->assertEquals($group['maxusers'], $maxUsers);

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testGroupUpdateOwner()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $this->assertTrue($this->group->updateGroupOwner($groupId, $randomMemberUsername));

        $group = $this->group->getGroup($groupId);
        $this->assertArrayNotHasKey('code', $group);
        $this->assertEquals($group['owner'], $randomMemberUsername);

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testGroupGetAnnouncement()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $this->assertArrayNotHasKey('code', $this->group->getGroupAnnouncement($groupId));

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testGroupUpdateAnnouncement()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $announcement = "update announcement";
        $this->assertTrue($this->group->updateGroupAnnouncement($groupId, $announcement));

        $group = $this->group->getGroupAnnouncement($groupId);
        $this->assertArrayNotHasKey('code', $group);
        $this->assertEquals($announcement, $group['announcement']);

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testGroupListAllGroupMembers()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $this->assertArrayNotHasKey('code', $this->group->listAllGroupMembers($groupId));

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testGroupListMembers()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $this->assertArrayNotHasKey('code', $this->group->listGroupMembers($groupId, 2));

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testGroupAddMemberSingle()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $randomMemberUsername1 = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername1, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $this->assertTrue($this->group->addGroupMember($groupId, $randomMemberUsername1));

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername1));
    }

    public function testGroupAddMemberBatch()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $randomMemberUsername1 = Utils::randomUserName();
        $randomMemberUsername2 = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername1, 'password' => $randomPassword), array('username' => $randomMemberUsername2, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $addMembers = array($randomMemberUsername1, $randomMemberUsername2);
        $this->assertTrue($this->group->addGroupMembers($groupId, $addMembers));
        
        $members = $this->group->listAllGroupMembers($groupId);
        $this->assertArrayNotHasKey('code', $members);
        $this->assertEquals(4, count($members));

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername1));
        $this->assertTrue($this->user->delete($randomMemberUsername2));
    }

    public function testGroupRemoveMemberSingle()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $this->assertTrue($this->group->removeGroupMember($groupId, $randomMemberUsername));

        $members = $this->group->listAllGroupMembers($groupId);
        $this->assertArrayNotHasKey('code', $members);
        $this->assertEquals(1, count($members));

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testGroupRemoveMemberBatch()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $randomMemberUsername1 = Utils::randomUserName();
        $randomMemberUsername2 = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername1, 'password' => $randomPassword), array('username' => $randomMemberUsername2, 'password' => $randomPassword))));

        $members = array($randomMemberUsername, $randomMemberUsername1, $randomMemberUsername2);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $this->assertTrue($this->group->removeGroupMembers($groupId, $members));
        
        $members = $this->group->listAllGroupMembers($groupId);
        $this->assertArrayNotHasKey('code', $members);
        $this->assertEquals(1, count($members));

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername1));
        $this->assertTrue($this->user->delete($randomMemberUsername2));
    }

    public function testGroupAdmin()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $randomAdminUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword), array('username' => $randomAdminUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername, $randomAdminUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $this->assertTrue($this->group->addGroupAdmin($groupId, $randomAdminUsername));

        $admins = $this->group->listGroupAdmins($groupId);
        $this->assertArrayNotHasKey('code', $admins);
        $this->assertEquals(1, count($admins));

        $this->assertIsBool($this->group->removeGroupAdmin($groupId, $randomAdminUsername));

        $admins = $this->group->listGroupAdmins($groupId);
        $this->assertArrayNotHasKey('code', $admins);
        $this->assertEquals(0, count($admins));

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
        $this->assertTrue($this->user->delete($randomAdminUsername));
    }

    public function testGroupUsersBlockedJoin()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $this->assertTrue($this->block->blockUserJoinGroup($groupId, $randomMemberUsername));

        $data = $this->block->getUsersBlockedJoinGroup($groupId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals($data[0], $randomMemberUsername);

        $this->assertTrue($this->block->unblockUserJoinGroup($groupId, $randomMemberUsername));
        $data = $this->block->getUsersBlockedJoinGroup($groupId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(count($data), 0);

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }

    public function testGroupBlockUserSendMsg()
    {
        $randomOwnerUsername = Utils::randomUserName();
        $randomPassword = Utils::randomPassword();
        $randomMemberUsername = Utils::randomUserName();
        $this->assertArrayNotHasKey('code', $this->user->create(array(array('username' => $randomOwnerUsername, 'password' => $randomPassword), array('username' => $randomMemberUsername, 'password' => $randomPassword))));

        $members = array($randomMemberUsername);
        $groupId = $this->group->createPrivateGroup($randomOwnerUsername, "group", "group description", $members, 200, true);
        $this->assertIsString($groupId);

        $this->assertTrue($this->block->blockUserSendMsgToGroup($groupId, $members, 30000));

        $data = $this->block->getUsersBlockedSendMsgToGroup($groupId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(1, count($data));

        $this->assertTrue($this->block->unblockUserSendMsgToGroup($groupId, $members));

        $data = $this->block->getUsersBlockedSendMsgToGroup($groupId);
        $this->assertArrayNotHasKey('code', $data);
        $this->assertEquals(0, count($data));

        $this->assertTrue($this->group->destroyGroup($groupId));

        $this->assertTrue($this->user->delete($randomOwnerUsername));
        $this->assertTrue($this->user->delete($randomMemberUsername));
    }
}
