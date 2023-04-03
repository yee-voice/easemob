<?php
namespace Easemob\Http;

/**
 * @ignore 响应体类
 * @final
 */
final class Response
{
    /**
     * @var int $httpCode http 状态码
     */
    public $httpCode;
    
    /**
     * @var number $duration 响应时长
     */
    public $duration;
    
    /**
     * @var mixed $headers 响应头
     */
    public $headers;
    
    /**
     * @var mixed $body 响应体
     */
    public $body;
    
    /**
     * @var mixed $error 错误信息
     */
    public $error;
    
    /**
     * @var mixed $data 响应数据
     */
    private $data;
    
    /**
     * @var array $statusText 状态码对应信息
     */
    private static $statusText = array(
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        408 => 'Request Timeout',
        413 => 'Request Entity Too Large',
        415 => 'Unsupported Media Type',
        429 => 'Too Many Requests',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
    );

    /**
     * 构造方法
     * @param int    $httpCode 状态码
     * @param double $duration 执行时间
     * @param mixed  $headers  响应头
     * @param mixed  $body     响应体
     * @param mixed  $error    错误信息
     */
    public function __construct($httpCode, $duration, $headers = null, $body = null, $error = null)
    {
        $this->httpCode = $httpCode;
        $this->duration = $duration;
        $this->headers = $headers;
        $this->body = $body;

        if ($error !== null) {
            return;
        }

        if ($body !== null) {
            $this->data = json_decode($body, true);
            $error = isset($this->data['error']) && $this->data['error'] ? $this->data['error'] : $error;
        }

        if ($error === null) {
            $error = isset(self::$statusText[$httpCode]) ? self::$statusText[$httpCode] : $error;
        }
        
        $this->error = $error;
    }

    /**
     * 查看请求是否成功
     * @return boolean 请求是否成功
     */
    public function ok()
    {
        return $this->httpCode >= 200 && $this->httpCode < 300 && $this->error == null;
    }

    /**
     * 获取响应数据
     * @return array 响应数据
     */
    public function data()
    {
        return $this->data;
    }
}