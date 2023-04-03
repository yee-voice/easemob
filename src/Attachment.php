<?php
namespace Easemob;

use Easemob\Http\Http;

/**
 * \~chinese
 * Attachment 用来上传下载附件
 * 
 * \~english
 * The `Attachment` is used to upload and download attachments
 */
final class Attachment
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
     * 文件上传
     * 
     * \details 
     * - 上传文件的大小不能超过 10 M，超过会上传失败。
     * - 在上传文件的时候可以选择是否限制访问权限，如果选择限制的话，会在上传请求完成后返回一个 secret，只有知道这个 secret，并且是 app 的注册用户，才能够下载文件。如果选择不限制的话，则只要是 app 的注册用户就能够下载。
     * - 如选择加 secret 限制的话，消息回调（包含发送前回调和发送后回调）、历史消息这些功能中涉及下载文件时，都需要在下载 url 中拼接 secret，才能正常下载文件；
     * - 拼接规则如下：url?share-secret=secret
     * 
     * @param  string  $fileName       上传的附件
     * @param  boolean $restrictAccess 控制文件是否可以被任何人获取，这个值为 true，返回结果中会添加一个 share-secret 值。再次访问文件需要用到这个值。默认值：false
     * @return array                   上传文件信息或者错误
     * 
     * \~english
     * \brief
     * File upload
     * 
     * \details
     * - The size of the uploaded file cannot exceed 10m. If it exceeds 10m, the upload will fail.
     * - When uploading files, you can choose whether to restrict access rights. If you choose to restrict, a secret will be returned after the upload request is completed. Only those who know the secret and are registered users of the app can download files. If you choose not to limit, you can download as long as you are a registered user of the app.
     * - If you choose to add the secret limit, the message callback (including the callback before sending and the callback after sending) and the historical message, which involve downloading files, need to splice the secret in the download URL to download files normally;
     * - The splicing rules are as follows: url?share-secret=secret
     * 
     * @param  string  $fileName       Uploaded attachments
     * @param  boolean $restrictAccess Controls whether the file can be obtained by anyone. This value is true. A share secret value will be added to the returned result. This value is required to access the file again. Default: false
     * @return array                   Upload file information or error
     */
    public function uploadFile($fileName, $restrictAccess = false)
    {
        if (!trim($fileName)) {
            \Easemob\exception('Please pass in the attachment name');
        }
        
        $file = fopen($fileName, 'rb');
        if ($file === false) {
            \Easemob\exception('The attachment cannot be read');
        }

        $restrictAccess = (bool)$restrictAccess;
        $headers = $this->auth->headers();
        if ($restrictAccess) {
            $headers['restrict-access'] = $restrictAccess;
        }
        
        $stat = fstat($file);
        $size = $stat['size'];
        $data = fread($file, $size);
        fclose($file);
        $mimeType = mime_content_type($fileName) ? mime_content_type($fileName) : null;
        $uri = $this->auth->getBaseUri() . '/chatfiles';
        $resp = Http::multipartPost($uri, $fileName, $data, $mimeType, $headers);
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $data = $resp->data();
        return $data['entities'][0];
    }

    /**
     * \~chinese
     * \brief
     * 下载附件
     * 
     * \details
     * 这里需要注意的就是，如果上传文件时候选择了文件不共享，需要在请求头中带上上面返回的 share-secret 和当前登录用户的 token 才能够下载。
     * 
     * @param  string  $fileName    下载的文件名
     * @param  string  $uuid        文件唯一 ID，文件上传成功后会返回
     * @param  string  $shareSecret share-secret，文件上传成功后会返回
     * @return int|array            下载文件的大小或者错误
     * 
     * \~english
     * \brief
     * Download attachments
     * 
     * \details
     * It should be noted here that if you choose not to share the file when uploading the file, you need to bring the share secret returned above and the token of the currently logged in user in the request header to download it.
     * 
     * @param  string  $fileName    Downloaded file name
     * @param  string  $uuid        Unique ID of the file, which will be returned after the file is uploaded successfully
     * @param  string  $shareSecret share-secret，it will return after the file is uploaded successfully
     * @return int|array            Download file size or error
     */
    public function downloadFile($fileName, $uuid, $shareSecret = '')
    {
        return $this->download($fileName, $uuid, $shareSecret);
    }

    /**
     * \~chinese
     * \brief
     * 下载缩略图
     * 
     * \details
     * 在服务器端支持自动的创建图片的缩略图。可以先下载缩略图，当用户有需求的时候，再下载大图。 这里和下载大图唯一不同的就是 header 中多了一个“thumbnail: true”，当服务器看到过来的请求的 header 中包括这个的时候，就会返回缩略图，否则返回原始大图。
     * 
     * @param  string $fileName    下载缩略图的文件名
     * @param  string $uuid        文件唯一 ID，文件上传成功后会返回
     * @param  string $shareSecret share-secret，文件上传成功后会返回
     * @return int|array           下载缩略图的大小或者错误
     * 
     * \~english
     * \brief
     * Download thumbnails
     * 
     * \details
     * The server side supports the automatic creation of thumbnails of pictures. You can download thumbnails first, and then download large images when users need them. The only difference between this and downloading the big picture is that there is an "thumbnail: true" in the header. When the server sees that this is included in the header of the request, it will return the thumbnail, otherwise it will return the original big picture.
     * 
     * @param  string $fileName    Download thumbnail file name
     * @param  string $uuid        Unique ID of the file, which will be returned after the file is uploaded successfully
     * @param  string $shareSecret share-secret，it will return after the file is uploaded successfully
     * @return int|array           Download file size or error
     */
    public function downloadThumb($fileName, $uuid, $shareSecret = '')
    {
        return $this->download($fileName, $uuid, $shareSecret, true);
    }

    /**
     * @ignore 下载附件
     * @param  string  $fileName    下载的文件名
     * @param  string  $uuid        文件唯一 ID，文件上传成功后会返回
     * @param  string  $shareSecret share-secret，文件上传成功后会返回
     * @param  boolean $thumb       下载缩略图标识
     * @return int|array            下载文件的大小或者错误
     */
    private function download($fileName, $uuid, $shareSecret = '', $thumb = false)
    {
        $uri = $this->auth->getBaseUri() . '/chatfiles/' . $uuid;
        $headers = $this->auth->headers();
        if ($shareSecret) {
            $headers['share-secret'] = $shareSecret;
        }
        if ($thumb) {
            $headers['thumbnail'] = true;
        }
        $resp = Http::get($uri, $headers);
        if (!$resp->ok()) {
            return \Easemob\error($resp);
        }
        $dir = dirname($fileName);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        return file_put_contents($fileName, $resp->body);
    }
}
