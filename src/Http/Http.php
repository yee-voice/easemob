<?php
namespace Easemob\Http;

/**
 * Http 请求类
 * @final
 */
final class Http
{
    /**
     * @var string $proxyIp 代理 ip
     */
    public static $proxyIp;

    /**
     * @var string $proxyPort 代理端口
     */
    public static $proxyPort;

    /**
     * @var string $proxyUser 代理用户名
     */
    public static $proxyUser;

    /**
     * @var string $proxyUser 代理密码
     */
    public static $proxyPass;

    /**
     * 设置代理信息
     * @param string $proxyIp   代理 ip
     * @param int    $proxyPort 代理端口
     * @param string $proxyUser 代理用户名
     * @param string $proxyPass 代理密码
     * @example
     * <pre>
     * \Easemob\Http\Http::setProxy("ip", 8080);
     * </pre>
     */
    public static function setProxy($proxyIp, $proxyPort = 80, $proxyUser = '', $proxyPass = '')
    {
        self::$proxyIp = $proxyIp;
        self::$proxyPort = $proxyPort;
        self::$proxyUser = $proxyUser;
        self::$proxyPass = $proxyPass;
    }

    /**
     * @ignore 发送 get 请求
     * @param  string   $uri     请求 uri
     * @param  mixed    $headers 请求头
     * @return Response          响应对象
     */
    public static function get($uri, $headers = null)
    {
        return self::send(new Request('GET', $uri, $headers));
    }

    /**
     * @ignore 发送 post 请求
     * @param  string   $uri     请求 uri
     * @param  mixed    $body    请求体
     * @param  mixed    $headers 请求头
     * @return Response          响应对象
     */
    public static function post($uri, $body, $headers = null)
    {
        return self::send(new Request('POST', $uri, $headers, $body));
    }

    /**
     * @ignore 发送 put 请求
     * @param  string   $uri     请求 uri
     * @param  mixed    $body    请求体
     * @param  mixed    $headers 请求头
     * @return Response          响应对象
     */
    public static function put($uri, $body, $headers = null)
    {
        return self::send(new Request('PUT', $uri, $headers, $body));
    }

    /**
     * @ignore 发送 delete 请求
     * @param  string   $uri     请求 uri
     * @param  mixed    $headers 请求头
     * @return Response          响应对象
     */
    public static function delete($uri, $body, $headers = null)
    {
        return self::send(new Request('DELETE', $uri, $headers, $body));
    }

    /**
     * @ignore 发送 http 请求
     * @param  Request $request 请求信息
     * @return Response         响应对象
     */
    public static function send($request)
    {
        $startTime = microtime(true);
        $ch = curl_init();
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // 将 curl_exec() 获取的信息以字符串返回，而不是直接输出。
            CURLOPT_SSL_VERIFYPEER => false,    // 禁止 cURL 验证证书
            CURLOPT_SSL_VERIFYHOST => false,    // 不检查服务器SSL证书名称
            CURLOPT_HEADER => true,             // 将头文件的信息作为数据流输出
            CURLOPT_NOBODY => false,            // true 时将不输出 BODY 部分。同时 Mehtod 变成了 HEAD。修改为 false 时不会变成 GET。
            CURLOPT_CUSTOMREQUEST => $request->method,  // 请求方法
            CURLOPT_URL => $request->uri,   // 请求地址
            CURLOPT_USERAGENT => 'EasemobServerSDK-PHP/' . PHP_VERSION,
        );

        if (!empty($request->headers)) {
            if (!isset($request->headers['Content-Type']) || !$request->headers['Content-Type']) {
                $request->headers['Content-Type'] = (!$request->body || is_array($request->body)) ? 'application/json' : 'application/x-www-form-urlencoded';
            }
            $options[CURLOPT_HTTPHEADER] = self::buildHeaders($request->headers);
        }

        if (!empty($request->body)) {
            $request->body = is_array($request->body) ? json_encode($request->body) : $request->body;
            $options[CURLOPT_POSTFIELDS] = $request->body;
        }

        if (self::$proxyIp && self::$proxyPort) {
            $options[CURLOPT_PROXY] = self::$proxyIp;
            $options[CURLOPT_PROXYPORT] = self::$proxyPort;
            if (self::$proxyUser && self::$proxyPass) {
                $options[CURLOPT_PROXYUSERPWD] = self::$proxyUser .':'. self::$proxyPass;
            }
        }

        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $endTime = microtime(true);
        $duration = $endTime - $startTime;
        $code = curl_errno($ch);
        if ($code !== 0) {
            $response = new Response(-1, $duration, null, null, curl_error($ch));
            curl_close($ch);
            return $response;
        }

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = self::parseHeaders(substr($result, 0, $headerSize));
        $body = substr($result, $headerSize);
        curl_close($ch);
        return new Response($code, $duration, $headers, $body, null);
    }

    /**
     * @ignore 发送上传附件请求
     * @param  string   $uri      请求 uri
     * @param  string   $fileName 附件名
     * @param  array    $fileBody 附件信息
     * @param  mixed    $mimeType 附件 mime 类型
     * @param  array    $headers  请求头
     * @return Response           响应对象
     */
    public static function multipartPost(
        $uri,
        $fileName,
        $fileBody,
        $mimeType = null,
        $headers = array()
    ) {
        $data = array();
        $mimeBoundary = md5(microtime());
        array_push($data, '--' . $mimeBoundary);
        $finalMimeType = empty($mimeType) ? 'application/octet-stream' : $mimeType;
        $finalFileName = self::escapeQuotes($fileName);
        array_push($data, "Content-Disposition: form-data; name=\"file\"; filename=\"$finalFileName\"");
        array_push($data, "Content-Type: $finalMimeType");
        array_push($data, '');
        array_push($data, $fileBody);

        array_push($data, '--' . $mimeBoundary . '--');
        array_push($data, '');

        $body = implode("\r\n", $data);
        $contentType = 'multipart/form-data; boundary=' . $mimeBoundary;
        $headers['Content-Type'] = $contentType;
        $headers['restrict-access'] = true;

        return self::send(new Request('POST', $uri, $headers, $body));
    }

    /**
     * @ignore 构造请求头信息
     * @param  array $headers 请求头信息
     * @return array          请求头信息
     */
    private static function buildHeaders($headers)
    {
        $headersArr = array();
        foreach ($headers as $key => $value) {
            array_push($headersArr, "{$key}: {$value}");
        }
        return $headersArr;
    }

    /**
     * @ignore 解析请求头信息
     * @param  string $headersRaw 请求头原始字符串
     * @return array              请求头信息
     */
    private static function parseHeaders($headersRaw)
    {
        $headers = array();
        $lines = explode("\r\n", $headersRaw);
        foreach ($lines as $line) {
            $item = explode(':', $line);
            if (trim($item[0])) {
                $headers[$item[0]] = isset($item[1]) ? trim($item[1]) : '';
            }
        }
        return $headers;
    }

    /**
     * @ignore 替换引号
     * @param  string $str 原始字符串
     * @return string      替换之后的字符串
     */
    private static function escapeQuotes($str)
    {
        $find = array("\\", "\"");
        $replace = array("\\\\", "\\\"");
        return str_replace($find, $replace, $str);
    }
}
