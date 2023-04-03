<?php
namespace Easemob;

use Easemob\Http\Http;

/**
 * \~chinese
 * Push 用来管理用户推送（设置推送免打扰等）
 * 
 * \~english
 * The `Push` is used to manage user push (set push free, etc.)
 */
final class Push
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
     * 设置推送昵称
     * 
     * \details
     * 设置用户的推送昵称，在离线推送时使用。
     * 
     * @param  string  $username 用户名
     * @param  string  $nickname 要设置的推送昵称
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Set push nickname
     * 
     * \details
     * Set the user's push nickname and use it when pushing offline.
     * 
     * @param  string  $username User name
     * @param  string  $nickname Nickname
     * @return boolean|array     Success or error
     */
    public function updateUserNickname($username, $nickname)
    {
        if (!trim($username) || !trim($nickname)) {
            \Easemob\exception('Please enter your username and nickname');
        }
        $uri = $this->auth->getBaseUri() . '/users/' . $username;
        $body = compact('nickname');
        $resp = Http::put($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 设置推送消息展示方式
     * 
     * \details
     * 设置推送消息至客户端的方式，修改后及时有效。服务端对应不同的设置，向用户发送不同展示方式的消息。
     * 
     * @param  string  $username                   用户名
     * @param  int     $notification_display_style 消息提醒方式，0：仅通知；1：通知以及消息详情；
     * @return boolean|array                       成功或者错误
     * 
     * \~english
     * \brief
     * Set push message display method
     * 
     * \details
     * Set the method of pushing messages to the client, which is timely and effective after modification. The server sends messages with different display methods to users according to different settings.
     * 
     * @param  string  $username                   User name
     * @param  int     $notification_display_style Message reminder method, 0: notification only; 1: Notice and message details;
     * @return boolean|array                       Success or error
     */
    public function setNotificationDisplayStyle($username, $notification_display_style = 1)
    {
        if (!trim($username)) {
            \Easemob\exception('Please enter your username');
        }
        $notification_display_style = (int)$notification_display_style ? 1 : 0;
        $uri = $this->auth->getBaseUri() . '/users/' . $username;
        $body = compact('notification_display_style');
        $resp = Http::put($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 设置推送免打扰
     * 
     * \details
     * 设置用户免打扰，在免打扰期间，用户将不会收到离线消息推送。
     * 
     * @param  string   $username  用户名
     * @param  int      $startTime 免打扰起始时间，单位是小时，例如 8 代表每日 8:00 开启免打扰
     * @param  int      $endTime   免打扰结束时间，单位是小时，例如 18 代表每日 18:00 关闭免打扰
     * @return boolean|array       成功或者错误
     * 
     * \~english
     * \brief
     * Set no disturb
     * 
     * \details
     * Set the user to be undisturbed. During the undisturbed period, the user will not receive offline message push.
     * 
     * @param  string   $username  User name
     * @param  int      $startTime The starting time of no disturbance, in hours, for example, 8 represents 8:00 every day
     * @param  int      $endTime   The end time of no disturbance, in hours, for example, 18 means that no disturbance is closed at 18:00 every day
     * @return boolean|array       Success or error
     */
    public function openNotificationNoDisturbing($username, $startTime, $endTime)
    {
        return $this->disturb($username, 1, $startTime, $endTime);
    }

    /**
     * \~chinese
     * \brief
     * 取消推送免打扰
     * 
     * @param  string   $username  用户名
     * @return boolean|array       成功或者错误
     * 
     * \~english
     * \brief
     * Cancel push without interruption
     * 
     * @param  string   $username  User name
     * @return boolean|array       Success or error
     */
    public function closeNotificationNoDisturbing($username)
    {
        return $this->disturb($username, 0);
    }

    /**
     * @ignore 设置免打扰
     * @param  string   $username                         用户名
     * @param  int      $notification_no_disturbing       是否免打扰，0：代表免打扰关闭，1：免打扰开启
     * @param  int      $notification_no_disturbing_start 免打扰起始时间，单位是小时，例如 8 代表每日 8:00 开启免打扰
     * @param  int      $notification_no_disturbing_end   免打扰结束时间，单位是小时，例如 18 代表每日 18:00 关闭免打扰
     * @return boolean|array                              成功或者错误
     */    
    private function disturb(
        $username,
        $notification_no_disturbing,
        $notification_no_disturbing_start = 0,
        $notification_no_disturbing_end = 0
    ) {
        if (!trim($username)) {
            \Easemob\exception('Please enter your username');
        }
        $notification_no_disturbing = (int)$notification_no_disturbing ? 1 : 0;
        $uri = $this->auth->getBaseUri() . '/users/' . $username;
        $body = compact('notification_no_disturbing', 'notification_no_disturbing_start', 'notification_no_disturbing_end');
        $resp = Http::put($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }
}
