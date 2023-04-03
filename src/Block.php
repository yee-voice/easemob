<?php

namespace Easemob;

use Easemob\Http\Http;

/**
 * \~chinese
 * Block 用于限制访问(将用户加入黑名单、群组/聊天室禁言等)
 * 
 * \~english
 * The `Block` is used to restrict access (add users to blacklists, group / chat room prohibitions, etc.)
 */
final class Block
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
     * 获取用户黑名单
     * 
     * @param  string $username 要获取黑名单的用户
     * @return array            黑名单用户名列表或者错误
     * 
     * \~english
     * \brief
     * Get user blacklist
     * 
     * @param  string $username User name
     * @return array            Blacklist user name list or error
     */
    public function getUsersBlockedFromSendMsgToUser($username)
    {
        if (!trim($username)) {
            \Easemob\exception('Please enter username');
        }
        $uri = $this->auth->getBaseUri() . '/users/' . $username . '/blocks/users';
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
     * 添加用户黑名单
     * 
     * \details
     * 向用户的黑名单列表中添加一个或者多个用户，黑名单中的用户无法给该用户发送消息，每个用户的黑名单人数上限为 500。
     * 
     * @param  string  $username  要添加黑名单的用户名
     * @param  array   $usernames 需要加入到黑名单中的用户名，以数组方式提交
     * @return boolean|array      成功或者错误
     * 
     * \~english
     * \brief
     * Add user blacklist
     * 
     * \details
     * Add one or more users to the user's blacklist. Users in the blacklist cannot send messages to the user. The maximum number of blacklists for each user is 500.
     * 
     * @param  string  $username  User name
     * @param  array   $usernames The user names that need to be added to the blacklist shall be submitted in the form of array
     * @return boolean|array      Success or error
     */
    public function blockUserSendMsgToUser($username, $usernames = array())
    {
        if (!trim($username)) {
            \Easemob\exception('Please enter username');
        }
        if (!is_array($usernames)) {
            \Easemob\exception('Please pass in the user array to be blacklisted');
        }
        $uri = $this->auth->getBaseUri() . '/users/' . $username . '/blocks/users';
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
     * 移除用户黑名单
     * 
     * \details
     * 从用户的黑名单中移除用户。将用户从黑名单移除后，恢复到好友，或者未添加好友的用户关系。可以正常的进行消息收发。
     * 
     * @param  string  $username        要移除黑名单的用户名
     * @param  string  $friend_username 好友用户名
     * @return boolean|array            成功或者错误
     * 
     * \~english
     * \brief
     * Remove user blacklist
     * 
     * \details
     * Remove the user from the user's blacklist. After the user is removed from the blacklist, it can be restored to friends, or the user relationship without adding friends. Can send and receive messages normally.
     * 
     * @param  string  $username        User name
     * @param  string  $friend_username Friends user name
     * @return boolean|array            Success or error
     */
    public function unblockUserSendMsgToUser($username, $blocked_username)
    {
        if (!trim($username) || !trim($blocked_username)) {
            \Easemob\exception('Please enter the user name and the user name to remove the blacklist');
        }
        $uri = $this->auth->getBaseUri() . '/users/' . $username . '/blocks/users/' . $blocked_username;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 账号封禁
     * 
     * \details
     * 用户若被禁用将立即下线并无法登录，直到被解禁后才能恢复登录。常用在对异常用户的即时处理场景使用。
     * 
     * @param  string  $username 要封禁的用户名
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Account number ban
     * 
     * \details
     * If the user is disabled, he will be offline immediately and cannot log in until he is lifted. It is often used in the immediate processing of abnormal users.
     * 
     * @param  string  $username User name
     * @return boolean|array     Success or error
     */
    public function blockUserLogin($username)
    {
        return $this->activate($username, 0);
    }

    /**
     * \~chinese
     * \brief
     * 账号解禁
     * 
     * \details
     * 用户若被禁用将立即下线并无法登录，直到被解禁后才能恢复登录。常用在对异常用户的即时处理场景使用。
     * 
     * @param  string  $username 要解禁的用户名
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Account lifting
     * 
     * \details
     * If the user is disabled, he will be offline immediately and cannot log in until he is lifted. It is often used in the immediate processing of abnormal users.
     * 
     * @param  string  $username User name
     * @return boolean|array     Success or error
     */
    public function unblockUserLogin($username)
    {
        return $this->activate($username);
    }

    /**
     * \~chinese
     * \brief
     * 设置用户全局禁言
     * 
     * \details
     * 设置单个用户 ID 的单聊、群组、聊天室消息全局禁言。
     * 
     * @param  string $username              用户名
     * @param  int    $chatMuteDuration      单聊消息禁言时间，单位为秒，非负整数，最大值为 2147483647，`0` 表示取消该帐号的单聊消息禁言，`-1` 表示该帐号被设置永久禁言，其它值表示该帐号的具体禁言时间，负值为非法值。
     * @param  int    $groupchatMuteDuration 群组消息禁言时间，单位为秒，规则同上。
     * @param  int    $chatroomMuteDuration  聊天室消息禁言时间，单位为秒，规则同上。
     * @return boolean|array                 成功或者错误
     * 
     * \~english
     * \brief
     * Set user global prohibitions
     * 
     * \details
     * Set the global prohibition of single chat, group and chat room messages of a single user ID.
     * 
     * @param  string $username              User name
     * @param  int    $chatMuteDuration      Single chat message forbidden time, in seconds, is a non negative integer. The maximum value is 2147483647, ` 0 'means to cancel the forbidden time of single chat messages of the account, ` - 1' means that the account is set with permanent forbidden time, other values mean the specific forbidden time of the account, and negative values are illegal values.
     * @param  int    $groupchatMuteDuration Group message forbidden time, in seconds, and the rules are the same as above.
     * @param  int    $chatroomMuteDuration  Chat room message forbidden time, in seconds, and the rules are the same as above.
     * @return boolean|array                 Success or error
     */
    public function blockUserSendMsg($username, $chatMuteDuration = -1, $groupchatMuteDuration = -1, $chatroomMuteDuration = -1)
    {
        return $this->blockUserSendMsgGlobal($username, $chatMuteDuration, $groupchatMuteDuration, $chatroomMuteDuration);
    }

    /**
     * \~chinese
     * \brief
     * 解除用户全局禁言
     * 
     * @param  string $username 解除禁言的用户名
     * @return boolean|array    成功或者错误
     * 
     * \~english
     * \brief
     * Lifting the user's global prohibition
     * 
     * @param  string $username User name
     * @return boolean|array    Success or error
     */
    public function unblockUserSendMsg($username)
    {
        return $this->blockUserSendMsgGlobal($username, 0, 0, 0);
    }

    /**
     * \~chinese
     * \brief
     * 查询单个用户 ID 全局禁言
     * 
     * \details
     * 查询单个用户的单聊/群聊/聊天室消息禁言。
     * 
     * @param  string $username 查询禁言信息的用户名
     * @return array            用户全局禁言信息或者错误
     * 
     * \~english
     * \brief
     * Query single user ID global prohibitions
     * 
     * \details
     * Query the forbidden words of single chat / group chat / chat room messages of a single user.
     * 
     * @param  string $username User name
     * @return array            User global forbidden information or error
     */
    public function getUserBlocked($username)
    {
        if (!trim($username)) {
            \Easemob\exception('Please enter username');
        }

        $uri = $this->auth->getBaseUri() . '/mutes/' . $username;
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
     * 查询APPKEY的用户禁言
     * 
     * \details
     * 查询 App Key 下的用户禁言剩余时间的集合。
     * 
     * @param  int   $pageSize 请求查询每页显示的禁言用户的数量，默认取 10 条
     * @param  int   $pageNum  请求查询的页码，默认取第 1 页
     * @return array           用户禁言信息或者错误
     * 
     * \~english
     * \brief
     * User prohibitions for querying appkey
     * 
     * \details
     * Query the collection of the remaining time of the user's forbidden words under the app key.
     * 
     * @param  int   $pageSize Request to query the number of forbidden users displayed on each page. The default is 10
     * @param  int   $pageNum  The page number requested for query is page 1 by default
     * @return array           User forbidden information or error
     */
    public function getAppBlocked($pageSize = 10, $pageNum = 1)
    {
        $pageSize = (int)$pageSize > 0 ? (int)$pageSize : 10;
        $pageNum = (int)$pageNum > 0 ? (int)$pageNum : 1;

        $uri = $this->auth->getBaseUri() . '/mutes';
        $uri .= $pageSize ? ('?pagesize=' . $pageSize . '&pagenum=' . $pageNum) : '';

        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return isset($data['data']) ? $data['data'] : $data;
    }

    // Group
    /**
     * \~chinese
     * \brief
     * 查询群组黑名单
     * 
     * \details
     * 查询一个群组黑名单中的用户列表。位于黑名单中的用户查看不到该群组的信息，也无法收到该群组的消息。
     * 
     * @param  string $groupId 群组 ID
     * @return array           群组黑名单信息或者错误
     * 
     * 
     * \~english
     * \brief
     * Query group blacklist
     * 
     * \details
     * Query the list of users in a group blacklist. Users in the blacklist cannot view the information of the group or receive the message of the group.
     * 
     * @param  string $groupId Group ID
     * @return array           Group blacklist information or error
     */
    public function getUsersBlockedJoinGroup($groupId)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please pass the group ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/blocks/users';
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
     * 添加单个用户至群组黑名单
     * 
     * \details
     * 添加一个用户进入一个群组的黑名单。群主无法被加入群组的黑名单。
     * 
     * 用户进入群组黑名单后，会收到消息：You are kicked out of the group xxx。之后，该用户查看不到该群组的信息，也收不到该群组的消息。
     * 
     * @param  string $groupId  群组 ID
     * @param  string $username 要添加的 IM 用户名
     * @return boolean|array    成功或者错误
     * 
     * \~english
     * \brief
     * Add a single user to the group blacklist
     * 
     * \details
     * Add a user to the blacklist of a group. The group leader cannot be added to the blacklist of the group.
     * 
     * After entering the group blacklist, the user will receive a message: you are kicked out of the group XXX. After that, the user cannot view the information of the group or receive the message of the group.
     * 
     * @param  string $groupId  Group ID
     * @param  string $username User name
     * @return boolean|array    Success or error
     */
    public function blockUserJoinGroup($groupId, $username)
    {
        return $this->addGroupBlocks($groupId, $username);
    }

    /**
     * \~chinese
     * \brief
     * 批量添加用户至群组黑名单
     * 
     * \details
     * 将多个用户添加一个群组的黑名单。你一次最多可以添加 60 个用户至群组黑名单。群主无法被加入群组的黑名单。
     * 
     * 用户进入群组黑名单后，会收到消息：You are kicked out of the group xxx。之后，该用户查看不到该群组的信息，也收不到该群组的消息。
     *
     * @param  string $groupId   群组 ID
     * @param  array  $usernames 要添加的 IM 用户名数组
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Batch add users to group blacklist
     * 
     * \details
     * Add multiple users to the blacklist of a group. You can add up to 60 users to the group blacklist at a time. The group leader cannot be added to the blacklist of the group.
     * 
     * After entering the group blacklist, the user will receive a message: you are kicked out of the group XXX. After that, the user cannot view the information of the group or receive the message of the group.
     * 
     * @param  string $groupId   Group ID
     * @param  array  $usernames User name array
     * @return boolean|array     Success or error
     */
    public function blockUsersJoinGroup($groupId, $usernames)
    {
        return $this->addGroupBlocks($groupId, $usernames);
    }

    /**
     * \~chinese
     * \brief
     * 从群组黑名单移除单个用户
     * 
     * \details
     * 将指定用户移出群组黑名单。对于群组黑名单中的用户，如果需要将其再次加入群组，需要先将其从群组黑名单中移除。
     * 
     * @param  string $groupId  群组 ID
     * @param  string $username 要移除的用户名
     * @return boolean|array    成功或者错误
     * 
     * \~english
     * \brief
     * Remove a single user from the group blacklist
     * 
     * \details
     * Remove the specified user from the group blacklist. For users in the group blacklist, if they need to join the group again, they need to be removed from the group blacklist first.
     * 
     * @param  string $groupId  Group ID
     * @param  string $username User name
     * @return boolean|array    Success or error
     */
    public function unblockUserJoinGroup($groupId, $username)
    {
        return $this->removeGroupBlocks($groupId, $username);
    }

    /**
     * \~chinese
     * \brief
     * 从群组黑名单批量移除用户
     * 
     * \details
     * 将多名指定用户从群组黑名单中移除。对于群组黑名单中的用户，如果需要将其再次加入群组，需要先将其从群组黑名单中移除。
     * 
     * @param  string $groupId   群组 ID
     * @param  array  $usernames 要添加的 IM 用户名数组
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Batch remove users from group blacklist
     * 
     * \details
     * Remove multiple designated users from the group blacklist. For users in the group blacklist, if they need to join the group again, they need to be removed from the group blacklist first.
     * 
     * @param  string $groupId   Group ID
     * @param  array  $usernames User name array
     * @return boolean|array     Success or error
     */
    public function unblockUsersJoinGroup($groupId, $usernames)
    {
        return $this->removeGroupBlocks($groupId, $usernames);
    }

    /**
     * \~chinese
     * \brief
     * 添加群组禁言
     * 
     * \details
     * 对指定群成员禁言。群成员被禁言后，将无法在群中发送消息。
     * 
     * @param  string  $groupId       群组 ID
     * @param  array   $usernames     要被添加禁言的用户 ID 数组
     * @param  int     $mute_duration 禁言时长，单位为毫秒。
     * @return boolean|array          成功或者错误
     * 
     * 
     * \~english
     * \brief
     * Add group forbidden words
     * 
     * \details
     * No speaking to designated group members. After group members are banned, they will not be able to send messages in the group.
     * 
     * @param  string  $groupId       Group ID
     * @param  array   $usernames     User name array
     * @param  int     $mute_duration Forbidden speech duration, in milliseconds.
     * @return boolean|array          Success or error
     */
    public function blockUserSendMsgToGroup($groupId, $usernames, $mute_duration = -1)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please pass the group ID');
        }

        if (!is_array($usernames) || empty($usernames)) {
            \Easemob\exception('Please pass the user name');
        }

        $mute_duration = (int)$mute_duration > 0 ? (int)$mute_duration : -1;
        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/mute';
        $resp = Http::post($uri, compact('usernames', 'mute_duration'), $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 解除群组成员禁言
     * 
     * \details
     * 将一个或多个群成员移除禁言列表。移除后，群成员可以在群组中正常发送消息。
     * 
     * @param  string  $groupId   群组 ID
     * @param  array   $usernames 要移除禁言的用户 ID 数组
     * @return boolean|array      成功或者错误
     * 
     * \~english
     * \brief
     * Release group members from prohibition
     * 
     * \details
     * Remove one or more group members from the forbidden list. After removal, group members can send messages normally in the group.
     * 
     * @param  string  $groupId   Group ID
     * @param  array   $usernames User name array
     * @return boolean|array      Success or error
     */
    public function unblockUserSendMsgToGroup($groupId, $usernames)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please pass the group ID');
        }

        if (!is_array($usernames) || empty($usernames)) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/mute/' . implode(',', $usernames);
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 获取禁言列表
     * 
     * \details
     * 获取当前群组的禁言用户列表。
     * 
     * @param  string $groupId 群组 ID
     * @return array           禁言列表信息或者错误
     * 
     * \~english
     * \brief
     * Get forbidden list
     * 
     * \details
     * Get the list of forbidden users in the current group.
     * 
     * @param  string $groupId Group ID
     * @return array           Forbidden list information or error
     */
    public function getUsersBlockedSendMsgToGroup($groupId)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please pass the group ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/mute';
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
     * 禁言群组全体成员
     * 
     * \details
     * 对所有群组成员一键禁言，即将群组的所有成员均加入禁言列表。设置群组全员禁言后，仅群组白名单中的用户可在群组内发消息
     * 
     * @param  string  $groupId       群组 ID
     * @param  int     $mute_duration 禁言时长，单位为毫秒。
     * @return boolean|array          成功或者错误
     * 
     * \~english
     * \brief
     * Forbidden all members of the group
     * 
     * \details
     * One click prohibition for all group members, that is, all members of the group will be added to the prohibition list. After setting the prohibition of all members of the group, only users in the group white list can send messages in the group
     * 
     * @param  string  $groupId       Group ID
     * @param  int     $mute_duration Forbidden speech duration, in milliseconds.
     * @return boolean|array          Success or error
     */
    public function blockAllUserSendMsgToGroup($groupId, $mute_duration = -1)
    {

        if (!trim($groupId)) {
            \Easemob\exception('Please pass the group ID');
        }

        $mute_duration = (int)$mute_duration > 0 ? (int)$mute_duration : -1;
        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/ban';
        $resp = Http::post($uri, compact('mute_duration'), $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 解除群组全员禁言
     * 
     * \details
     * 一键取消对群组全体成员的禁言。移除后，群成员可以在群组中正常发送消息。
     * 
     * @param  string  $groupId 群组 ID
     * @return boolean|array    成功或者错误
     * 
     * \~english
     * \brief
     * Lifting the ban on all members of the group
     * 
     * \details
     * One click to cancel the ban on all members of the group. After removal, group members can send messages normally in the group.
     * 
     * @param  string  $groupId Group ID
     * @return boolean|array    Success or error
     */
    public function unblockAllUserSendMsgToGroup($groupId)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please pass the group ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/ban';
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
     * 查询聊天室黑名单
     * 
     * \details
     * 查询一个聊天室黑名单中的用户列表。黑名单中的用户无法查看或收到该聊天室的信息。
     * 
     * @param  string $roomId 聊天室 ID
     * @return array          聊天室黑名单信息或者错误
     * 
     * \~english
     * \brief
     * Query chat room blacklist
     * 
     * \details
     * Query the list of users in a chat room blacklist. Users in the blacklist cannot view or receive information from this chat room.
     * 
     * @param  string $roomId Chat room ID
     * @return array          Chat room blacklist information or error
     */
    public function getUsersBlockedJoinRoom($roomId)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/blocks/users';
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
     * 添加单个用户至聊天室黑名单
     * 
     * \details
     * 添加一个用户进入一个聊天室的黑名单。聊天室所有者无法被加入聊天室的黑名单。
     * 
     * 用户进入聊天室黑名单后，会收到消息：“You are kicked out of the chatroom xxx”。之后，该用户无法查看和收发该聊天室的信息。
     * 
     * @param  string $roomId   聊天室 ID
     * @param  string $username 要添加的 IM 用户名
     * @return boolean|array    成功或者错误
     * 
     * \~english
     * \brief
     * Add a single user to the chat room blacklist
     * 
     * \details
     * Add a user to the blacklist of a chat room. Chat room owners cannot be blacklisted.
     * 
     * After entering the chat room blacklist, users will receive a message: "you are kicked out of the chat room XXX". After that, the user cannot view and send the information of the chat room.
     * 
     * @param  string $roomId   Chat room ID
     * @param  string $username User name
     * @return boolean|array    Success or error
     */
    public function blockUserJoinRoom($roomId, $username)
    {
        return $this->addRoomBlocks($roomId, $username);
    }

    /**
     * \~chinese
     * \brief
     * 批量添加用户至聊天室黑名单
     * 
     * \details
     * 将多个用户加入一个聊天室的黑名单。你一次最多可以添加 60 个用户至聊天室黑名单。聊天室所有者无法被加入聊天室的黑名单。
     * 
     * 用户进入聊天室黑名单后，会收到消息：“You are kicked out of the chatroom xxx”。之后，这些用户无法查看和收发该聊天室的信息。
     * 
     * @param  string $roomId    聊天室 ID
     * @param  array  $usernames 要添加的 IM 用户名数组
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Batch add users to chat room blacklist
     * 
     * \details
     * Add multiple users to the blacklist of a chat room. You can add up to 60 users to the chat room blacklist at a time. Chat room owners cannot be blacklisted.
     * 
     * After entering the chat room blacklist, users will receive a message: "you are kicked out of the chat room XXX". After that, these users cannot view and send the information of the chat room.
     * 
     * @param  string $roomId    Chat room ID
     * @param  array  $usernames User name array
     * @return boolean|array     Success or error
     */
    public function blockUsersJoinRoom($roomId, $usernames)
    {
        return $this->addRoomBlocks($roomId, $usernames);
    }

    /**
     * \~chinese
     * \brief
     * 从聊天室黑名单移除单个用户
     * 
     * \details
     * 将指定用户移出聊天室黑名单。对于聊天室黑名单中的用户，如果需要将其再次加入聊天室，需要先将其从聊天室黑名单中移除。
     * 
     * @param  string $roomId   聊天室 ID
     * @param  string $username 要添加的 IM 用户名
     * @return boolean|array    成功或者错误
     * 
     * \~english
     * \brief
     * Remove a single user from the chat room blacklist
     * 
     * \details
     * Remove the specified user from the chat room blacklist. For users in the chat room blacklist, if they need to join the chat room again, they need to be removed from the chat room blacklist first.
     * 
     * @param  string $roomId   Chat room ID
     * @param  string $username User name
     * @return boolean|array    Success or error
     */
    public function unblockUserJoinRoom($roomId, $username)
    {
        return $this->removeRoomBlocks($roomId, $username);
    }

    /**
     * \~chinese
     * \brief
     * 从聊天室黑名单批量移除用户
     * 
     * \details
     * 将多名指定用户从聊天室黑名单中移除。你每次最多可移除 60 个用户。对于聊天室黑名单中的用户，如果需要将其再次加入聊天室，需要先将其从聊天室黑名单中移除。
     * 
     * @param  string $roomId    聊天室 ID
     * @param  array  $usernames 要添加的 IM 用户名数组
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Batch remove users from chat room blacklist
     * 
     * \details
     * Remove multiple designated users from the chat room blacklist. You can remove up to 60 users at a time. For users in the chat room blacklist, if they need to join the chat room again, they need to be removed from the chat room blacklist first.
     * 
     * @param  string $roomId    Chat room ID
     * @param  array  $usernames User name array
     * @return boolean|array     Success or error
     */
    public function unblockUsersJoinRoom($roomId, $usernames)
    {
        return $this->removeRoomBlocks($roomId, $usernames);
    }

    /**
     * \~chinese
     * \brief
     * 禁言聊天室成员
     * 
     * \details
     * 将用户禁言。用户被禁言后，将无法在聊天室中发送消息。
     * 
     * @param  string  $roomId        聊天室 ID
     * @param  array   $usernames     要被添加禁言的用户 ID 数组
     * @param  int     $mute_duration 禁言的时间，单位毫秒，如果是“-1”代表永久（实际的到期时间为固定时间戳4638873600000，即2117-01-01 00:00:00）
     * @return boolean|array          成功或者错误
     * 
     * \~english
     * \brief
     * Forbidden chat room members
     * 
     * \details
     * Forbid users from speaking. After being banned, users will not be able to send messages in the chat room.
     * 
     * @param  string  $roomId        Chat room ID
     * @param  array   $usernames     User name array
     * @param  int     $mute_duration Forbidden time, in milliseconds. If "- 1" means permanent (the actual expiration time is the fixed timestamp 4638873600000, i.e. 2117-01-01 00:00:00)
     * @return boolean|array          Success or error
     */
    public function blockUserSendMsgToRoom($roomId, $usernames, $mute_duration = -1)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        if (!is_array($usernames) || empty($usernames)) {
            \Easemob\exception('Please pass the user name');
        }

        $mute_duration = (int)$mute_duration > 0 ? (int)$mute_duration : -1;
        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/mute';
        $resp = Http::post($uri, compact('usernames', 'mute_duration'), $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 解除聊天室禁言成员
     * 
     * \details
     * 将用户从禁言列表中移除，可以移除多个 member。移除后，用户可以正常在聊天室中发送消息。
     * 
     * @param  string  $roomId    聊天室 ID
     * @param  array   $usernames 要移除禁言的用户 ID 数组
     * @return boolean|array      成功或者错误
     * 
     * \~english
     * \brief
     * Unblock chat room members
     * 
     * \details
     * You can remove multiple members by removing users from the forbidden list. After removal, users can send messages in the chat room normally.
     * 
     * @param  string  $roomId    Chat room ID
     * @param  array   $usernames User name array
     * @return boolean|array      Success or error
     */
    public function unblockUserSendMsgToRoom($roomId, $usernames)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        if (!is_array($usernames) || empty($usernames)) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/mute/' . implode(',', $usernames);
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 获取聊天室禁言列表
     * 
     * @param  string $roomId  聊天室 ID
     * @return array           禁言列表信息或者错误
     * 
     * \~english
     * \brief
     * Get chat room forbidden list
     * 
     * @param  string $roomId  Chat room ID
     * @return array           Forbidden list information or error
     */
    public function getUsersBlockedSendMsgToRoom($roomId)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/mute';
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return isset($data['data']) ? $data['data'] : $data;
    }

    /**
     * \~chinese
     * \brief
     * 禁言聊天室全体成员
     * 
     * \details
     * 对所有聊天室成员一键禁言，即将聊天室的所有成员均加入禁言列表。设置聊天室全员禁言后，仅聊天室白名单中的用户可在聊天室内发消息。
     * @param  string  $roomId        聊天室 ID
     * @param  int     $mute_duration 禁言时长，单位为毫秒。
     * @return boolean|array          成功或者错误
     * 
     * \~english
     * \brief
     * Forbidden all members of the room
     * 
     * \details
     * One click prohibition for all members of the chat room, that is, all members of the chat room will be added to the prohibition list. After setting the prohibition of all members in the chat room, only users in the chat room white list can send messages in the chat room.
     * 
     * @param  string  $roomId        Chat room ID
     * @param  int     $mute_duration Forbidden speech duration, in milliseconds.
     * @return boolean|array          Success or error
     */
    public function blockAllUserSendMsgToRoom($roomId, $mute_duration = -1)
    {

        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        $mute_duration = (int)$mute_duration > 0 ? (int)$mute_duration : -1;
        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/ban';
        $resp = Http::post($uri, compact('mute_duration'), $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 解除聊天室全员禁言
     * 
     * \details
     * 一键取消对聊天室全体成员的禁言。移除后，聊天室成员可以在聊天室中正常发送消息。
     * 
     * @param  string  $roomId  聊天室 ID
     * @return boolean|array    成功或者错误
     * 
     * \~english
     * \brief
     * Lifting the ban on all members of the chat room
     * 
     * \details
     * One click to cancel the ban on all members of the chat room. After removal, chat room members can send messages normally in the chat room.
     * 
     * @param  string  $roomId  Chat room ID
     * @return boolean|array    Success or error
     */
    public function unblockAllUserSendMsgToRoom($roomId)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/ban';
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * @ignore 设置用户全局禁言
     * @param  string $username              用户名
     * @param  int    $chatMuteDuration      单聊消息禁言时间，单位为秒，非负整数，最大值为 2147483647，`0` 表示取消该帐号的单聊消息禁言，`-1` 表示该帐号被设置永久禁言，其它值表示该帐号的具体禁言时间，负值为非法值。
     * @param  int    $groupchatMuteDuration 群组消息禁言时间，单位为秒，规则同上。
     * @param  int    $chatroomMuteDuration  聊天室消息禁言时间，单位为秒，规则同上。
     * @return boolean|array                 成功或者错误
     */
    private function blockUserSendMsgGlobal($username, $chatMuteDuration = -1, $groupchatMuteDuration = -1, $chatroomMuteDuration = -1)
    {
        if (!trim($username)) {
            \Easemob\exception('Please enter username');
        }

        $chatMuteDuration = (int)$chatMuteDuration >= 0 ? (int)$chatMuteDuration : -1;
        $groupchatMuteDuration = (int)$groupchatMuteDuration >= 0 ? (int)$groupchatMuteDuration : -1;
        $chatroomMuteDuration = (int)$chatroomMuteDuration >= 0 ? (int)$chatroomMuteDuration : -1;

        $data = array(
            'username' => $username,
            'chat' => $chatMuteDuration,
            'groupchat' => $groupchatMuteDuration,
            'chatroom' => $chatroomMuteDuration,
        );

        $uri = $this->auth->getBaseUri() . '/mutes';
        $resp = Http::post($uri, $data, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        $data = $data['data'];
        if (isset($data['result']) && $data['result'] == 'ok') {
            return true;
        }
        return false;
    }

    /**
     * @ignore 账号封禁与解禁
     * @param  string  $username 要操作用户的用户名
     * @param  int     $status   0：禁用；1：解禁
     * @return boolean|array     成功或者错误
     */
    private function activate($username, $status = 1)
    {
        if (!trim($username)) {
            \Easemob\exception('Please enter your username');
        }
        $status = (int)$status ? 1 : 0;
        $uri = $this->auth->getBaseUri() . '/users/' . $username;
        $uri .= $status ? '/activate' : '/deactivate';
        $resp = Http::post($uri, array(), $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * @ignore 添加用户至群组黑名单
     * @param  string       $groupId   群组 ID
     * @param  array|string $usernames 要添加的 IM 用户名，string: 添加单个用户；array: 批量添加
     * @return boolean|array           成功或者错误
     */
    private function addGroupBlocks($groupId, $usernames)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please pass the group ID');
        }

        if ((is_array($usernames) && empty($usernames)) || (is_string($usernames) && !trim($usernames))) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/blocks/users';
        $uri .= is_string($usernames) ? ('/' . $usernames) : '';
        $body = is_array($usernames) ? compact('usernames') : null;
        $resp = Http::post($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * @ignore 从群组黑名单移除用户
     * @param  string       $groupId   群组 ID
     * @param  array|string $usernames 要移除的 IM 用户名，string: 移除单个用户；array: 批量移除
     * @return boolean|array           成功或者错误
     */
    private function removeGroupBlocks($groupId, $usernames)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please pass the group ID');
        }

        if ((is_array($usernames) && empty($usernames)) || (is_string($usernames) && !trim($usernames))) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/blocks/users/';
        $uri .= is_array($usernames) ? implode(',', $usernames) : $usernames;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * @ignore 添加用户至聊天室黑名单
     * @param  string       $roomId   聊天室 ID
     * @param  array|string $usernames 要添加的 IM 用户名，string: 添加单个用户；array: 批量添加
     * @return boolean|array           成功或者错误
     */
    private function addRoomBlocks($roomId, $usernames)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        if ((is_array($usernames) && empty($usernames)) || (is_string($usernames) && !trim($usernames))) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/blocks/users';
        $uri .= is_string($usernames) ? ('/' . $usernames) : '';
        $body = is_array($usernames) ? compact('usernames') : null;
        $resp = Http::post($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * @ignore 从聊天室黑名单移除用户
     * @param  string       $roomId   聊天室 ID
     * @param  array|string $usernames 要移除的 IM 用户名，string: 移除单个用户；array: 批量移除
     * @return boolean|array           成功或者错误
     */
    private function removeRoomBlocks($roomId, $usernames)
    {
        if (!trim($roomId)) {
            \Easemob\exception('Please pass the chat room ID');
        }

        if ((is_array($usernames) && empty($usernames)) || (is_string($usernames) && !trim($usernames))) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatrooms/' . $roomId . '/blocks/users/';
        $uri .= is_array($usernames) ? implode(',', $usernames) : $usernames;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }
}
