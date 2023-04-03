<?php
namespace Easemob;

use Easemob\Http\Http;

/**
 * \~chinese
 * WhiteList 用于管理群组、聊天室白名单
 * 
 * \~english
 * The `WhiteList` used to manage groups, chat rooms white lists
 */
final class WhiteList
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

    // Group
    /**
     * \~chinese
     * \brief
     * 查询群组白名单
     * 
     * \details
     * 查询一个群组白名单中的用户列表。
     * 
     * @param  string $groupId 群组 id
     * @return array           白名单列表或者错误
     * 
     * \~english
     * \brief
     * Query group whitelist
     * 
     * \details
     * Query the list of users in a group white list.
     * 
     * @param  string $groupId group ID
     * @return array           Whitelist or error
     */
    public function getGroupWhiteList($groupId)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please pass the group ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/white/users';
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
     * 添加单个用户至群组白名单
     * 
     * \details
     * 查询一个群组将指定的单个用户添加至群组白名单。用户在添加至群组白名单后，当群组全员被禁言时，仍可以在群组中发送消息。白名单中的用户列表。
     * 
     * @param  string $groupId  群组 id
     * @param  string $username 用户名
     * @return boolean|array    成功或者错误
     * 
     * \~english
     * \brief
     * Add a single user to the group whitelist
     * 
     * \details
     * Query a group and add the specified individual user to the group white list. After being added to the group white list, users can still send messages in the group when all members of the group are banned. List of users in the whitelist.
     * 
     * @param  string $groupId  Group id
     * @param  string $username user name
     * @return boolean|array    Success or error
     */
    public function addUserToGroupWhiteList($groupId, $username)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please pass the group ID');
        }

        if (!trim($username)) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/white/users/' . $username;
        $resp = Http::post($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 批量添加用户至群组白名单
     * 
     * \details
     * 添加多个用户至群组白名单。你一次最多可添加 60 个用户。用户添加至白名单后在群组全员禁言时仍可以在群组中发送消息。
     * 
     * @param  string $groupId   群组 id
     * @param  array  $usernames 用户名数组
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Batch add users to the group whitelist
     * 
     * \details
     * Add multiple users to the group whitelist. You can add up to 60 users at a time. After users are added to the white list, they can still send messages in the group when all members of the group are forbidden.
     * 
     * @param  string $groupId   Group id
     * @param  array  $usernames User name array
     * @return boolean|array     Success or error
     */
    public function addUsersToGroupWhiteList($groupId, $usernames)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please pass the group ID');
        }

        if (!is_array($usernames) || empty($usernames)) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/white/users';
        $body = compact('usernames');
        $resp = Http::post($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 将用户移除群组白名单
     * 
     * \details
     * 将指定用户从群组白名单中移除。你每次最多可移除 60 个用户。
     * 
     * @param  string $groupId  群组 id
     * @param  string $username 用户名，可以以逗号分隔传递多个用户名
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Remove user from group whitelist
     * 
     * \details
     * Removes the specified user from the group whitelist. You can remove up to 60 users at a time.
     * 
     * @param  string $groupId   Group ID
     * @param  string $username  User name. Multiple user names can be passed in comma separated form
     * @return boolean|array     Success or error
     */
    public function removeUsersFromGroupWhiteList($groupId, $username)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please pass the group ID');
        }

        if (!trim($username)) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/white/users/' . $username;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    // Room
    /**
     * \~chinese
     * \brief
     * 查询聊天室白名单
     * 
     * \details
     * 查询一个聊天室白名单中的用户列表。
     * 
     * @param  string $roomId 聊天室 id
     * @return array          白名单列表或者错误
     * 
     * \~english
     * \brief
     * Query chat room white list
     * 
     * \details
     * Query the list of users in a chat room white list.
     * 
     * @param  string $roomId Chat room ID
     * @return array          Whitelist or error
     */
    public function getRoomWhiteList($roomId)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/white/users';
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
     * 添加单个用户至聊天室白名单
     * 
     * \details
     * 将指定的单个用户添加至聊天室白名单。用户添加至聊天室白名单后，当聊天室全员禁言时，仍可以在聊天室中发送消息。
     * 
     * @param  string $roomId   聊天室 id
     * @param  string $username 用户名
     * @return boolean|array    成功或者错误
     * 
     * \~english
     * \brief
     * Add a single user to the chat room whitelist
     * 
     * \details
     * Adds the specified individual user to the chat room whitelist. After the user is added to the chat room white list, when the chat room is forbidden, he can still send messages in the chat room.
     * 
     * @param  string $roomId   Chat room id
     * @param  string $username user name
     * @return boolean|array    Success or error
     */
    public function addUserToRoomWhiteList($roomId, $username)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        if (!trim($username)) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/white/users/' . $username;
        $resp = Http::post($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 批量添加用户至聊天室白名单
     * 
     * \details
     * 添加多个用户至聊天室白名单。你一次最多可添加 60 个用户。用户添加至聊天室白名单后，在聊天室全员禁言时，仍可以在聊天室中发送消息。
     * 
     * @param  string $roomId    聊天室 id
     * @param  array  $usernames 用户名数组
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Batch add users to chat room white list
     * 
     * \details
     * Add multiple users to the chat room whitelist. You can add up to 60 users at a time. After users are added to the chat room white list, they can still send messages in the chat room when all members of the chat room are forbidden to speak.
     * 
     * @param  string $roomId    Chat room id
     * @param  array  $usernames User name array
     * @return boolean|array     Success or error
     */
    public function addUsersToRoomWhiteList($roomId, $usernames)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        if (!is_array($usernames) || empty($usernames)) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/white/users';
        $body = compact('usernames');
        $resp = Http::post($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 将用户移除聊天室白名单
     * 
     * \details
     * 将指定用户从聊天室白名单移除。你每次最多可移除 60 个用户。
     * 
     * @param  string $roomId   聊天室 id
     * @param  string $username 用户名，可以以逗号分隔传递多个用户名
     * @return boolean|array    成功或者错误
     * 
     * \~english
     * \brief
     * Remove user from chat room whitelist
     * 
     * \details
     * Remove the specified user from the chat room whitelist. You can remove up to 60 users at a time.
     * 
     * @param  string $roomId   Chat room ID
     * @param  string $username User name. Multiple user names can be passed in comma separated form
     * @return boolean|array    Success or error
     */
    public function removeUsersFromRoomWhiteList($roomId, $username)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        if (!trim($username)) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/white/users/' . $username;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }
}