<?php
namespace Easemob\Http;

/**
 * @ignore 请求体类
 * @final
 */
final class Request
{
    /**
     * @var string $uri 请求 uri
     */    
    public $uri;
    
    /**
     * @var string $method 请求方法
     */  
    public $method;
    
    /**
     * @var mixed $headers 请求头
     */  
    public $headers;
    
    /**
     * @var mixed $headers 请求体
     */  
    public $body;

    /**
     * 构造方法
     * @param string $method  请求方法
     * @param string $uri     请求 uri
     * @param mixed  $headers 请求头
     * @param mixed  $body    请求体
     */
    public function __construct($method, $uri, $headers = null, $body = null)
    {
        $this->method = strtoupper($method);
        $this->uri = $uri;
        $this->headers = $headers;
        $this->body = $body;
    }
}
