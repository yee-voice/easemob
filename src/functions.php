<?php
/**
 * @ignore 公共函数库
 */
namespace Easemob;

/**
 * @ignore 返回错误信息
 * @param  Response $response 响应对象
 * @param  string   $message  错误信息
 * @param  int      $code     错误码
 * @return array              返回信息
 */
function error($response, $message = null, $code = 1)
{
    if ($response !== null) {
        $data = $response->data();
        return array(
            'code' => $response->httpCode,
            'error' => isset($data['error']) ? $data['error'] : $response->error,
            'error_description' => isset($data['error_description']) ? $data['error_description'] : '',
        );
    }
    return array(
        'code' => $code,
        'error' => $message,
    );
    // throw new \Exception($message, $code);
}

/**
 * @ignore 输出异常信息
 * @param string $message 异常信息
 * @param int    $code    错误码
 */
function exception($message, $code = 1)
{
    throw new \Exception($message, $code);
}