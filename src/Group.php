<?php
namespace Easemob;

use Easemob\Http\Http;

/**
 * \~chinese
 * Group 用来管理群组
 * 
 * \~english
 * The `Group` is used to manage group
 */
final class Group
{
    /**
     * @ignore
     * @var Auth $auth 授权对象
     */
    private $auth;

    /**
     * @ignore
     * @var array $modifiedAllowedField 修改群组信息时，请求体中允许的属性，5.6 版本前不能声明类常量数组，改为静态变量代替
     */
    private static $modifiedAllowedField = array(
        'groupname',
        'description',
        'maxusers',
        'membersonly',
        'allowinvites',
        'custom',
    );

    /// @cond
    public function __construct($auth)
    {
        $this->auth = $auth;
    }
    /// @endcond

    /**
     * \~chinese
     * \brief
     * 获取 App 中所有的群组（可分页）
     * 
     * @param  int    $limit  一次获取的群组数量，默认获取 10 条。
     * @param  string $cursor 分页使用，传入游标后便从游标起始的地方进行查询，类似于数据库 limit 1,5 中 1 的作用，可以理解为页码。
     * @return array          群组列表信息或者错误
     * 
     * \~english
     * \brief
     * Get all groups in the app (pagable)
     * 
     * @param  int    $limit  Number of groups obtained at one time, get 10 by default.
     * @param  string $cursor Paging is used. After the cursor is passed in, it will be queried from the beginning of the cursor, which is similar to the function of 1 in database limit 1,5. It can be understood as page number.
     * @return array          Group list information or error
     */
    public function listGroups($limit = 10, $cursor = '')
    {
        $limit = (int) $limit < 0 ? 1 : (int) $limit;
        $uri = $this->auth->getBaseUri() . '/chatgroups';
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
     * 获取 App 中所有的群组
     * 
     * @return array 群组列表信息或者错误
     * 
     * \~english
     * \brief
     * Get all groups in the app
     * 
     * @return array Group list information or error
     */
    public function listAllGroups()
    {
        $data = $this->listGroups(0);
        return $data['data'];
    }

    /**
     * \~chinese
     * \brief
     * 分页获取单个用户加入的所有群组
     * 
     * @param  string $username 用户名
     * @param  int    $pageSize 每页获取的群组数量。该参数仅适用于分页获取方法。默认取 10 条。
     * @param  int    $pageNum  当前页码。该参数仅适用于分页获取方法。
     * @return array            群组信息或者错误
     * 
     * \~english
     * \brief
     * Paged access to all groups joined by a single user
     * 
     * @param  string $username User name
     * @param  int    $pageSize Number of groups obtained per page. This parameter is only applicable to the paging get method. 10 by default.
     * @param  int    $pageNum  Current page number. This parameter is only applicable to the paging get method.
     * @return array            Group information or error
     */
    public function listGroupsUserJoined($username, $pageSize = 10, $pageNum = 1)
    {
        if (!trim($username)) {
            \Easemob\exception('Please pass the user name');
        }
        $pageSize = (int) $pageSize > 0 ? (int) $pageSize : 0;
        $pageNum = (int) $pageNum > 0 ? (int) $pageNum : 1;
        $uri = $this->auth->getBaseUri() . '/users/' . $username . '/joined_chatgroups';
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
     * brief
     * 获取单个用户加入的所有群组
     * 
     * @param  string $username 用户名
     * @return array            群组信息或者错误
     * 
     * \~english
     * brief
     * Get all groups joined by a single user
     * 
     * @param  string $username User name
     * @return array            Group information or error
     */
    public function listAllGroupsUserJoined($username)
    {
        return $this->listGroupsUserJoined($username, 0);
    }

    /**
     * \~chinese
     * \brief
     * 获取群组详情
     * 
     * \details
     * 可以获取一个或多个群组的详情。当获取多个群组的详情时，返回所有存在的群组的详情；对于不存在的群组，返回 “group id doesn’t exist”。
     * 
     * @param  string $groupId 群组 ID，可以以逗号分割，同时传递多个群组 ID
     * @return array           群组信息或者错误
     * 
     * \~english
     * \brief
     * Get group details
     * 
     * \details
     * You can get the details of one or more groups. When the details of multiple groups are obtained, the details of all existing groups are returned; For group ID 'exist', return 'EST'.
     * 
     * @param  string $groupId Group ID, which can be separated by commas, and multiple group IDs can be passed at the same time
     * @return array           Group information or error
     */
    public function getGroup($groupId)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please pass the group ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId;
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return count($data['data']) > 1 ? $data['data'] : $data['data'][0];
    }

    /**
     * \~chinese
     * \brief
     * 创建公开群
     * 
     * \details
     * 创建一个公开的群组，并设置群主、群组名称、群组描述、群成员、群成员最大人数（包括群主）、加入群是否需要批准、群组扩展信息。
     * 
     * @param  string  $owner        群组管理员的用户名
     * @param  string  $groupname    群组名称，最大长度为 128 字符。
     * @param  string  $desc         群组描述，最大长度为 512 字符。
     * @param  array   $members      群组成员，此属性为非必需，但是如果加此项，数组元素至少一个，不能超过 100 个。（注：群主 user1 不需要写入到 members 里面）
     * @param  int     $maxusers     群组最大成员数（包括群主），值为数值类型，默认值 200，具体上限请参考 <a href="https://console.easemob.com/user/login" target="_blank">环信即时通讯云控制台</a>。
     * @param  boolean $members_only 用户申请入群是否需要群主或者群管理员审批，默认是 false。true：是；false：否。
     * @param  string  $custom       群组扩展信息，例如可以给群组添加业务相关的标记，不要超过 1024 字符。
     * @return string|array          群组 id 或者错误
     * 
     * \~english
     * \brief
     * Create public group
     * 
     * \details
     * Create a public group, and set the group owner, group name, group description, group members, the maximum number of group members (including group owners), whether to join the group requires approval, and group extension information.
     * 
     * @param  string  $owner        User name of the group administrator
     * @param  string  $groupname    Group name, with a maximum length of 128 characters.
     * @param  string  $desc         Group description, with a maximum length of 512 characters.
     * @param  array   $members      This attribute is not required for group members, but if this item is added, there must be at least one array element, not more than 100. (Note: group leader user1 does not need to be written into members)
     * @param  int     $maxusers     The maximum number of group members (including group owners), the value is numerical type, the default value is 200, please refer to the specific upper limit <a href="https://console.easemob.com/user/login" target="_blank">easemob console</a>。
     * @param  boolean $members_only Whether the user's application to join the group needs the approval of the group owner or group administrator. The default is false. True: Yes; False: No.
     * @param  string  $custom       Group extension information, for example, you can add business-related tags to the group, which should not exceed 1024 characters.
     * @return string|array          Group ID or error
     */
    public function createPublicGroup($owner, $groupname, $desc, $members = array(), $maxusers = 200, $members_only = true, $custom = '')
    {
        // 公开群
        $public = true;
        // 公开群（public 为 true），则不允许群成员邀请别人加入此群
        $allowinvites = false;
        $data = compact('owner', 'groupname', 'desc', 'public', 'maxusers', 'allowinvites', 'members_only', 'members', 'custom');
        return $this->create($data);
    }

    /**
     * \~chinese
     * \brief
     * 创建私有群
     * 
     * \details
     * 创建一个私有的群组，并设置群主、群组名称、群组描述、群成员、群成员最大人数（包括群主）、是否允许群成员邀请别人加入此群、群组扩展信息。
     * 
     * @param  string  $owner        群组管理员的用户名
     * @param  string  $groupname    群组名称，最大长度为 128 字符。
     * @param  string  $desc         群组描述，最大长度为 512 字符。
     * @param  array   $members      群组成员，此属性为非必需，但是如果加此项，数组元素至少一个，不能超过 100 个。（注：群主 user1 不需要写入到 members 里面）
     * @param  int     $maxusers     群组最大成员数（包括群主），值为数值类型，默认值 200，具体上限请参考 <a href="https://console.easemob.com/user/login" target="_blank">环信即时通讯云控制台</a>。
     * @param  boolean $allowinvites 是否允许群成员邀请别人加入此群：true：允许群成员邀请人加入此群；false：只有群主或者管理员才可以往群里加人。
     * @param  string  $custom       群组扩展信息，例如可以给群组添加业务相关的标记，不要超过 1024 字符。
     * @return string|array          群组 id 或者错误
     * 
     * \~english
     * \brief
     * Create private group
     * 
     * \details
     * Create a private group, and set the group owner, group name, group description, group members, maximum number of group members (including group owner), whether to allow group members to invite others to join the group, and group extension information.
     * 
     * @param  string  $owner        User name of the group administrator
     * @param  string  $groupname    Group name, with a maximum length of 128 characters.
     * @param  string  $desc         Group description, with a maximum length of 512 characters.
     * @param  array   $members      This attribute is not required for group members, but if this item is added, there must be at least one array element, not more than 100. (Note: group leader user1 does not need to be written into members)
     * @param  int     $maxusers     The maximum number of group members (including group owners), the value is numerical type, the default value is 200, please refer to the specific upper limit <a href="https://console.easemob.com/user/login" target="_blank">easemob console</a>。
     * @param  boolean $allowinvites Allow group members to invite others to join the group: true: allow group members to invite people to join the group; False: only the group leader or administrator can add people to the group.
     * @param  string  $custom       Group extension information, for example, you can add business-related tags to the group, which should not exceed 1024 characters.
     * @return string|array          Group ID or error
     */
    public function createPrivateGroup($owner, $groupname, $desc, $members = array(), $maxusers = 200, $allowinvites = false, $custom = '')
    {
        // 私有群
        $public = false;
        // 如果允许了群成员邀请用户进群（allowinvites 为 true），那么就不需要群主或群管理员审批了
        $members_only = $allowinvites ? false : true;
        $data = compact('owner', 'groupname', 'desc', 'public', 'maxusers', 'allowinvites', 'members_only', 'members', 'custom');
        return $this->create($data);
    }

    /**
     * \~chinese
     * \brief
     * 修改群组信息
     * 
     * \details
     * 修改成功的数据行会返回 true，失败为 false。请求 body 只接收 groupname、description、maxusers、membersonly、allowinvites、custom 六个属性，传不存在的字段，或者不能修改的字段会抛异常。
     * 
     * @param  array   $data 群组信息
     *     - `groupname` string 类型，群组名称，修改时值不能包含斜杠（“/”），最大长度为 128 字符。
     *     - `description` string 类型，群组描述，修改时值不能包含斜杠（“/”），最大长度为 512 字符。
     *     - `maxusers` int 类型，群组成员最大数（包括群主），值为数值类型。
     *     - `membersonly` string 类型，加入群组是否需要群主或者群管理员审批：true：是；false：否。
     *     - `allowinvites` string 类型，是否允许群成员邀请别人加入此群：true：允许群成员邀请人加入此群；false：只有群主才可以往群里加人。
     *     - `custom` string 类型，群组扩展信息，例如可以给群组添加业务相关的标记，不要超过 1,024 字符。
     * @return boolean|array 成功或者错误
     * 
     * \~english
     * \brief
     * Modify group information
     * 
     * \details
     * The modified data row will return true, and the failure will be false. The request body only receives six attributes: groupname, description, maxusers, members only, allowinvites and custom. If it passes non-existent fields or fields that cannot be modified, exceptions will be thrown.
     * 
     * @param  array   $data Group information
     *     - `groupname` string type. Group name. When modified, the value cannot contain slash ("/"), and the maximum length is 128 characters.
     *     - `description` string type. Group description. When modifying, the value cannot contain slash ("/"), and the maximum length is 512 characters.
     *     - `maxusers` int type. The maximum number of group members (including group owners) is the numerical value type.
     *     - `membersonly` string type. Whether to join the group requires the approval of the group owner or group administrator: true: Yes; False: No.
     *     - `allowinvites` string type. Allow group members to invite others to join the group: true: allow group members to invite people to join the group; False: only the group leader can add people to the group.
     *     - `custom` string type. Group extension information, for example, you can add business-related tags to the group, which should not exceed 1024 characters.
     * @return boolean|array Success or error
     */
    public function updateGroup($data)
    {
        $data['group_id'] = isset($data['group_id']) ? trim($data['group_id']) : '';
        $groupId = $data['group_id'];
        if (!$groupId) {
            \Easemob\exception('Please pass the group ID');
        }
        unset($data['group_id']);
        foreach ($data as $field => $value) {
            if (!in_array($field, self::$modifiedAllowedField)) {
                \Easemob\exception('Only groupname, description, maxusers, members only, allowinvites and custom are allowed at most');
            }
        }

        if (isset($data['groupname'])) {
            $data['groupname'] = trim($data['groupname']);
            if (!$data['groupname']) {
                \Easemob\exception('Group name cannot be empty');
            } elseif (preg_match('/\//', $data['groupname'])) {
                \Easemob\exception('The group name cannot contain a diagonal bar ("/)');
            } elseif (mb_strlen($data['groupname']) > 128) {
                \Easemob\exception('The maximum length of the group name is 128 characters');
            }
        }

        if (isset($data['description'])) {
            $data['description'] = trim($data['description']);
            if (!$data['description']) {
                \Easemob\exception('Group description cannot be empty');
            } elseif (preg_match('/\//', $data['description'])) {
                \Easemob\exception('The group description cannot contain diagonal bars ("/")');
            } elseif (mb_strlen($data['description']) > 512) {
                \Easemob\exception('The maximum length of the group description is 512 characters');
            }
        }

        // 获取原始群组信息
        $info = $this->getGroup($groupId);

        if (isset($data['maxusers'])) {
            $data['maxusers'] = (int) $data['maxusers'];
        }

        // 如果是公开群（public为true），则不允许群成员邀请别人加入此群
        if ($info['public'] && isset($data['allowinvites'])) {
            $data['allowinvites'] = false;
        }

        if (isset($data['membersonly'])) {
            // 如果允许了群成员邀请用户进群（allowinvites为true），那么就不需要群主或群管理员审批了
            $data['membersonly'] = $info['allowinvites'] ? false : (boolean) $data['membersonly'];
        }

        if (isset($data['allowinvites'])) {
            $data['allowinvites'] = (boolean) $data['allowinvites'];

            if (isset($data['membersonly'])) {
                // 如果允许了群成员邀请用户进群（allowinvites为true），那么就不需要群主或群管理员审批了
                $data['membersonly'] = $data['allowinvites'] ? false : (boolean) $data['membersonly'];
            }
        }

        if (isset($data['custom'])) {
            $data['custom'] = trim($data['custom']);
            if ($data['custom'] && mb_strlen($data['custom']) > 1024) {
                \Easemob\exception('The maximum length of group extension information is 1024 characters');
            }
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId;
        $resp = Http::put($uri, $data, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 删除群组
     * 
     * @param  string  $groupId 群组 id
     * @return boolean|array    成功或者错误
     * 
     * \~english
     * \brief
     * Delete Group
     * 
     * @param  string  $groupId Group ID
     * @return boolean|array    Success or error
     */
    public function destroyGroup($groupId)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please pass the group ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * \~chinese
     * \brief
     * 获取群组公告
     * 
     * \details
     * 获取指定群组 ID 的群组公告。
     * 
     * @param  string $groupId 群组 id
     * @return array           公告信息或者错误
     * 
     * \~english
     * \brief
     * Get group announcements
     * 
     * \details
     * Gets the group announcement of the specified group ID.
     * 
     * @param  string $groupId Group ID
     * @return array           Announcement information or error
     */
    public function getGroupAnnouncement($groupId)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please enter group ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/announcement';
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
     * 修改群组公告
     * 
     * \details
     * 修改指定群组 ID 的群组公告，注意群组公告的内容不能超过 512 个字符。
     * 
     * @param  string  $groupId      群组 ID
     * @param  string  $announcement 群组公告内容
     * @return boolean|array         成功或者错误
     * 
     * \~english
     * \brief
     * Modify group announcement
     * 
     * \details
     * Modify the group announcement of the specified group ID. note that the content of the group announcement cannot exceed 512 characters.
     * 
     * @param  string  $groupId      Group ID
     * @param  string  $announcement Group announcement content
     * @return boolean|array         Success or error
     */
    public function updateGroupAnnouncement($groupId, $announcement)
    {
        if (!trim($groupId) || !trim($announcement)) {
            \Easemob\exception('Please enter the group ID and announcement content');
        }

        if (mb_strlen($announcement) > 512) {
            \Easemob\exception('The content of the group announcement cannot exceed 512 characters');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/announcement';
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
     * 获取群组共享文件
     * 
     * \details
     * 分页获取指定群组 ID 的群组共享文件，之后可以根据 response 中返回的 file_id，file_id 是群组共享文件的唯一标识，调用 {@link #downloadGroupShareFile($fileName, $groupId, $fileId)} 下载文件，或调用 {@link #deleteGroupShareFile($groupId, $fileId)} 删除文件。
     * 
     * @param  string $groupId  群组 ID
     * @param  int    $pageSize 每页获取的群组数量。该参数仅适用于分页获取方法。默认取 10 条。
     * @param  int    $pageNum  当前页码。该参数仅适用于分页获取方法。
     * @return array            群组文件信息或者错误
     * 
     * \~english
     * \brief
     * Get group shared files
     * 
     * \details
     * Get the group shared file of the specified group ID by paging, and then according to the file returned in the response_ id，file_ ID is the unique identification of the group shared file, call {@link #downloadGroupShareFile($fileName, $groupId, $fileId)} download files, or call {@link #deleteGroupShareFile($groupId, $fileId)} delete file.
     * 
     * @param  string $groupId  Group ID
     * @param  int    $pageSize Number of groups obtained per page. This parameter is only applicable to the paging get method. 10 by default.
     * @param  int    $pageNum  Current page number. This parameter is only applicable to the paging get method.
     * @return array            Group file information or error
     */
    public function getGroupShareFiles($groupId, $pageSize = 10, $pageNum = 1)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please enter the group ID');
        }

        $pageSize = (int) $pageSize > 0 ? (int) $pageSize : 0;
        $pageNum = (int) $pageNum > 0 ? (int) $pageNum : 1;
        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/share_files';
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
     * 上传群组共享文件
     * 
     * \details
     * 上传指定群组 ID 的群组共享文件。注意上传的文件大小不能超过 10 MB。
     * 
     * @param  string $groupId  群组 ID
     * @param  string $fileName 上传的文件路径
     * @return array            上传的文件信息或者错误
     * 
     * \~english
     * \brief
     * Upload group shared files
     * 
     * \details
     * Upload the group shared file with the specified group ID. Note that the uploaded file size cannot exceed 10 Mb.
     * 
     * @param  string $groupId  Group ID
     * @param  string $fileName Uploaded file path
     * @return array            Uploaded file information or error
     */
    public function uploadGroupShareFile($groupId, $fileName)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please enter the group ID');
        }

        if (!trim($fileName)) {
            \Easemob\exception('Please pass in the attachment name');
        }

        $file = fopen($fileName, 'rb');
        if ($file === false) {
            \Easemob\exception('The attachment cannot be read');
        }

        $stat = fstat($file);
        $size = $stat['size'];
        $data = fread($file, $size);
        fclose($file);
        $mimeType = mime_content_type($fileName) ? mime_content_type($fileName) : null;
        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/share_files';
        $resp = Http::multipartPost($uri, $fileName, $data, $mimeType, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data'];
    }

    /**
     * \~chinese
     * \brief
     * 下载群组共享文件
     * 
     * \details
     * 根据指定的群组 ID 与 file_id 下载群组共享文件，file_id 通过 {@link #getGroupShareFiles($groupId, $pageSize = 10, $pageNum = 1)} 获取。
     * 
     * @param  string  $fileName 要下载的文件路径
     * @param  string  $groupId  群组 ID
     * @param  string  $fileId   群组共享文件 id
     * @return int|array         文件大小或者错误
     * 
     * \~english
     * \brief
     * Download group shared files
     * 
     * \details
     * According to the specified group ID and file_id download group shared file, file_id is obtained through {@link #getGroupShareFiles($groupId, $pageSize = 10, $pageNum = 1)}.
     * 
     * @param  string  $fileName File path to download
     * @param  string  $groupId  Group ID
     * @param  string  $fileId   file_id
     * @return int|array         File size or error
     */
    public function downloadGroupShareFile($fileName, $groupId, $fileId)
    {
        if (!trim($fileName) || !trim($fileId) || !trim($fileId)) {
            \Easemob\exception('Please enter the file path, group ID and group shared file ID to download');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/share_files/' . $fileId;
        $resp = Http::get($uri, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $dir = dirname($fileName);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        return file_put_contents($fileName, $resp->body);
    }

    /**
     * \~chinese
     * \brief
     * 删除群组共享文件
     * 
     * \details
     * 根据指定的群组 ID 与 file_id 删除群组共享文件，file_id 通过 {@link #getGroupShareFiles($groupId, $pageSize = 10, $pageNum = 1)} 获取。
     * 
     * @param  string  $groupId 群组 ID
     * @param  string  $fileId  群组共享文件 id
     * @return boolean|array    成功或者错误
     * 
     * \~english
     * \brief
     * Delete group shared files
     * 
     * \details
     * According to the specified group ID and file_id delete group shared file, file_id is obtained through {@link #getGroupShareFiles($groupId, $pageSize = 10, $pageNum = 1)}。
     * 
     * @param  string  $groupId Group ID
     * @param  string  $fileId  file_id
     * @return boolean|array    Success or error
     */
    public function deleteGroupShareFile($groupId, $fileId)
    {
        if (!trim($groupId) || !trim($fileId)) {
            \Easemob\exception('Please pass in the group ID and the group shared file ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/share_files/' . $fileId;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['data']['result'];
    }

    /**
     * \~chinese
     * \brief
     * 分页获取群组成员
     * 
     * @param  string $groupId  群组 ID
     * @param  int    $pageSize 每页成员数量，默认值为 10，最大为 100。
     * @param  int    $pageNum  当前页码。默认值为 1。
     * @return array            群组成员信息或者错误
     * 
     * \~english
     * \brief
     * Paging get group members
     * 
     * @param  string $groupId  Group ID
     * @param  int    $pageSize Number of members per page. The default value is 10 and the maximum value is 100.
     * @param  int    $pageNum  Current page number. The default value is 1.
     * @return array            Group member information or error
     */
    public function listGroupMembers($groupId, $pageSize = 10, $pageNum = 1)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please enter the group ID');
        }

        $pageSize = (int) $pageSize > 0 ? (int) $pageSize : 0;
        $pageNum = (int) $pageNum > 0 ? (int) $pageNum : 1;

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/users';
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
     * 获取群组所有成员
     * 
     * @param  string $groupId 群组 ID
     * @return array           群组成员信息或者错误
     * 
     * \~english
     * \brief
     * Get all members of the group
     * 
     * @param  string $groupId Group ID
     * @return array           Group member information or error
     */
    public function listAllGroupMembers($groupId)
    {
        return $this->listGroupMembers($groupId, 0);
    }

    /**
     * \~chinese
     * \brief
     * 添加单个群组成员
     * 
     * \details
     * 一次给群添加一个成员，不能重复添加同一个成员。如果用户已经是群成员，将添加失败，并返回错误。
     * 
     * @param  string  $groupId  群组 ID
     * @param  string  $username 环信用户 ID
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Add individual group members
     * 
     * \details
     * Add one member to the group at a time. You cannot add the same member repeatedly. If the user is already a member of the group, the addition fails with an error.
     * 
     * @param  string  $groupId  Group ID
     * @param  string  $username User name
     * @return boolean|array     Success or error
     */
    public function addGroupMember($groupId, $username)
    {
        return $this->addUsers($groupId, $username);
    }

    /**
     * \~chinese
     * \brief
     * 批量添加群组成员
     * 
     * \details
     * 为群组添加多个成员，一次最多可以添加 60 位成员。如果所有用户均已是群成员，将添加失败，并返回错误。
     * 
     * @param  string  $groupId   群组 ID
     * @param  array   $usernames 环信用户 ID 数组
     * @return boolean|array      成功或者错误
     * 
     * \~english
     * \brief
     * Batch add group members
     * 
     * \details
     * Add multiple members to the group. You can add up to 60 members at a time. If all users are already members of the group, the addition fails with an error.
     * 
     * @param  string  $groupId   Group ID
     * @param  array   $usernames User name
     * @return boolean|array      Success or error
     */
    public function addGroupMembers($groupId, $usernames)
    {
        return $this->addUsers($groupId, $usernames);
    }

    /**
     * \~chinese
     * \brief
     * 移除单个群组成员
     * 
     * \details
     * 从群中移除某个成员。如果被移除用户不是群成员，将移除失败，并返回错误。
     * 
     * @param  string  $groupId   群组 ID
     * @param  string  $username  环信用户 ID
     * @return boolean|array      成功或者错误
     * 
     * \~english
     * \brief
     * Remove individual group members
     * 
     * \details
     * Remove a member from the group. If the removed user is not a member of the group, the removal fails with an error.
     * 
     * @param  string  $groupId   Group ID
     * @param  string  $username  User name
     * @return boolean|array      Success or error
     */
    public function removeGroupMember($groupId, $username)
    {
        return $this->removeUsers($groupId, $username);
    }

    /**
     * \~chinese
     * \brief
     * 批量移除群组成员
     * 
     * \details
     * 移除群成员，用户名之间用英文逗号分隔。建议一次最多移除 60 个群成员。如果所有被移除用户均不是群成员，将移除失败，并返回错误。
     * 
     * @param  string  $groupId   群组 ID
     * @param  array   $username  环信用户 ID 数组
     * @return boolean|array      成功或者错误
     * 
     * \~english
     * \brief
     * Batch remove group members
     * 
     * \details
     * Remove group members and separate user names with English commas. It is recommended to remove up to 60 group members at a time. If all the removed users are not members of the group, the removal fails with an error.
     * 
     * @param  string  $groupId   Group ID
     * @param  array   $username  User name array
     * @return boolean|array      Success or error
     */
    public function removeGroupMembers($groupId, $usernames)
    {
        return $this->removeUsers($groupId, $usernames);
    }

    /**
     * \~chinese
     * \brief
     * 获取群管理员列表
     * 
     * @param  string $groupId 群组 ID
     * @return array           群管理员信息或者错误
     * 
     * \~english
     * \brief
     * Get the list of group administrators
     * 
     * @param  string $groupId Group ID
     * @return array           Group administrator information or error
     */
    public function listGroupAdmins($groupId)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please enter the group ID');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/admin';
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
     * 添加群管理员
     * 
     * \details
     * 将一个群成员角色权限提升为群管理员。
     * 
     * @param  string  $groupId  群组 ID
     * @param  string  $newadmin 添加的新管理员用户 ID
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Add group administrator
     * 
     * \details
     * Promote the role permission of a group member to group administrator.
     * 
     * @param  string  $groupId  Group ID
     * @param  string  $newadmin User name
     * @return boolean|array     Success or error
     */
    public function addGroupAdmin($groupId, $newadmin)
    {
        if (!trim($groupId) || !trim($newadmin)) {
            \Easemob\exception('Please pass in the group ID and the new administrator user ID to be added');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/admin';
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
     * 移除群管理员
     * 
     * \details
     * 将用户的角色从群管理员降为群普通成员。
     * 
     * @param  string  $groupId  群组 ID
     * @param  string  $oldadmin 移除的管理员用户 ID
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Remove group administrator
     * 
     * \details
     * Reduce the user's role from group administrator to ordinary member of the group.
     * 
     * @param  string  $groupId  Group ID
     * @param  string  $oldadmin User name
     * @return boolean|array     Success or error
     */
    public function removeGroupAdmin($groupId, $oldadmin)
    {
        if (!trim($groupId) || !trim($oldadmin)) {
            \Easemob\exception('Please pass in the group ID and the administrator user ID to be removed');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/admin/' . $oldadmin;
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
     * 转让群组
     * 
     * \details
     * 修改群主为同一群组中的其他成员。
     * 
     * @param  string  $groupId  群组 ID
     * @param  string  $newowner 群组的新管理员用户 ID
     * @return boolean|array     成功或者错误
     * 
     * \~english
     * \brief
     * Transfer group
     * 
     * \details
     * Modify the group owner to other members in the same group.
     * 
     * @param  string  $groupId  Group ID
     * @param  string  $newowner User name
     * @return boolean|array     Success or error
     */
    public function updateGroupOwner($groupId, $newowner)
    {
        if (!trim($groupId) || !trim($newowner)) {
            \Easemob\exception('Please pass in the group ID and the new administrator user ID of the group');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId;
        $body = compact('newowner');
        $resp = Http::put($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * @ignore 创建群组
     * @param  array $data  群组信息
     * @return string|array 群组 id 或者错误
     */
    private function create($data)
    {
        $data['groupname'] = trim($data['groupname']);
        $data['desc'] = trim($data['desc']);
        $data['owner'] = trim($data['owner']);

        if (!isset($data['groupname']) || !$data['groupname']) {
            \Easemob\exception('Please pass the group name');
        }

        if (!isset($data['desc']) || !$data['desc']) {
            \Easemob\exception('Please pass the group description');
        }

        if (!isset($data['owner']) || !$data['owner']) {
            \Easemob\exception('Please pass the administrator of the group');
        }

        if (isset($data['members'])) {
            if (!is_array($data['members']) || empty($data['members'])) {
                \Easemob\exception('Please pass in group members. Group members must be arrays');
            } elseif (count($data['members']) > 100) {
                \Easemob\exception('Group members cannot exceed 100');
            } elseif (in_array($data['owner'], $data['members'])) {
                \Easemob\exception('The group leader does not need to be included in the group members');
            }
        }

        $data['public'] = (boolean) $data['public'];
        $data['maxusers'] = isset($data['maxusers']) && (int) $data['maxusers'] ? (int) $data['maxusers'] : 200;
        // 如果是公开群（public 为 true），则不允许群成员邀请别人加入此群
        $data['allowinvites'] = (boolean) $data['allowinvites'];
        // 如果允许了群成员邀请用户进群（allowinvites为true），那么就不需要群主或群管理员审批了
        $data['members_only'] = (boolean) $data['members_only'];
        $data['custom'] = trim($data['custom']);

        $uri = $this->auth->getBaseUri() . '/chatgroups';
        $resp = Http::post($uri, $data, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return isset($data['data']['groupid']) ? $data['data']['groupid'] : $data['data'];
    }

    /**
     * @ignore （批量）添加群组成员
     * @param  string       $groupId   群组 ID
     * @param  string|array $usernames 环信用户 ID，string: 添加单个群组成员；array: 批量添加群组成员
     * @return boolean|array           成功或者错误
     */
    private function addUsers($groupId, $usernames)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please pass the group ID');
        }

        if ((is_array($usernames) && empty($usernames)) || (is_string($usernames) && !trim($usernames))) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/users';
        $uri .= is_array($usernames) ? '' : ('/' . $usernames);
        $body = is_array($usernames) ? compact('usernames') : null;
        $resp = Http::post($uri, $body, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }

    /**
     * @ignore（批量）移除群组成员
     * @param  string       $groupId   群组 ID
     * @param  string|array $usernames 环信用户 ID，string: 移除单个群组成员；array: 批量移除群组成员
     * @return boolean|array           成功或者错误
     */
    private function removeUsers($groupId, $usernames)
    {
        if (!trim($groupId)) {
            \Easemob\exception('Please pass the group ID');
        }

        if ((is_array($usernames) && empty($usernames)) || (is_string($usernames) && !trim($usernames))) {
            \Easemob\exception('Please pass the user name');
        }

        $uri = $this->auth->getBaseUri() . '/chatgroups/' . $groupId . '/users/';
        $uri .= is_array($usernames) ? implode(',', $usernames) : $usernames;
        $resp = Http::delete($uri, null, $this->auth->headers());
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        return true;
    }
}