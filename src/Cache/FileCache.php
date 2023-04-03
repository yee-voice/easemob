<?php
namespace Easemob\Cache;

/**
 * @ignore 文件缓存类
 * @final
 */
final class FileCache implements ICache
{
    /**
     * 获取缓存 key
     * @param  string $name 缓存名称
     * @return string       缓存 key
     */
    public function getCacheKey($name)
    {
        return md5($name) . '.php';
    }

    /**
     * 获取缓存值
     * @param  string $name 缓存名称
     * @return mixed        缓存值
     */
    public function get($name)
    {
        $path = __DIR__ . '/../../runtime/cache';
        $filename = $path . '/' . $this->getCacheKey($name);
        if (!file_exists($filename)) {
            return null;
        }

        $content = file_get_contents($filename);
        $data = json_decode($content, true);
        return $data['expire'] <= time() ? null : $data['value'];
    }

    /**
     * 设置缓存值
     * @param  string $name   缓存名称
     * @param  mixed  $value  缓存值
     * @param  int    $expire 过期时间
     * @return boolean        是否设置成功
     */
    public function set($name, $value, $expire = 3600)
    {
        $path = __DIR__ . '/../../runtime/cache';
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $filename = $this->getCacheKey($name);
        $data = array(
            'value'  => $value,
            'expire' => time() + $expire,
        );
        
        $result = file_put_contents($path . '/' . $filename, json_encode($data));
        if ($result === false) {
            \Easemob\exception($path . " 目录无写入权限");
        }
        return true;
    }
}