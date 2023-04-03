<?php

namespace Easemob;

use Easemob\Http\Http;

/**
 * \~chinese
 * Room 用于管理聊天室
 * 
 * \~english
 * The `Room` is used to manage chat rooms
 */
final class Room
{
    /**
     * @ignore
     * @var Auth $auth 授权对象
     */
    private $auth;

    /// @cond
    public function __construct($auth)
    {
        $this->auth = $auth;
    }
    /// @endcond

    /**
     * \~chinese
     * \brief
     * 获取 app 中所有的聊天室（分页）
     * 
     * @param  int    $limit  每页显示的数量，默认取 10 条
     * @param  string $cursor 分页游标
     * @return array          聊天室列表信息或者错误
     * 
     * \~english
     * \brief
     * Get all chat rooms in the app (paging)
     * 
     * @param  int    $limit  The number displayed on each page is 10 by default
     * @param  string $cursor Paging cursor
     * @return array          Chat room list information or error
     */
    public function listRooms($limit = 10, $cursor = '')
    {
        $limit = (int) $limit >= 0 ? (int) $limit : 10;
        $uri = $this->auth->getBaseUri() . '/chatrooms';
        $uri .= $limit ? '?limit=' . $limit : '';
        $uri .= ($limit && $cursor) ? '&cursor=' . $cursor : '';
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return array(
            'cursor' => isset($data['cursor']) ? $data['cursor'] : '',
            'data' => $data['data'],
        );
    }

    /**
     * \~chinese
     * \brief
     * 获取 app 中所有的聊天室
     * 
     * @return array 聊天室列表信息或者错误
     * 
     * \~english
     * \brief
     * Get all chat rooms in the app
     * 
     * @return array Chat room list information or error
     */
    public function listAllRooms()
    {
        $result = $this->listRooms(0);
        return $result['data'];
    }

    /**
     * \~chinese
     * \brief
     * 获取用户加入的聊天室（分页）
     * 
     * \details
     * 根据用户名称获取该用户加入的全部聊天室
     * 
     * @param  string $username 用户名
     * @param  int    $pageSize 每页获取的群组数量，默认取 10 条
     * @param  int    $pageNum  当前页码，默认取第 1 页
     * @return array            用户加入的聊天室列表或者错误
     * 
     * \~english
     * \brief
     * Get the chat room that the user joined (paging)
     * 
     * \details
     * Get all chat rooms joined by the user according to the user name
     * 
     * @param  string $username User name
     * @param  int    $pageSize The number of groups obtained per page is 10 by default
     * @param  int    $pageNum  The current page number is page 1 by default
     * @return array            The chat room list added by the user is incorrect
     */
    public function listRoomsUserJoined($username, $pageSize = 10, $pageNum = 1)
    {
        if (!trim($username)) {
            \Easemob\exception('Please pass the user name');
        }
        $pageSize = (int) $pageSize >= 0 ? (int) $pageSize : 10;
        $pageNum = (int) $pageNum > 0 ? (int) $pageNum : 1;
        $uri = $this->auth->getBaseUri() . '/users/' . trim($username) . '/joined_chatrooms';
        $uri .= $pageSize ? ('?pagesize=' . $pageSize . '&pagenum=' . $pageNum) : '';
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data'];
    }

    /**
     * \~chinese
     * \brief
     * 获取用户加入的聊天室
     * 
     * \details
     * 根据用户名称获取该用户加入的全部聊天室
     * 
     * @param  string $username 用户名
     * @return array            用户加入的聊天室列表或者错误
     * 
     * \~english
     * \brief
     * Get the chat room that the user joined
     * 
     * \details
     * Get all chat rooms joined by the user according to the user name
     * 
     * @param  string $username User name
     * @return array            The chat room list added by the user is incorrect
     */
    public function listAllRoomsUserJoined($username)
    {
        return $this->listRoomsUserJoined($username, 0);
    }

    /**
     * \~chinese
     * \brief
     * 获取聊天室详情
     * 
     * \details
     * 可以获取一个或多个聊天室的详情。当获取多个聊天室的详情时，可以直接填写多个 chatroom_id 并用 “,” 隔开，一次调用最多输入 100 个聊天室 ID，会返回所有存在的聊天室的详情，对于不存在的聊天室，response body 内返回 “chatroom id doesn’t exist”。
     * 
     * @param  string $roomId 聊天室 ID，多个之间用 “,” 分隔
     * @return array          聊天室详情或者错误
     * 
     * \~english
     * \brief
     * Get chat room details
     * 
     * \details
     * You can get details of one or more chat rooms. When obtaining the details of multiple chat rooms, you can directly fill in multiple chatrooms_ For all chat rooms that do not exist, enter "response ID" and return "response ID" at most once.
     * 
     * @param  string $roomId Chat room ID, separated by ","
     * @return array          Chat room details or errors
     */
    public function getRoom($roomId)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId;
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return strpos($roomId, ',') !== false ? $data['data'] : $data['data'][0];
    }

    /**
     * \~chinese
     * \brief
     * 创建聊天室
     * 
     * \details
     * 创建一个聊天室，并设置聊天室名称、聊天室描述、公开聊天室/私有聊天室属性、聊天室成员最大人数（包括管理员）、加入公开聊天室是否需要批准、管理员、以及聊天室成员。
     * 
     * @param  array  $name        聊天聊天室名称
     * @param  string $description 聊天室描述
     * @param  string $owner       聊天室的管理员
     * @param  array  $members     聊天室成员，此属性为可选的，但是如果加了此项，数组元素至少一个
     * @param  int    $maxusers    聊天室成员最大数（包括聊天室所有者），值为数值类型。
     * @return string|array        创建的聊天室 id 或者错误
     * 
     * \~english
     * \brief
     * Create a chat room
     * 
     * \details
     * Create a chat room, and set the chat room name, chat room description, public chat room / private chat room properties, the maximum number of chat room members (including administrators), whether approval is required to join the public chat room, administrators, and chat room members.
     * 
     * @param  array  $name        Chat room name
     * @param  string $description Chat room description
     * @param  string $owner       Chat room administrator
     * @param  array  $members     This attribute is optional for chat room members, but if this item is added, there must be at least one array element
     * @param  int    $maxusers    Maximum number of chat room members (including chat room owners). The value is numeric.
     * @return string|array        Created chat room ID or error
     */
    public function createRoom($name, $description, $owner, $members = array(), $maxusers = 0)
    {
        if (!trim($name)) {
            \Easemob\exception('Please pass the chat room name');
        }

        if (!trim($description)) {
            \Easemob\exception('Please pass the chat room description');
        }

        if (!trim($owner)) {
            \Easemob\exception('Please pass the chat room administrator');
        }

        if ($members && (!is_array($members) || empty($members))) {
            \Easemob\exception('Please pass chat room members');
        }

        $data = compact('name', 'description', 'owner', 'members');

        $maxusers = (int) $maxusers > 0 ? (int) $maxusers : 0;
        if ($maxusers) {
            $data['maxusers'] = $maxusers;
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms';
        $resp = Http::post($uri, $data, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data']['id'];
    }

    /**
     * \~chinese
     * \brief
     * 修改聊天室信息
     * 
     * \details
     * 修改成功的数据行会返回 true，失败为 false。请求 body 只接收 name、description、maxusers 三个属性。传其他字段，或者不能修改的字段会抛异常。
     * 
     * @param  array   $data 聊天室信息
     *     - `name` string 类型，聊天室名称，修改时值不能包含斜杠(“/”)。
     *     - `description` string 类型，聊天室描述，修改时值不能包含斜杠(“/”)。
     *     - `maxusers` int 类型，聊天室最大成员数（包括聊天室所有者），值为数值类型。
     * @return boolean|array 成功或者错误
     * 
     * \~english
     * \brief
     * Modify chat room information
     * 
     * \details
     * The modified data row will return true, and the failure will be false. The request body only receives three attributes: name, description and maxusers. Exceptions will be thrown if other fields are passed or fields that cannot be modified.
     * 
     * @param  array   $data Chat room information
     *     - `name` String type, chat room name. The value cannot contain slash ("/") when modified.
     *     - `description` String type, chat room description. When modifying, the value cannot contain slash ("/").
     *     - `maxusers` The type of chat room is int, and the value is the maximum number of chat room owners.
     * @return boolean|array Success or error
     */
    public function updateRoom($data)
    {
        if (!is_array($data) || empty($data)) {
            \Easemob\exception('Please pass the chat room information');
        }

        if (!isset($data['room_id']) || !trim($data['room_id'])) {
            \Easemob\exception('Please pass the chat room ID');
        }

        if (isset($data['name']) && preg_match('/\//', $data['name'])) {
            \Easemob\exception('Chat room names cannot contain slashes ("/")');
        }

        if (isset($data['description']) && preg_match('/\//', $data['description'])) {
            \Easemob\exception('Chat room description cannot contain slashes ("/")');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $data['room_id'];
        unset($data['room_id']);
        if (isset($data['maxusers'])) {
            $data['maxusers'] = (int) $data['maxusers'];
        }
        $resp = Http::put($uri, $data, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 删除聊天室
     * 
     * \details
     * 删除单个聊天室。如果被删除的聊天室不存在，会返回错误。
     * 
     * @param  string  $roomId 聊天室 ID
     * @return boolean|array   成功或者错误
     * 
     * \~english
     * \brief
     * Delete chat room
     * 
     * \details
     * Delete a single chat room. If the deleted chat room does not exist, an error will be returned.
     * 
     * @param  string  $roomId Chat room ID
     * @return boolean|array   Success or error
     */
    public function destroyRoom($roomId)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 获取聊天室公告
     * 
     * \details
     * 获取指定聊天室 ID 的聊天室公告。
     * 
     * @param  string $roomId 聊天室 id
     * @return array          公告信息或者错误
     * 
     * \~english
     * \brief
     * Get chat announcements
     * 
     * \details
     * Gets the chat announcement of the specified chat room ID.
     * 
     * @param  string $roomId Chat room ID
     * @return array           Announcement information or error
     */
    public function getRoomAnnouncement($roomId)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/announcement';
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data'];
    }

    /**
     * \~chinese
     * \brief
     * 修改聊天室公告
     * 
     * \details
     * 修改指定聊天室 ID 的聊天室公告。聊天室公告内容不能超过 512 个字符。
     * @param  string  $roomId       聊天室 ID
     * @param  string  $announcement 聊天室公告内容
     * @return boolean|array         成功或者错误
     * 
     * \~english
     * \brief
     * Modify chat announcement
     * 
     * \details
     * Modify the chat announcement of the specified chat ID. The content of chat room announcement cannot exceed 512 characters.
     * @param  string  $roomId       Chat room ID
     * @param  string  $announcement Chat room announcement content
     * @return boolean|array         Success or error
     */
    public function updateRoomAnnouncement($roomId, $announcement)
    {
        if (!trim($roomId) || !trim($announcement)) {
            \Easemob\exception('Please pass the chat room ID and announcement content');
        }

        if (mb_strlen($announcement) > 512) {
            \Easemob\exception('The content of the announcement room cannot exceed 512 characters');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/announcement';
        $body = compact('announcement');
        $resp = Http::post($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data']['result'];
    }

    /**
     * \~chinese
     * \brief
     * 分页获取聊天室成员
     * 
     * @param  string $roomId   聊天室 ID
     * @param  int    $pageSize 每页获取的群组数量，默认取 10 条
     * @param  int    $pageNum  当前页码，默认取第 1 页
     * @return array            聊天室成员信息或者错误
     * 
     * \~english
     * \brief
     * Paging to get chat room members
     * 
     * @param  string $roomId   Chat room ID
     * @param  int    $pageSize The number of groups obtained per page is 10 by default
     * @param  int    $pageNum  The current page number is page 1 by default
     * @return array            Chat room member information or error
     */
    public function listRoomMembers($roomId, $pageSize = 10, $pageNum = 1)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        $pageSize = (int) $pageSize >= 0 ? (int) $pageSize : 10;
        $pageNum = (int) $pageNum > 0 ? (int) $pageNum : 1;

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/users';
        $uri .= $pageSize ? ('?pagesize=' . $pageSize . '&pagenum=' . $pageNum) : '';
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data'];
    }

    /**
     * \~chinese
     * \brief
     * 获取聊天室所有成员
     * 
     * @param  string $roomId 聊天室 ID
     * @return array          聊天室成员信息或者错误
     * 
     * \~english
     * \brief
     * Get all members of the chat room
     * 
     * @param  string $roomId Chat room ID
     * @return array          Chat room member information or error
     */
    public function listRoomMembersAll($roomId)
    {
        return $this->listRoomMembers($roomId, 0);
    }

    /**
     * \~chinese
     * \brief
     * 添加单个聊天室成员
     * 
     * \details
     * 一次给聊天室添加一个成员，不能重复添加同一个成员。如果用户已经是聊天室成员，将添加失败，并返回错误。
     * 
     * @param  string $roomId   聊天室 ID
     * @param  string $username 环信用户 ID
     * @return boolean|array    成功或者错误
     * 
     * \~english
     * \brief
     * Add individual chat room members
     * 
     * \details
     * Add one member to the chat room at a time. You cannot add the same member repeatedly. If the user is already a member of the chat room, the addition will fail with an error.
     * 
     * @param  string $roomId   Chat room ID
     * @param  string $username User name
     * @return boolean|array    Success or error
     */
    public function addRoomMember($roomId, $username)
    {
        return $this->addUsers($roomId, $username);
    }

    /**
     * \~chinese
     * \brief
     * 批量添加聊天室成员
     * 
     * \details
     * 向聊天室添加多位用户，一次性最多可添加 60 位用户。
     * 
     * @param  string $roomId    聊天室 ID
     * @param  array  $usernames 环信用户 ID 数组
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Batch add chat members
     * 
     * \details
     * Add more than 60 users to the chat room at one time.
     * 
     * @param  string $roomId    Chat room ID
     * @param  array  $usernames User name array
     * @return boolean|array     Success or error
     */
    public function addRoomMembers($roomId, $usernames)
    {
        return $this->addUsers($roomId, $usernames);
    }

    /**
     * \~chinese
     * \brief
     * 删除单个聊天室成员
     * 
     * \details
     * 从聊天室删除一个成员。如果被删除用户不在聊天室中，或者聊天室不存在，将返回错误。
     * 
     * @param  string $roomId   聊天室 ID
     * @param  string $username 环信用户 ID
     * @return boolean|array    成功或者错误
     * 
     * \~english
     * \brief
     * Delete individual chat members
     * 
     * \details
     * Delete a member from the chat room. If the deleted user is not in the chat room, or the chat room does not exist, an error will be returned.
     * 
     * @param  string $roomId   Chat room ID
     * @param  string $username User name
     * @return boolean|array    Success or error
     */
    public function removeRoomMember($roomId, $username)
    {
        return $this->removeUsers($roomId, $username);
    }

    /**
     * \~chinese
     * \brief
     * 批量删除聊天室成员
     * 
     * \details
     * 从聊天室删除多个成员。如果被删除用户不在聊天室中，或者聊天室不存在，将返回错误。
     * 
     * 一次最多传 100 个用户 ID。
     * 
     * @param  string $roomId    聊天室 ID
     * @param  array  $usernames 环信用户 ID 数组
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Batch delete chat room members
     * 
     * \details
     * Delete multiple members from the chat room. If the deleted user is not in the chat room, or the chat room does not exist, an error will be returned.
     * 
     * Up to 100 user IDs can be transmitted at a time.
     * 
     * @param  string $roomId    Chat room ID
     * @param  array  $usernames User name array
     * @return boolean|array     Success or error
     */
    public function removeRoomMembers($roomId, $usernames)
    {
        return $this->removeUsers($roomId, $usernames);
    }

    /**
     * \~chinese
     * \brief
     * 获取聊天室管理员列表
     * 
     * @param  string $roomId 聊天室 ID
     * @return array          聊天室管理员列表信息或者错误
     * 
     * \~english
     * \brief
     * Get the list of chat room administrators
     * 
     * @param  string $roomId Chat room ID
     * @return array          Chat room administrator list information or error
     */
    public function listRoomAdminsAll($roomId)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/admin';
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data'];
    }

    /**
     * \~chinese
     * \brief
     * 添加聊天室管理员
     * 
     * @param  string  $roomId   聊天室 ID
     * @param  string  $newadmin 添加的新管理员用户 ID
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Add chat administrator
     * 
     * @param  string  $roomId   Chat room ID
     * @param  string  $newadmin New administrator user ID added
     * @return boolean|array     Success or error
     */
    public function promoteRoomAdmin($roomId, $newadmin)
    {
        if (!trim($roomId) || !trim($newadmin)) {
            \Easemob\exception('Please pass the chat room ID and the new administrator user ID to be added');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/admin';
        $body = compact('newadmin');
        $resp = Http::post($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 移除聊天室管理员
     * 
     * \details
     * 将用户的角色从聊天室管理员降为普通聊天室成员。
     * 
     * @param  string  $roomId   聊天室 ID
     * @param  string  $oldadmin 移除的管理员用户 ID
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Remove chat admin
     * 
     * \details
     * Reduce the user's role from chat room administrator to ordinary chat room member.
     * 
     * @param  string  $roomId   Chat room ID
     * @param  string  $oldadmin Removed administrator user ID
     * @return boolean|array     Success or error
     */
    public function demoteRoomAdmin($roomId, $oldadmin)
    {
        if (!trim($roomId) || !trim($oldadmin)) {
            \Easemob\exception('Please pass the chat room ID and the administrator user ID to be removed');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/admin/' . $oldadmin;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data']['result'] === 'success' ? true : false;
    }

    /**
     * \~chinese
     * \brief
     * 分页获取聊天室超级管理员列表
     * 
     * @param  int   $pageSize 每页获取的数量，默认取 10 条
     * @param  int   $pageNum  当前页码，默认取第 1 页
     * @return array           超级管理员列表信息或者错误
     * 
     * \~english
     * \brief
     * Paging to get the list of chat room super administrators
     * 
     * @param  int   $pageSize The quantity obtained per page is 10 by default
     * @param  int   $pageNum  The current page number is page 1 by default
     * @return array           Super administrator list information or error
     */
    public function listRoomSuperAdmins($pageSize = 10, $pageNum = 1)
    {
        return $this->superAdmins($pageSize, $pageNum);
    }

    /// @cond
    public function listRoomSuperAdminsAll()
    {
        return $this->superAdmins(0);
    }
    /// @endcond

    /**
     * \~chinese
     * \brief
     * 添加超级管理员
     * 
     * \details
     * 给用户添加聊天室超级管理员身份，一次只能添加一个。
     * 
     * @param  string  $superadmin 添加的用户名称
     * @return boolean|array       成功或者错误
     * 
     * \~english
     * \brief
     * Add super administrator
     * 
     * \details
     * Add the chat room super administrator identity to users. You can only add one at a time.
     * 
     * @param  string  $superadmin User name
     * @return boolean|array       Success or error
     */
    public function promoteRoomSuperAdmin($superadmin)
    {
        if (!is_string($superadmin) || !trim($superadmin)) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/super_admin';
        $resp = Http::post($uri, compact('superadmin'), $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 撤销超级管理员
     * 
     * @param  string  $superadmin 需要移除的 IM 用户名
     * @return boolean|array       成功或者错误
     * 
     * \~chinese
     * \brief
     * 撤销超级管理员
     * 
     * @param  string  $superadmin User name
     * @return boolean|array       Success or error
     */
    public function demoteRoomSuperAdmin($superadmin)
    {
        if (!trim($superadmin)) {
            \Easemob\exception('Please pass the user name');
        }
        $uri = $this->auth->getBaseUri() . '/chatrooms/super_admin/' . $superadmin;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * @ignore （批量）添加聊天室成员
     * @param  string       $roomId    聊天室 ID
     * @param  string|array $usernames 环信用户 ID
     * @return boolean|array           成功或者错误
     */
    private function addUsers($roomId, $usernames)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        if ((is_array($usernames) && empty($usernames)) || (is_string($usernames) && !trim($usernames))) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/users';
        $uri .= is_array($usernames) ? '' : ('/' . $usernames);
        $body = is_array($usernames) ? compact('usernames') : null;
        $resp = Http::post($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * @ignore （批量）移除聊天室成员
     * @param  string       $roomId    聊天室 ID
     * @param  string|array $usernames 环信用户 ID，string: 移除单个成员；array: 批量移除成员
     * @return boolean|array           成功或者错误
     */
    private function removeUsers($roomId, $usernames)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        if ((is_array($usernames) && empty($usernames)) || (is_string($usernames) && !trim($usernames))) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/users/';
        $uri .= is_array($usernames) ? implode(',', $usernames) : $usernames;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * @ignore 分页获取聊天室超级管理员列表
     * @param  int    $pageSize 每页获取的群组数量，默认取 10 条
     * @param  int    $pageNum  当前页码，默认取第 1 页
     * @return array            超级管理员列表信息或者错误
     */
    private function superAdmins($pageSize = 10, $pageNum = 1)
    {
        $pageSize = (int) $pageSize >= 0 ? (int) $pageSize : 10;
        $pageNum = (int) $pageNum > 0 ? (int) $pageNum : 1;

        $uri = $this->auth->getBaseUri() . '/chatrooms/super_admin';
        $uri .= $pageSize ? ('?pagesize=' . $pageSize . '&pagenum=' . $pageNum) : '';

        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data'];
    }
}