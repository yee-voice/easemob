<?php
namespace Easemob;

use Easemob\Http\Http;

/**
 * \~chinese
 * UserMetadata 用来管理用户属性
 * 
 * \~english
 * The `UserMetadata` is used to manage user attribute
 */
final class UserMetadata
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
     * 设置用户属性
     * 
     * \details
     * 用户属性的内容为一个或多个纯文本键值对，默认单一用户的属性总长不得超过 2 KB，默认一个 app 下所有用户的所有属性总长不得超过 10 GB。
     * 
     * @param  string  $username 要设置属性的用户名
     * @param  array   $metadata 要设置的属性（键：属性名；值：属性值）
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Set user properties
     * 
     * \details
     * The content of user attributes is one or more plain text key value pairs. By default, the total length of attributes of a single user shall not exceed 2 kb. By default, the total length of all attributes of all users under an app shall not exceed 10 GB.
     * 
     * @param  string  $username User name
     * @param  array   $metadata Properties(key: attribute name; value: attribute value)
     * @return boolean|array     Success or error
     */
    public function setMetadataToUser($username, $metadata)
    {
        if (!trim($username)) {
            \Easemob\exception('Please enter username');
        }
        if (!is_array($metadata) || empty($metadata)) {
            \Easemob\exception('Please enter metadata');
        }
        $uri = $this->auth->getBaseUri() . '/metadata/user/' . $username;
        $resp = Http::put($uri, http_build_query($metadata), $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 获取用户属性
     * 
     * \details
     * 获取指定用户的所有用户属性键值对。如果指定的用户或用户属性不存在，返回空数据 {}。
     * 
     * @param  string $username 要获取属性的用户名
     * @return array            用户属性或者错误
     * 
     * \~english
     * \brief
     * Get user properties
     * 
     * \details
     * Gets all user attribute key value pairs for the specified user. If the specified user or user attribute does not exist, null data {} is returned.
     * 
     * @param  string $username User name
     * @return array            User properties or error
     */
    public function getMetadataFromUser($username)
    {
        if (!trim($username)) {
            \Easemob\exception('Please enter username');
        }
        $uri = $this->auth->getBaseUri() . '/metadata/user/' . $username;
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
     * 批量获取用户属性
     * 
     * \details
     * 根据指定的用户名列表和属性列表，查询用户属性。如果指定的用户或用户属性不存在，返回空数据 {}。 每次最多指定 100 个用户。
     * 
     * @param  array $targets    用户名列表，最多 100 个用户名。
     * @param  array $properties 属性名列表，查询结果只返回该列表中包含的属性，不在该列表中的属性将被忽略。
     * @return array             用户属性（数组键是用户名，数组值是用户对应的属性）或者错误
     * 
     * \~english
     * \brief
     * Get user attributes in batch
     * 
     * \details
     * Query user attributes according to the specified user name list and attribute list. If the specified user or user attribute does not exist, null data {} is returned. Specify up to 100 users at a time.
     * 
     * @param  array $targets    User name list, up to 100 user names.
     * @param  array $properties Attribute name list. The query result only returns the attributes contained in the list, and the attributes not in the list will be ignored.
     * @return array             User attribute (the array key is the user name, and the array value is the attribute corresponding to the user) or error
     */
    public function batchGetMetadataFromUser($targets, $properties)
    {
        if (!is_array($targets) || empty($targets) || !is_array($properties) || empty($properties)) {
            \Easemob\exception('Parameters error');
        }

        // 最多 100 个用户
        $limitNums = 100;
        if (count($targets) > $limitNums) {
            // 截取前 100 个用户
            $targets = array_slice($targets, 0, $limitNums);
        }
        $uri = $this->auth->getBaseUri() . '/metadata/user/get';
        $body = compact('targets', 'properties');
        $resp = Http::post($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data'];
    }

    /**
     * \~chinese
     * \brief
     * 获取用户属性总量大小
     * 
     * \details
     * 获取该 app 下所有用户的属性数据大小，单位为 byte。
     * 
     * @return float|array 用户属性总量大小（单位：byte）或者错误
     * 
     * \~english
     * \brief
     * Get the total size of user attributes
     * 
     * \details
     * Get the attribute data size of all users under the app, in bytes.
     * 
     * @return float|array Total size of user attributes (unit: byte) or error
     */
    public function getUsage()
    {
        $uri = $this->auth->getBaseUri() . '/metadata/user/capacity';
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
     * 删除用户属性
     * 
     * \details
     * 删除指定用户的所有属性。如果指定的用户或用户属性不存在（可能已删除），也视为删除成功。
     * 
     * @param  string  $username 用户名
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Delete user attributes
     * 
     * \details
     * Deletes all properties of the specified user. If the specified user or user attribute does not exist (may have been deleted), the deletion is also regarded as successful.
     * 
     * @param  string  $username User name
     * @return boolean|array     Success or error
     */
    public function deleteMetadataFromUser($username)
    {
        if (!trim($username)) {
            \Easemob\exception('Please enter username');
        }
        $uri = $this->auth->getBaseUri() . '/metadata/user/' . $username;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data'];
    }
}
