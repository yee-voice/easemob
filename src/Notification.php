<?php
namespace Easemob;

use Easemob\Http\Http;

final class Notification
{

    private $auth;

    public function __construct($auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param string $labelName 要创建的标签名称
     * - 26 个小写英文字母 a-z；
     * - 26 个大写英文字母 A-Z；
     * - 10 个数字 0-9；
     * - “_”, “-”, “.”。
     * @param string $description  推送标签的描述
     * 
     */

    public function createLabel(string $labelName, string $description = '')
    {
        if (!trim($labelName) || !trim($labelName)) {
            \Easemob\exception('Please enter label name');
        }
        if (count($description) > 255) {
            \Easemob\exception('description too long');
        }
        // https://{host}/{org_name}/{app_name}/push/label
        $uri = $this->auth->getBaseUri() . '/push/label';
        $body = compact('name', 'description');
        $resp = Http::post($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return $resp->data();
    }

    /**
     * 通过名称获取标签
     * @param string $labelName
     * @return array 标签信息
     */
    public function findLabel(string $labelName)
    {
        if (!trim($labelName) || !trim($labelName)) {
            \Easemob\exception('Please enter label name');
        }
        $uri = $this->auth->getBaseUri() . '/push/label/' . $labelName;
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return $resp->data();
    }

    /**
     * 标签列表
     *
     * @param integer $limit 条数
     * @param integer $cursor 下一页的游标
     * @return array 标签列表
     */
    public function labelList(int $limit = 10, int $cursor = 0)
    {
        $uri = $this->auth->getBaseUri() . '/push/label';
        $uri .= $limit ? '?limit=' . $limit : '';
        $uri .= ($limit && $cursor) ? '&cursor=' . $cursor : '';
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return $resp->data();
    }

    /**
     * 删除标签
     *
     * @param string $labelName 标签名称
     * @return array
     */
    public function deleteLabel(string $labelName)
    {
        if (!trim($labelName) || !trim($labelName)) {
            \Easemob\exception('Please enter label name');
        }
        // https://{host}/{org_name}/{app_name}/push/label
        $uri = $this->auth->getBaseUri() . '/push/label/' . $labelName;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return $resp->data();
    }

    /**
     * 为标签添加用户
     *
     * @param string $labelName 标签名称
     * @param array $users 用户名数组
     * @return array
     */
    public function labelAddUser($labelName, $users)
    {
        if (!trim($labelName) || !trim($labelName)) {
            \Easemob\exception('Please enter label name');
        }
        if (!is_array($users) || count($users) == 0) {
            \Easemob\exception('Please enter users');
        }
        if (count($users) > 100) {
            \Easemob\exception('Please enter less than 100 users');
        }

        $uri = $this->auth->getBaseUri() . '/push/label/' . $labelName . '/user';
        $body = array('usernames' => $users);
        $resp = Http::post($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return $resp->data();
    }

    /**
     * 通过名称获取标签用户
     * @param string $labelName 标签名称
     * @param string $username 用户名
     * @return array 标签用户信息
     */
    public function findLabelUser(string $labelName, string $username)
    {
        if (!trim($labelName) || !trim($labelName)) {
            \Easemob\exception('Please enter label name');
        }
        if (!trim($labelName) || !trim($labelName)) {
            \Easemob\exception('Please enter user name');
        }
        $uri = $this->auth->getBaseUri() . '/push/label/' . $labelName . '/user/' . $username;
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return $resp->data();
    }

    /**
     * 标签列表
     * @param string $labelName 标签名称
     * @param integer $limit 条数
     * @param integer $cursor 下一页的游标
     * @return array 标签用户列表
     */
    public function labelUserList(string $labelName, int $limit = 10, int $cursor = 0)
    {
        $uri = $this->auth->getBaseUri() . '/push/label/' . $labelName . '/user';
        $uri .= $limit ? '?limit=' . $limit : '';
        $uri .= ($limit && $cursor) ? '&cursor=' . $cursor : '';
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return $resp->data();
    }

    /**
     * 批量删除标签内的用户
     *
     * @param string $labelName 标签名称
     * @param array $users 用户名数组
     * @return array
     */
    public function deleteLabelUsers(string $labelName, array $users)
    {
        if (!trim($labelName) || !trim($labelName)) {
            \Easemob\exception('Please enter label name');
        }
        if (!is_array($users) || count($users) == 0) {
            \Easemob\exception('Please enter users');
        }
        if (count($users) > 100) {
            \Easemob\exception('Please enter less than 100 users');
        }
        $uri = $this->auth->getBaseUri() . '/push/label/' . $labelName . '/user';
        $body = array('usernames' => $users);
        $resp = Http::delete($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return $resp->data();
    }

    /**
     * 使用单接口批量发送推送通知
     * https://docs-im.easemob.com/push/apppush/integration#%E4%BD%BF%E7%94%A8%E5%8D%95%E6%8E%A5%E5%8F%A3%E6%89%B9%E9%87%8F%E5%8F%91%E9%80%81%E6%8E%A8%E9%80%81%E9%80%9A%E7%9F%A5
     * @param array $targets 目标地址
     * @param array $message 推送的消息
     * @param bool $async 是否异步推送, 同步推送最多发送到1个目标. 异步最多100个
     * @param int $strategy 推送策略, 可选值: 0: 厂商通道优先, 1:只走环信通道, 2: 只走厂商通道(默认), 3: 环信优先
     * @return array
     */
    public function pushSingle(array $targets, $message, bool $async = true, int $strategy = 2)
    {
        if (!is_array($targets) || count($targets) == 0) {
            \Easemob\exception('Please enter targets');
        }
        if ($async) {
            if (count($targets) > 100) {
                \Easemob\exception('Please enter less than 100 users');
            }
        } else {
            if (count($targets) != 1) {
                \Easemob\exception('targets count must be equal to 1');
            }
        }

        $this->checkPushMessage($message);

        $uri = $this->auth->getBaseUri() . '/push/single';
        $body = array('targets' => $targets, 'pushMessage' => $message, 'async' => $async, 'strategy' => $strategy);
        $resp = Http::post($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return $resp->data();
    }

    /**
     * 创建推送通知
     * @param array $message 推送的消息
     * @return array
     */
    public function createNotification($message)
    {
        $this->checkPushMessage($message);

        $uri = $this->auth->getBaseUri() . '/push/message';
        $resp = Http::post($uri, $message, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return $resp->data();
    }

    /**
     * 查询推送通知
     * @param string $messageId 推送的消息Id
     * @return array
     */
    public function findNotificationMessage(string $messageId)
    {
        if (!trim($messageId)) {
            return \Easemob\error('messageId can not be empty');
        }

        $uri = $this->auth->getBaseUri() . '/push/message/' . $messageId;
        $resp = Http::post($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return $resp->data();
    }

    /**
     * 创建全局推送任务
     * @param string $messageId 推送的消息
     * @param int $strategy 推送策略, 可选值: 0: 厂商通道优先, 1:只走环信通道, 2: 只走厂商通道(默认), 3: 环信优先
     * @return array
     */
    function sendBroadcastNotification(string $messageId, int $strategy = 2)
    {
        if (!trim($messageId)) {
            return \Easemob\error('messageId can not be empty');
        }

        $uri = $this->auth->getBaseUri() . '/push/task/broadcast';
        $resp = Http::post($uri, ['strategy' => $strategy, 'pushMsgId' => $messageId], $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return $resp->data();
    }

    /**
     * 创建推送任务
     * @param array $message 推送的消息
     * @param int $strategy 推送策略, 可选值: 0: 厂商通道优先, 1:只走环信通道, 2: 只走厂商通道(默认), 3: 环信优先
     * @return array
     */
    function createNotificationTask($message, int $strategy = 2)
    {
        $this->checkPushMessage($message);
        $uri = $this->auth->getBaseUri() . '/push/task';
        $resp = Http::post($uri, ['strategy' => $strategy, 'pushMessage' => $message], $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return $resp->data();
    }

    /**
     * 使用标签推送接口发送推送通知
     * @param array $targets 目标标签
     * @param array $message 推送的消息
     * @param int $strategy 推送策略, 可选值: 0: 厂商通道优先, 1:只走环信通道, 2: 只走厂商通道(默认), 3: 环信优先
     * @return array
     */
    function sendLabelNotification(array $targets, array $message, int $strategy = 2)
    {
        if (count($targets) === 0) {
            return \Easemob\error('please enter target label');
        }

        $this->checkPushMessage($message);

        $uri = $this->auth->getBaseUri() . '/push/list/label';
        $body = ['targets' => $targets, 'strategy' => $strategy, 'pushMessage' => $message];
        $resp = Http::post($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return $resp->data();
    }


    private function checkPushMessage($message)
    {
        if (!$message) {
            \Easemob\exception('Please enter message');
        }

        if (!isset($message['title'])) {
            \Easemob\exception('Please enter message title');
        }
        if (!isset($message['content'])) {
            \Easemob\exception('Please enter message content');
        }
    }

}