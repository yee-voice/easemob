<?php

namespace Easemob;

use Easemob\Http\Http;

/**
 * \~chinese
 * Message 用来发送消息
 * 
 * \~english
 * The `Message` is used to send message
 */
final class Message
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
     * 发送文本消息
     * 
     * @param  string  $target_type 发送的目标类型；users：给用户发消息，chatgroups：给群发消息，chatrooms：给聊天室发消息
     * @param  array   $target      发送的目标；注意这里需要用数组，数组内添加的最大用户数默认 600 个，即使只有一个用户，也要用数组 [‘u1’]；给用户发送时数组元素是用户名，给群组发送时，数组元素是 groupid。
     * @param  array   $message     消息内容
     * @param  string  $from        表示消息发送者；无此字段 Server 会默认设置为 "from": "admin"，有 from 字段但值为空串 ("") 时请求失败
     * @param  string  $sync_device 消息发送成功后，是否将消息同步给发送方。true：是；false（默认）：否。
     * @param  boolean $isOnline    该参数值为 true 时，代表 routetype 的值为 “ROUTE_ONLINE”，表示发送消息时只有接收方在线时，才进行消息投递。若接收方离线，将不会收到此条消息。
     * @return array                发送给的目标和对应消息 id 的数组或者错误
     * 
     * \~english
     * \brief
     * Send text message
     * 
     * @param  string  $target_type Type of target to send; Users: send messages to users, chatgroups: send messages to groups, chatrooms: send messages to chat rooms
     * @param  array   $target      Target of transmission; Note that you need to use the array here. The maximum number of users added in the array is 600 by default. Even if there is only one user, you should also use the array ['u1 ']; When sending to the user, the array element is the user name. When sending to the group, the array element is the groupid.
     * @param  array   $message     Message content
     * @param  string  $from        Indicates the sender of the message; Without this field, the server will default to "from": "admin". When there is a from field but the value is an empty string (""), the request fails
     * @param  string  $sync_device Whether to synchronize the message to the sender after the message is sent successfully. True: Yes; False (default): No.
     * @param  boolean $isOnline    When the parameter value is true, the value representing routetype is "route_online", which means that the message is delivered only when the receiver is online. If the receiver is offline, it will not receive this message.
     * @return array                The array or error of the target and corresponding message ID sent to
     */
    public function text($target_type, $target, $message, $from = 'admin', $sync_device = false, $isOnline = false)
    {
        return $this->send('txt', $target_type, $target, $message, $from, $sync_device, $isOnline);
    }

    /**
     * \~chinese
     * \brief
     * 发送图片消息
     * 
     * @param  string  $target_type 发送的目标类型；users：给用户发消息，chatgroups：给群发消息，chatrooms：给聊天室发消息
     * @param  array   $target      发送的目标；注意这里需要用数组，数组内添加的最大用户数默认 600 个，即使只有一个用户，也要用数组 [‘u1’]；给用户发送时数组元素是用户名，给群组发送时，数组元素是 groupid。
     * @param  array   $message     消息内容
     * @param  string  $from        表示消息发送者；无此字段 Server 会默认设置为 "from": "admin"，有 from 字段但值为空串 ("") 时请求失败
     * @param  string  $sync_device 消息发送成功后，是否将消息同步给发送方。true：是；false（默认）：否。
     * @param  boolean $isOnline    该参数值为 true 时，代表 routetype 的值为 “ROUTE_ONLINE”，表示发送消息时只有接收方在线时，才进行消息投递。若接收方离线，将不会收到此条消息。
     * @return array                发送给的目标和对应消息 id 的数组或者错误
     * 
     * \~english
     * \brief
     * Send picture message
     * 
     * @param  string  $target_type Type of target to send; Users: send messages to users, chatgroups: send messages to groups, chatrooms: send messages to chat rooms
     * @param  array   $target      Target of transmission; Note that you need to use the array here. The maximum number of users added in the array is 600 by default. Even if there is only one user, you should also use the array ['u1 ']; When sending to the user, the array element is the user name. When sending to the group, the array element is the groupid.
     * @param  array   $message     Message content
     * @param  string  $from        Indicates the sender of the message; Without this field, the server will default to "from": "admin". When there is a from field but the value is an empty string (""), the request fails
     * @param  string  $sync_device Whether to synchronize the message to the sender after the message is sent successfully. True: Yes; False (default): No.
     * @param  boolean $isOnline    When the parameter value is true, the value representing routetype is "route_online", which means that the message is delivered only when the receiver is online. If the receiver is offline, it will not receive this message.
     * @return array                The array or error of the target and corresponding message ID sent to
     */
    public function image($target_type, $target, $message, $from = 'admin', $sync_device = false, $isOnline = false)
    {
        return $this->send('img', $target_type, $target, $message, $from, $sync_device, $isOnline);
    }

    /**
     * \~chinese
     * \brief
     * 发送语音消息
     * 
     * @param  string  $target_type 发送的目标类型；users：给用户发消息，chatgroups：给群发消息，chatrooms：给聊天室发消息
     * @param  array   $target      发送的目标；注意这里需要用数组，数组内添加的最大用户数默认 600 个，即使只有一个用户，也要用数组 [‘u1’]；给用户发送时数组元素是用户名，给群组发送时，数组元素是 groupid。
     * @param  array   $message     消息内容
     * @param  string  $from        表示消息发送者；无此字段 Server 会默认设置为 "from": "admin"，有 from 字段但值为空串 ("") 时请求失败
     * @param  string  $sync_device 消息发送成功后，是否将消息同步给发送方。true：是；false（默认）：否。
     * @param  boolean $isOnline    该参数值为 true 时，代表 routetype 的值为 “ROUTE_ONLINE”，表示发送消息时只有接收方在线时，才进行消息投递。若接收方离线，将不会收到此条消息。
     * @return array                发送给的目标和对应消息 id 的数组或者错误
     * 
     * \~english
     * \brief
     * Send voice message
     * 
     * @param  string  $target_type Type of target to send; Users: send messages to users, chatgroups: send messages to groups, chatrooms: send messages to chat rooms
     * @param  array   $target      Target of transmission; Note that you need to use the array here. The maximum number of users added in the array is 600 by default. Even if there is only one user, you should also use the array ['u1 ']; When sending to the user, the array element is the user name. When sending to the group, the array element is the groupid.
     * @param  array   $message     Message content
     * @param  string  $from        Indicates the sender of the message; Without this field, the server will default to "from": "admin". When there is a from field but the value is an empty string (""), the request fails
     * @param  string  $sync_device Whether to synchronize the message to the sender after the message is sent successfully. True: Yes; False (default): No.
     * @param  boolean $isOnline    When the parameter value is true, the value representing routetype is "route_online", which means that the message is delivered only when the receiver is online. If the receiver is offline, it will not receive this message.
     * @return array                The array or error of the target and corresponding message ID sent to
     */
    public function audio($target_type, $target, $message, $from = 'admin', $sync_device = false, $isOnline = false)
    {
        return $this->send('audio', $target_type, $target, $message, $from, $sync_device, $isOnline);
    }

    /**
     * \~chinese
     * \brief
     * 发送视频消息
     * 
     * @param  string  $target_type 发送的目标类型；users：给用户发消息，chatgroups：给群发消息，chatrooms：给聊天室发消息
     * @param  array   $target      发送的目标；注意这里需要用数组，数组内添加的最大用户数默认 600 个，即使只有一个用户，也要用数组 [‘u1’]；给用户发送时数组元素是用户名，给群组发送时，数组元素是 groupid。
     * @param  array   $message     消息内容
     * @param  string  $from        表示消息发送者；无此字段 Server 会默认设置为 "from": "admin"，有 from 字段但值为空串 ("") 时请求失败
     * @param  string  $sync_device 消息发送成功后，是否将消息同步给发送方。true：是；false（默认）：否。
     * @param  boolean $isOnline    该参数值为 true 时，代表 routetype 的值为 “ROUTE_ONLINE”，表示发送消息时只有接收方在线时，才进行消息投递。若接收方离线，将不会收到此条消息。
     * @return array                发送给的目标和对应消息 id 的数组或者错误
     * 
     * \~english
     * \brief
     * Send video message
     * 
     * @param  string  $target_type Type of target to send; Users: send messages to users, chatgroups: send messages to groups, chatrooms: send messages to chat rooms
     * @param  array   $target      Target of transmission; Note that you need to use the array here. The maximum number of users added in the array is 600 by default. Even if there is only one user, you should also use the array ['u1 ']; When sending to the user, the array element is the user name. When sending to the group, the array element is the groupid.
     * @param  array   $message     Message content
     * @param  string  $from        Indicates the sender of the message; Without this field, the server will default to "from": "admin". When there is a from field but the value is an empty string (""), the request fails
     * @param  string  $sync_device Whether to synchronize the message to the sender after the message is sent successfully. True: Yes; False (default): No.
     * @param  boolean $isOnline    When the parameter value is true, the value representing routetype is "route_online", which means that the message is delivered only when the receiver is online. If the receiver is offline, it will not receive this message.
     * @return array                The array or error of the target and corresponding message ID sent to
     */
    public function video($target_type, $target, $message, $from = 'admin', $sync_device = false, $isOnline = false)
    {
        return $this->send('video', $target_type, $target, $message, $from, $sync_device, $isOnline);
    }

    /**
     * \~chinese
     * \brief
     * 发送文件消息
     * 
     * @param  string  $target_type 发送的目标类型；users：给用户发消息，chatgroups：给群发消息，chatrooms：给聊天室发消息
     * @param  array   $target      发送的目标；注意这里需要用数组，数组内添加的最大用户数默认 600 个，即使只有一个用户，也要用数组 [‘u1’]；给用户发送时数组元素是用户名，给群组发送时，数组元素是 groupid。
     * @param  array   $message     消息内容
     * @param  string  $from        表示消息发送者；无此字段 Server 会默认设置为 "from": "admin"，有 from 字段但值为空串 ("") 时请求失败
     * @param  string  $sync_device 消息发送成功后，是否将消息同步给发送方。true：是；false（默认）：否。
     * @param  boolean $isOnline    该参数值为 true 时，代表 routetype 的值为 “ROUTE_ONLINE”，表示发送消息时只有接收方在线时，才进行消息投递。若接收方离线，将不会收到此条消息。
     * @return array                发送给的目标和对应消息 id 的数组或者错误
     * 
     * \~english
     * \brief
     * Send file message
     * 
     * @param  string  $target_type Type of target to send; Users: send messages to users, chatgroups: send messages to groups, chatrooms: send messages to chat rooms
     * @param  array   $target      Target of transmission; Note that you need to use the array here. The maximum number of users added in the array is 600 by default. Even if there is only one user, you should also use the array ['u1 ']; When sending to the user, the array element is the user name. When sending to the group, the array element is the groupid.
     * @param  array   $message     Message content
     * @param  string  $from        Indicates the sender of the message; Without this field, the server will default to "from": "admin". When there is a from field but the value is an empty string (""), the request fails
     * @param  string  $sync_device Whether to synchronize the message to the sender after the message is sent successfully. True: Yes; False (default): No.
     * @param  boolean $isOnline    When the parameter value is true, the value representing routetype is "route_online", which means that the message is delivered only when the receiver is online. If the receiver is offline, it will not receive this message.
     * @return array                The array or error of the target and corresponding message ID sent to
     */
    public function file($target_type, $target, $message, $from = 'admin', $sync_device = false, $isOnline = false)
    {
        return $this->send('file', $target_type, $target, $message, $from, $sync_device, $isOnline);
    }

    /**
     * \~chinese
     * \brief
     * 发送位置消息
     * 
     * @param  string  $target_type 发送的目标类型；users：给用户发消息，chatgroups：给群发消息，chatrooms：给聊天室发消息
     * @param  array   $target      发送的目标；注意这里需要用数组，数组内添加的最大用户数默认 600 个，即使只有一个用户，也要用数组 [‘u1’]；给用户发送时数组元素是用户名，给群组发送时，数组元素是 groupid。
     * @param  array   $message     消息内容
     * @param  string  $from        表示消息发送者；无此字段 Server 会默认设置为 "from": "admin"，有 from 字段但值为空串 ("") 时请求失败
     * @param  string  $sync_device 消息发送成功后，是否将消息同步给发送方。true：是；false（默认）：否。
     * @param  boolean $isOnline    该参数值为 true 时，代表 routetype 的值为 “ROUTE_ONLINE”，表示发送消息时只有接收方在线时，才进行消息投递。若接收方离线，将不会收到此条消息。
     * @return array                发送给的目标和对应消息 id 的数组或者错误
     * 
     * \~english
     * \brief
     * Send location message
     * 
     * @param  string  $target_type Type of target to send; Users: send messages to users, chatgroups: send messages to groups, chatrooms: send messages to chat rooms
     * @param  array   $target      Target of transmission; Note that you need to use the array here. The maximum number of users added in the array is 600 by default. Even if there is only one user, you should also use the array ['u1 ']; When sending to the user, the array element is the user name. When sending to the group, the array element is the groupid.
     * @param  array   $message     Message content
     * @param  string  $from        Indicates the sender of the message; Without this field, the server will default to "from": "admin". When there is a from field but the value is an empty string (""), the request fails
     * @param  string  $sync_device Whether to synchronize the message to the sender after the message is sent successfully. True: Yes; False (default): No.
     * @param  boolean $isOnline    When the parameter value is true, the value representing routetype is "route_online", which means that the message is delivered only when the receiver is online. If the receiver is offline, it will not receive this message.
     * @return array                The array or error of the target and corresponding message ID sent to
     */
    public function location($target_type, $target, $message, $from = 'admin', $sync_device = false, $isOnline = false)
    {
        return $this->send('loc', $target_type, $target, $message, $from, $sync_device, $isOnline);
    }

    /**
     * \~chinese
     * \brief
     * 发送透传消息
     * 
     * @param  string  $target_type 发送的目标类型；users：给用户发消息，chatgroups：给群发消息，chatrooms：给聊天室发消息
     * @param  array   $target      发送的目标；注意这里需要用数组，数组内添加的最大用户数默认 600 个，即使只有一个用户，也要用数组 [‘u1’]；给用户发送时数组元素是用户名，给群组发送时，数组元素是 groupid。
     * @param  array   $message     消息内容
     * @param  string  $from        表示消息发送者；无此字段 Server 会默认设置为 "from": "admin"，有 from 字段但值为空串 ("") 时请求失败
     * @param  string  $sync_device 消息发送成功后，是否将消息同步给发送方。true：是；false（默认）：否。
     * @param  boolean $isOnline    该参数值为 true 时，代表 routetype 的值为 “ROUTE_ONLINE”，表示发送消息时只有接收方在线时，才进行消息投递。若接收方离线，将不会收到此条消息。
     * @return array                发送给的目标和对应消息 id 的数组或者错误
     * 
     * \~english
     * \brief
     * Send transparent message
     * 
     * @param  string  $target_type Type of target to send; Users: send messages to users, chatgroups: send messages to groups, chatrooms: send messages to chat rooms
     * @param  array   $target      Target of transmission; Note that you need to use the array here. The maximum number of users added in the array is 600 by default. Even if there is only one user, you should also use the array ['u1 ']; When sending to the user, the array element is the user name. When sending to the group, the array element is the groupid.
     * @param  array   $message     Message content
     * @param  string  $from        Indicates the sender of the message; Without this field, the server will default to "from": "admin". When there is a from field but the value is an empty string (""), the request fails
     * @param  string  $sync_device Whether to synchronize the message to the sender after the message is sent successfully. True: Yes; False (default): No.
     * @param  boolean $isOnline    When the parameter value is true, the value representing routetype is "route_online", which means that the message is delivered only when the receiver is online. If the receiver is offline, it will not receive this message.
     * @return array                The array or error of the target and corresponding message ID sent to
     */
    public function cmd($target_type, $target, $message, $from = 'admin', $sync_device = false, $isOnline = false)
    {
        return $this->send('cmd', $target_type, $target, $message, $from, $sync_device, $isOnline);
    }

    /**
     * \~chinese
     * \brief
     * 发送自定义消息
     * 
     * @param  string  $target_type 发送的目标类型；users：给用户发消息，chatgroups：给群发消息，chatrooms：给聊天室发消息
     * @param  array   $target      发送的目标；注意这里需要用数组，数组内添加的最大用户数默认 600 个，即使只有一个用户，也要用数组 [‘u1’]；给用户发送时数组元素是用户名，给群组发送时，数组元素是 groupid。
     * @param  array   $message     消息内容
     * @param  string  $from        表示消息发送者；无此字段 Server 会默认设置为 "from": "admin"，有 from 字段但值为空串 ("") 时请求失败
     * @param  string  $sync_device 消息发送成功后，是否将消息同步给发送方。true：是；false（默认）：否。
     * @param  boolean $isOnline    该参数值为 true 时，代表 routetype 的值为 “ROUTE_ONLINE”，表示发送消息时只有接收方在线时，才进行消息投递。若接收方离线，将不会收到此条消息。
     * @return array                发送给的目标和对应消息 id 的数组或者错误
     * 
     * \~english
     * \brief
     * Send custom message
     * 
     * @param  string  $target_type Type of target to send; Users: send messages to users, chatgroups: send messages to groups, chatrooms: send messages to chat rooms
     * @param  array   $target      Target of transmission; Note that you need to use the array here. The maximum number of users added in the array is 600 by default. Even if there is only one user, you should also use the array ['u1 ']; When sending to the user, the array element is the user name. When sending to the group, the array element is the groupid.
     * @param  array   $message     Message content
     * @param  string  $from        Indicates the sender of the message; Without this field, the server will default to "from": "admin". When there is a from field but the value is an empty string (""), the request fails
     * @param  string  $sync_device Whether to synchronize the message to the sender after the message is sent successfully. True: Yes; False (default): No.
     * @param  boolean $isOnline    When the parameter value is true, the value representing routetype is "route_online", which means that the message is delivered only when the receiver is online. If the receiver is offline, it will not receive this message.
     * @return array                The array or error of the target and corresponding message ID sent to
     */
    public function custom($target_type, $target, $message, $from = 'admin', $sync_device = false, $isOnline = false)
    {
        return $this->send('custom', $target_type, $target, $message, $from, $sync_device, $isOnline);
    }

    /**
     * \~chinese
     * \brief
     * 获取用户离线消息数
     * 
     * @param  string $username 用户名
     * @return int|array        用户离线消息数量或者错误
     * 
     * \~english
     * \brief
     * Get the number of user offline messages
     * 
     * @param  string $username User name
     * @return int|array        Number of offline messages or errors
     */
    public function countMissedMessages($username)
    {
        if (!trim($username)) {
            \Easemob\exception('Please enter username');
        }
        $uri = $this->auth->getBaseUri() . '/users/' . $username . '/offline_msg_count';
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data'][$username];
    }

    /**
     * \~chinese
     * \brief
     * 获取某条离线消息状态
     * 
     * @param  string $username 用户名
     * @param  string $msgId    消息 ID 编号
     * @return string|array     离线消息状态（delivered：表示状态为消息已投递；undelivered：表示消息未投递；msg_not_found：消息不存在）或者错误
     * 
     * \~english
     * \brief
     * Get the status of an offline message
     * 
     * @param  string $username User name
     * @param  string $msgId    Message ID number
     * @return string|array     Offline message status (delivered: indicates that the message has been delivered; undelivered: indicates that the message has not been delivered; msg_not_found: the message does not exist) or error
     */
    public function isMessageDeliveredToUser($username, $msgId)
    {
        if (!trim($username)) {
            \Easemob\exception('Please enter username');
        }

        if (!trim($msgId)) {
            \Easemob\exception('Please enter message ID');
        }
        $uri = $this->auth->getBaseUri() . '/users/' . $username . '/offline_msg_status/' . $msgId;
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data'][$msgId];
    }

    /**
     * \~chinese
     * \brief
     * 获取历史消息文件下载地址
     * 
     * \details
     * 导出聊天记录接口不是实时接口，获取成功存在一定的延时，不能够作为实时拉取消息的接口使用。以下 API 均需要企业管理员权限才能访问。此接口一次只能获取一个小时的历史消息。
     * 
     * @param  int    $datetime 时间，每次只能获取一小时的消息，格式为 yyyyMMddHH 如 2018112717。
     * @return string|array     聊天记录文件下载地址或者错误
     * 
     * \~english
     * \brief
     * Get the download address of historical message file
     * 
     * \details
     * The export chat record interface is not a real-time interface. There is a certain delay in obtaining success, so it can not be used as an interface for real-time pulling messages. The following APIs require enterprise administrator privileges to access. This interface can only get historical messages for one hour at a time.
     * 
     * @param  int    $datetime Time, only one hour of messages can be obtained at a time. The format is yyyymmddhh, such as 2018112717.
     * @return string|array     Chat record file download address or error
     */
    public function getHistoryAsUri($dateTime)
    {
        $dateTime = (int) $dateTime;
        if (!$dateTime) {
            \Easemob\exception('Please enter the time period to get');
        }

        $uri = $this->auth->getBaseUri() . '/chatmessages/' . $dateTime;
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return isset($data['data'][0]['url']) ? $data['data'][0]['url'] : $data;
    }

    /**
     * \~chinese
     * \brief
     * 下载消息历史文件到本地
     * 
     * @param  int    $datetime 时间，每次只能获取一小时的消息，格式为 yyyyMMddHH 如 2018112717。
     * @param  string $filename 下载后的文件名，消息历史文件是 gz 压缩的。
     * @return boolean|array    下载成功或错误
     * 
     * \~english
     * \brief
     * Download message history file to local
     * 
     * @param  int    $datetime Time, only one hour of messages can be obtained at a time. The format is yyyymmddhh, such as 2018112717.
     * @param  string $filename The downloaded file name and message history file are GZ compressed.
     * @return boolean|array    Download success or error
     */
    public function getHistoryAsLocalFile($dateTime, $filename)
    {
        $fileurl = $this->getHistoryAsUri($dateTime);
        return copy($fileurl, $filename);
        // if (is_string($fileurl)) {
        //     header("Content-Description: File Transfer");
        //     header("Content-Type: application/octet-stream");
        //     header("Content-Disposition: attachment;filename=".$filename);
        //     header("Content-Transfer-Encoding: binary");
        //     header("Expires: 0");
        //     header("Cache-Control: must-revalidate");
        //     header("Pragma: public");
        //     header("Content-Length: ". filesize($fileurl));
        //     ob_clean();
        //     flush();
        //     readfile($fileurl);
        //     exit();
        // }
    }

    /**
     * \~chinese
     * \brief
     * 服务端消息撤回
     * 
     * \details
     * 应用管理员可调用接口撤回发送的消息，默认时限为 2 分钟，如需调整请联系环信商务经理。
     * 
     * @param  array $msg 要撤回的消息，一维数组代表撤回一条消息，二维数组代表撤回多条消息
     *     - `msg_id` String 类型，撤回消息的消息 ID。
     *     - `to` 可选，String 类型，撤回消息的接收方。如果不提供则消息体找不到就撤回不了。单聊为接收方用户名称，群组为群 ID，聊天室为聊天室 ID。
     *     - `chat_type` String 类型，撤回消息的三种消息类型：单聊：chat；群聊：group_chat；聊天室：chatroom。
     *     - `from` 可选，String 类型，消息撤回方，不传默认使用的是 admin，默认消息撤回方为原消息发送者。你可以通过用户 ID 指定消息撤回方。
     *     - `force` boolean 类型，是否为强制撤回：
     *         - true：是，即超过服务器保存消息时间消息也可以被撤回，具体见服务器消息保存时长；
     *         - false：否，若设置的消息撤回时限超过服务端的消息保存时间，请求消息撤回时消息可能由于过期已在服务端删除，消息撤回请求会失败，即无法从收到该消息的客户端撤回该消息。
     * @return array 撤回的消息或者错误
     * 
     * \~english
     * \brief
     * Server message withdrawal
     * 
     * \details
     * The application administrator can call the interface to withdraw the sent message. The default time limit is 2 minutes. If you need to adjust, please contact the business manager of Huanxin.
     * 
     * @param  array $msgs For the message to be withdrawn, one-dimensional array represents withdrawing one message, and two-dimensional array represents withdrawing multiple messages
     *     - `msg_id` String type, the message ID of the withdrawal message.
     *     - `to` Optional, String type, The recipient of the recall message. If it is not provided, the message body cannot be found and cannot be withdrawn. The single chat is the user name of the receiver, the group is the group ID, and the chat room is the chat room ID.
     *     - `chat_type` String type，Three message types of recall messages: single chat: chat; Group chat: Group_ chat； Chat room: chatroom.
     *     - `from` Optional, String type，The message withdrawing party is not transmitted. By default, admin is used. By default, the message withdrawing party is the original message sender. You can specify the message withdrawing party through the user ID.
     *     - `force` boolean type, Forced withdrawal:
     *         - true: Yes, that is, the message can also be withdrawn after the server saves the message. See the server message saving time for details;
     *         - false: No, if the set message withdrawal time limit exceeds the message saving time of the server, the message may have been deleted at the server due to expiration when requesting message withdrawal, and the message withdrawal request will fail, that is, the message cannot be withdrawn from the client receiving the message.
     * @return array Withdrawn message or error
     */
    public function withdraw($msgs)
    {
        // 一维数组标识
        $OneFlag = false;
        if (count($msgs) == count($msgs, 1)) {
            // 一维数组
            $OneFlag = true;
            $this->authMsg($msgs);
        } else {
            // 多维数组
            foreach ($msgs as $msg) {
                $this->authMsg($msg);
            }
        }

        $body = compact('msgs');
        $uri = $this->auth->getBaseUri() . '/messages/recall';
        $resp = Http::post($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return isset($data['data']['msgs']) ? $data['data']['msgs'] : $data['data'];
    }

    /**
     * \~chinese
     * \brief
     * 服务端单向删除会话
     * 
     * @param  string $username    用户名
     * @param  string $channel     要删除的会话 ID。
     * @param  string $type        会话类型。chat：单聊会话；groupchat：群聊会话。
     * @param  string $delete_roam 是否删除服务端消息，不允许为空。true：是；false：否。
     * @return boolean             成功或者错误
     * 
     * \~english
     * \brief
     * Server side one-way deletion session
     * 
     * @param  string $username    User name
     * @param  string $channel     Session ID to delete.
     * @param  string $type        Session type. Chat: single chat session; Group chat: group chat conversation.
     * @param  string $delete_roam Whether to delete the server message. It cannot be empty. True: Yes; False: No.
     * @return boolean             Success or error
     */
    public function deleteSession($username, $channel, $type, $delete_roam = true)
    {
        if (!trim($username)) {
            return \Easemob\exception('Please enter username');
        }
        $uri = $this->auth->getBaseUri() . '/users/' . $username . '/user_channel';
        $delete_roam = (bool) $delete_roam;
        $body = compact('channel', 'type', 'delete_roam');
        $resp = Http::delete($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return isset($data['data']['result']) && $data['data']['result'] === 'ok' ? true : false;
    }

    /**
     * @ignore 验证消息参数
     * @param array $msg 消息参数
     */
    private function authMsg($msg)
    {
        if (!is_array($msg) || empty($msg)) {
            return \Easemob\exception('Please enter a message to recall');
        }

        if (!isset($msg['msg_id']) || !trim($msg['msg_id']) || !isset($msg['chat_type']) || !trim($msg['chat_type']) || !isset($msg['force'])) {
            return \Easemob\exception('Please enter msg_id, chat_type, force');
        }
    }

    /**
     * @ignore 发送消息
     * @param  string  $type        消息类型；txt:文本消息，img：图片消息，loc：位置消息，audio：语音消息，video：视频消息，file：文件消息，cmd：透传消息，custom：自定义消息
     * @param  string  $target_type 发送的目标类型；users：给用户发消息，chatgroups：给群发消息，chatrooms：给聊天室发消息
     * @param  array   $target      发送的目标；注意这里需要用数组，数组内添加的最大用户数默认 600 个，即使只有一个用户，也要用数组 ['u1']；给用户发送时数组元素是用户名，给群组发送时，数组元素是 groupid。
     * @param  mixed   $message     消息内容
     * @param  string  $from        表示消息发送者；无此字段 Server 会默认设置为 "from": "admin"，有 from 字段但值为空串 ("") 时请求失败
     * @param  string  $sync_device 消息发送成功后，是否将消息同步给发送方。true：是；false（默认）：否。
     * @param  boolean $isOnline    该参数值为 true 时，代表 routetype 的值为 “ROUTE_ONLINE”，表示发送消息时只有接收方在线时，才进行消息投递。若接收方离线，将不会收到此条消息。
     * @return array                发送给的目标和对应消息 id 的数组或者错误
     */
    private function send($type, $target_type, $target, $message, $from = 'admin', $sync_device = false, $isOnline = false)
    {
        if (!trim($type)) {
            \Easemob\exception('Please enter type');
        }

        if (!trim($target_type)) {
            \Easemob\exception('Please enter target_type');
        }

        if (!is_array($target) || empty($target)) {
            \Easemob\exception('Please enter target');
        }

        if (!is_array($message)) {
            \Easemob\exception('Please enter message');
        }

        if (!trim($from)) {
            \Easemob\exception('If the message sender is delivered, it cannot be empty');
        }

        if (isset($message['ext'])) {
            if (!$message['ext']) {
                \Easemob\exception('If there is no extended attribute, please remove the EXT field');
            } elseif (!is_array($message['ext'])) {
                \Easemob\exception('The extended attribute, if any, must be an array');
            }
            $ext = $message['ext'];
            unset($message['ext']);
        }

        if ($type == 'txt') {
            $msg = array('msg' => $message['msg']);
        } else {
            $msg = $message;
        }
        switch ($type) {
            case 'txt':
                // 文本消息
                // $msg = array(
                //     'msg' => $message['msg'],
                // );
                break;
            case 'img':
            case 'audio':
                // 图片消息 | 语音消息
                $msg['url'] = $this->auth->getBaseUri() . '/chatfiles/' . $msg['uuid'];
                unset($msg['uuid']);
                break;
            case 'video':
                // 视频消息
                $msg['url'] = $this->auth->getBaseUri() . '/chatfiles/' . $msg['uuid'];
                $msg['thumb'] = $this->auth->getBaseUri() . '/chatfiles/' . $msg['thumb_uuid'];
                unset($msg['uuid'], $msg['thumb_uuid']);
                break;
            case 'loc':
            case 'cmd':
                // 位置消息 | 透传消息
                break;
            case 'custom':
                // 自定义消息
                if (!isset($message['customEvent']) || !preg_match('/^[a-zA-Z0-9-_\/\.]{1,32}$/', $message['customEvent'])) {
                    \Easemob\exception('User defined event type format error');
                }

                if (isset($message['customExts']) && !is_array($message['customExts'])) {
                    \Easemob\exception('User defined event attribute format error');
                } elseif (isset($message['customExts'])) {
                    if (count($message['customExts']) > 16) {
                        \Easemob\exception('User defined event attributes can contain at most 16 elements');
                    } else {
                        foreach ($message['customExts'] as $key => $val) {
                            if (!is_string($key) || !is_string($val)) {
                                \Easemob\exception('User defined event attribute element key values can only be strings');
                            }
                        }
                    }
                }
                break;
        }

        $msg['type'] = $type;
        $uri = $this->auth->getBaseUri() . '/messages?useMsgId=true';
        $body = compact('target_type', 'target', 'msg', 'from');
        if (isset($ext)) {
            $body['ext'] = $ext;
        }

        if ((bool) $sync_device) {
            $body['sync_device'] = true;
        }

        if ((bool) $isOnline) {
            $body['routetype'] = 'ROUTE_ONLINE';
        }
        $resp = Http::post($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data'];
    }
}