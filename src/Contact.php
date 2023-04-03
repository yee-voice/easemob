<?php
namespace Easemob;

use Easemob\Http\Http;

/**
 * \~chinese
 * Contact 用来管理联系人（添加好友等）
 * 
 * \~english
 * The `Contact` is used to manage contacts (add friends, etc.)
 */
final class Contact
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
     * 添加好友
     * 
     * \details
     * 好友必须是和自己在一个 App Key 下的用户。免费版 App Key 下的每个用户的好友数量上限为 1000，不同版本 App Key 上限不同，具体可参考：<a href="https://www.easemob.com/pricing/im" target="_blank">版本功能介绍</a>。
     * 
     * @param  string  $username 要添加好友的用户名
     * @param  string  $contact  好友用户名
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Add friends
     * 
     * \details
     * Friends must be users under the same app key with themselves. The maximum number of friends of each user under the free version of APP key is 1000. The maximum number of friends of different versions of APP key is different. For details, please refer to:<a href="https://www.easemob.com/pricing/im" target="_blank">version function introduction</a>.
     * 
     * @param  string  $username User name
     * @param  string  $contact  Friend user name
     * @return boolean|array     Success or error
     */
    public function add($username, $contact)
    {
        if (!trim($username) || !trim($contact)) {
            \Easemob\exception('Please enter username and friend username');
        }
        $uri = $this->auth->getBaseUri() . '/users/' . $username . '/contacts/users/' . $contact;
        $resp = Http::post($uri, array(), $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 移除好友
     * 
     * \details
     * 从用户的好友列表中移除一个用户。
     * 
     * @param  string  $username 要移除好友的用户名
     * @param  string  $contact  好友用户名
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Remove friends
     * 
     * \details
     * Removes a user from the user's friends list.
     * 
     * @param  string  $username User name
     * @param  string  $contact  Friend user name
     * @return boolean|array     Success or error
     */
    public function remove($username, $contact)
    {
        if (!trim($username) || !trim($contact)) {
            \Easemob\exception('Please enter username and friend username');
        }
        $uri = $this->auth->getBaseUri() . '/users/' . $username . '/contacts/users/' . $contact;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 获取好友列表
     * 
     * @param  string $username 要获取好友列表的用户名
     * @return array            好友列表或者错误
     * 
     * \~english
     * \brief
     * Get friends list
     * 
     * @param  string $username User name
     * @return array            Friends list or error
     */
    public function get($username)
    {
        if (!trim($username)) {
            \Easemob\exception('Please enter username');
        }
        $uri = $this->auth->getBaseUri() . '/users/' . $username . '/contacts/users';
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data'];
    }
}
