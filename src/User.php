<?php
namespace Easemob;

use Easemob\Http\Http;

/**
 * \~chinese
 * User 用来实现用户体系建立和管理
 * 
 * \~english
 * The `User` is used to realize the establishment and management of user system
 */
final class User
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
     * 注册单个用户 | 批量注册用户
     * 
     * @param array $users 要注册的用户信息，注册单个用户时传入一维数组，批量注册用户时传入二维数组。
     *     - `username` String 类型，用户名，长度不可超过 64 个字节长度。支持以下字符集：
     *         - 26 个小写英文字母 a-z；
     *         - 26 个大写英文字母 A-Z；
     *         - 10 个数字 0-9；
     *         - “_”, “-”, “.”。
     *         <pre><b style="color: red">注意：不区分大小写。同一个 app 下，用户名唯一。</b></pre>
     *     - `password` String 类型，登录密码，长度不可超过 64 个字符长度。
     *     - `nickname` String 类型，昵称（可选），仅用在客户端推送通知栏显示的昵称，并不是用户个人信息的昵称，开发者可自定义该内容。长度不可超过 100 个字符。支持以下字符集：
     *         - 26 个小写英文字母 a-z；
     *         - 26 个大写英文字母 A-Z；
     *         - 10 个数字 0-9；
     *         - 中文；
     *         - 特殊字符。
     * @return array 注册的用户信息或者错误
     * 
     * \~english
     * \brief
     * Register individual users | Batch registered users
     * 
     * @param array $users For the user information to be registered, a one-dimensional array is passed in when registering a single user, and a two-dimensional array is passed in when registering users in batch.
     *     - `username` String type, user name, length cannot exceed 64 bytes. The following character sets are supported:
     *         - 26 lowercase English letters a-z;
     *         - 26 uppercase English letters A-Z;
     *         - 10 digits 0-9;
     *         - "_", "-" ".".
     *         <pre><b style="color: red">Note: case insensitive. Under the same app, the user name is unique.</b></pre>
     *     - `password` String type, login password, the length cannot exceed 64 characters.
     *     - `nickname` String type, nickname (optional). It is only used for the nickname displayed in the client push notification bar, not the nickname of the user's personal information. The developer can customize this content. The length cannot exceed 100 characters. The following character sets are supported:
     *         - 26 lowercase English letters a-z;
     *         - 26 uppercase English letters A-Z;
     *         - 10 digits 0-9;
     *         - Chinese;
     *         - Special characters;
     * @return array Registered user information or error
     */
    public function create($users)
    {
        // 一维数组标识
        $usersOneFlag = false;
        if (count($users) == count($users, 1)) {
            // 一维数组
            $usersOneFlag = true;
            $this->authUser($users);
        } else {
            // 多维数组
            foreach ($users as $user) {
                $this->authUser($user);
            }
        }
        
        $uri = $this->auth->getBaseUri() . '/users';
        $resp = Http::post($uri, $users, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        
        $data = $resp->data();
        return $usersOneFlag ? $data['entities'][0] : $data['entities'];
    }

    /**
     * \~chinese
     * \brief
     * 获取单个用户的详细信息
     * 
     * @param  string $username 用户名
     * @return array            用户信息或者错误
     * 
     * \~english
     * \brief
     * Get the details of a single user
     * 
     * @param  string $username User name
     * @return array            User information or error
     */
    public function get($username)
    {
        if (!trim($username)) {
            \Easemob\exception('Please enter your username');
        }
        $uri = $this->auth->getBaseUri() . '/users/' . $username;

        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        
        $data = $resp->data();
        return $data['entities'][0];
    }

    /**
     * \~chinese
     * \brief
     * 批量获取用户
     * 
     * @param  int     $limit     获取用户的数量。默认值 10，最大值 100。超过 100 按照 100 返回。
     * @param  string  $cursor    游标，用于分页显示用户列表。第一次发起批量查询用户请求时无需设置 cursor，请求成功后会获得第一页用户列表。从响应 body 中获取 cursor，并在下一次请求 中传入该 cursor，直到响应 body 中不再有 cursor 字段，则表示已查询到 app 中所有用户。
     * @param  boolean $activated 用户是否激活。true：已激活；false：封禁，封禁需要通过解禁接口进行解禁，才能正常登录。
     * @return array              分页用户信息或者错误
     * 
     * \~english
     * \brief Get users in batch
     * 
     * @param  int     $limit     Gets the number of users. The default value is 10 and the maximum value is 100. If it exceeds 100, it will be returned as 100.
     * @param  string  $cursor    Cursor, used to display the list of users in pages. When initiating a batch query user request for the first time, there is no need to set cursor. After the request is successful, the user list on the first page will be obtained. If there is no response from cursor to the next user in the URL field of cursor, it indicates that there is no response from cursor to the next user in the URL field of cursor.
     * @param  boolean $activated Whether the user is activated. True: activated; False: blocking. The blocking needs to be lifted through the unblocking interface to log in normally.
     * @return array              Paging user information or error
     */
    public function listUsers($limit = 10, $cursor = '', $activated = true)
    {
        $limit = (int)$limit <= 0 ? 10 : (int)$limit;
        $limit = $limit > 100 ? 100 : $limit;
        $activated = (boolean)$activated;

        $uri = $this->auth->getBaseUri() . '/users';
        $uri .= '?limit='.$limit;
        $uri .= $cursor ? '&cursor='.$cursor : '';
        $uri .= '&activated='.($activated ? 1 : 0);

        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        
        $data = $resp->data();
        return array(
            'data' => $data['entities'],
            'cursor' => isset($data['cursor']) && $data['cursor'] ? $data['cursor'] : '', 
        );
    }

    /**
     * \~chinese
     * \brief
     * 删除单个用户
     * 
     * \details
     * 删除一个用户，如果此用户是群组或者聊天室的群主，系统会同时删除这些群组和聊天室。请在操作时进行确认。
     * 
     * @param  string  $username 用户名
     * @return boolean|array     成功或失败或者错误
     * 
     * \~english
     * \brief
     * Delete single user
     * 
     * \details
     * Delete a user. If the user is the group owner of a group or chat room, the system will delete these groups and chat rooms at the same time. Please confirm during operation.
     * 
     * @param  string  $username User name
     * @return boolean|array     Success or failure or error
     */
    public function delete($username)
    {
        if (!trim($username)) {
            \Easemob\exception('Please enter your username');
        }
        $uri = $this->auth->getBaseUri() . '/users/' . $username;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return isset($data['entities'][0]) && $data['entities'][0]['username'] === $username ? true : false;
    }

    /**
     * \~chinese
     * \brief
     * 批量删除用户
     * 
     * \details
     * 删除某个 APP 下指定数量的用户账号。
     * 
     * @param  int   $limit 要删除的用户数量，建议这个数值在 100-500 之间，不要过大。需要注意的是，这里只是批量的一次性删除掉 N 个用户，具体删除哪些并没有指定，可以在返回值中查看到哪些用户被删除掉了。如果 $limit 的值小于等于 0，值会按 1 处理
     * @return array        被删除的用户信息或者错误
     * 
     * \~english 
     * \brief
     * Batch delete user
     * 
     * \details
     * Delete a specified number of user accounts under an app.
     * 
     * @param  int   $limit The number of users to be deleted is recommended to be between 100-500, not too large. It should be noted that only n users are deleted in batches at one time. The specific deleted users are not specified. You can see which users have been deleted in the return value. If the value of $limit is less than or equal to 0, the value will be treated as 1
     * @return array        Deleted user information or error
     */
    public function batchDelete($limit = 0)
    {
        $limit = (int)$limit <= 0 ? 1 : (int)$limit;
        $uri = $this->auth->getBaseUri() . '/users?limit='.$limit;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['entities'];
    }

    /// @cond
    public function deleteAll()
    {
        return $this->batchDelete(0);
    }
    /// @endcond

    /**
     * \~chinese
     * \brief
     * 修改用户密码
     * 
     * \details
     * 可以修改用户的登录密码，不需要提供原密码。
     * 
     * @param  string  $username    用户名
     * @param  string  $newpassword 新密码
     * @return boolean|array        成功或者错误
     * 
     * \~english
     * \brief
     * Modify user password
     * 
     * \details
     * You can change the user's login password without providing the original password.
     * 
     * @param  string  $username    User name
     * @param  string  $newpassword New password
     * @return boolean|array        Success or error
     */
    public function updateUserPassword($username, $newpassword)
    {
        if (!trim($username) || !trim($newpassword)) {
            \Easemob\exception('Please enter your username and password');
        }
        $uri = $this->auth->getBaseUri() . '/users/' . $username . '/password';
        $body = compact('newpassword');
        $resp = Http::put($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 获取用户在线状态
     * 
     * @param  string  $username 要获取在线状态的用户名
     * @return boolean|array     是否在线（true：在线，false：离线）或者错误
     * 
     * \~english
     * \brief
     * Get user online status
     * 
     * @param  string  $username User name
     * @return boolean|array     Online status (true: online, false: offline) or error
     */
    public function isUserOnline($username)
    {
        if (!is_string($username) || !trim($username)) {
            \Easemob\exception('Please enter your username');
        }
        $result = $this->status($username);
        return isset($result[$username]) && $result[$username] === 'online' ? true : false;
    }

    /**
     * \~chinese
     * \brief
     * 批量获取用户在线状态
     * 
     * \details
     * 批量查看用户的在线状态，最大同时查看100个用户。
     * 
     * @param  array $usernames 要获取在线状态的用户名数组，最多不能超过100个
     * @return array            用户在线状态数组（数组键为用户名，数组值为用户对应的在线状态，true：在线，false：离线）或者错误
     * 
     * \~english
     * \brief
     * Get online status of users in batch
     * 
     * \details
     * View the online status of users in batches, with a maximum of 100 users at the same time.
     * 
     * @param  array $usernames User name array, no more than 100
     * @return array            User online status array (array key is user name, array value is user's corresponding online status, true: online, false: offline) or error
     */
    public function isUsersOnline($usernames)
    {
        if (!is_array($usernames) || empty($usernames)) {
            \Easemob\exception('Please enter user name array');
        }
        $result = $this->status($usernames);
        $data = array();
        foreach ($result as $user => $status) {
            $data[$user] = $status === 'online' ? true : false;
        }
        return $data;
    }

    /**
     * \~chinese
     * \brief
     * 强制下线
     * 
     * \details
     * 强制用户即把用户状态改为离线，用户需要重新登录才能正常使用。
     * 
     * @param  string  $username 要强制下线用户的用户名
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Force user offline
     * 
     * \details
     * Force the user to change the user status to offline, and the user needs to log in again to use it normally.
     * 
     * @param  string  $username User name
     * @return boolean|array     Success or error
     */
    public function forceLogoutAllDevices($username)
    {
        if (!trim($username)) {
            \Easemob\exception('Please enter your username');
        }
        $uri = $this->auth->getBaseUri() . '/users/' . $username . '/disconnect';
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data']['result'];
    }

    /// @cond
    public function forceLogoutOneDevice($username, $resource)
    {

    }
    /// @endcond

    /**
     * @ignore 获取用户在线状态 | 批量获取用户在线状态
     * @param  string|array $username 要获取在线状态的用户名，string：获取用户在线状态；array：批量获取用户在线状态
     * @return boolean|array 是否在线（true：在线，false：离线）或者用户在线状态数组（数组键为用户名，数组值为用户对应的在线状态，true：在线，false：离线）或者错误
     */
    private function status($username)
    {
        if (is_array($username)) {
            // 批量获取用户在线状态
            $uri = $this->auth->getBaseUri() . '/users/batch/status';
            $body = array('usernames' => $username);
            $resp = Http::post($uri, $body, $this->auth->headers());
        } else {
            // 获取用户在线状态
            $uri = $this->auth->getBaseUri() . '/users/' . $username . '/status';
            $resp = Http::get($uri, $this->auth->headers());
        }
        
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        if (is_array($username)) {
            $result = array();
            foreach ($data['data'] as $val) {
                foreach ($val as $key => $item) {
                    $result[$key] = $item;
                }
            }
            return $result;
        }
        return $data['data'];
    }

    /**
     * @ignore 验证用户信息
     * @param array $user 用户信息
     */
    private function authUser($user)
    {
        if (!isset($user['username']) || !trim($user['username']) || !isset($user['password']) || !trim($user['password'])) {
            return \Easemob\exception('Please enter your username and password');
        }
    }

    /**
     * @ignore 生成环信用户token
     * 
     */
    public function token($user) {

        $this->checkTokenParam($user);

        $uri = $this->auth->getBaseUri() . '/token';
        $resp = Http::post($uri, $user, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        
        return $resp->data();
        // return $usersOneFlag ? $data['entities'][0] : $data['entities'];
    }

    private function checkTokenParam($param) {
        $hasUsername = isset($param['username']) && trim($param['username']);
        $hasGrantType = isset($param['grant_type']) && ($param['grant_type'] === 'password' || $param['grant_type'] === 'inherit');
        $checkPassword = $hasGrantType && $param['grant_type'] === 'password' && isset($param['password']) && trim($param['password']);
        $checkAutoCreateUser = $hasGrantType && $param['grant_type'] === 'inherit' && isset($param['autoCreateUser']);
        if(!($hasUsername && ($checkPassword || $checkAutoCreateUser))) {
            return \Easemob\exception('param error');
        }
    }
}