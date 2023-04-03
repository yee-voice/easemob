<?php
namespace Easemob\Cache;


class RedisCache implements ICache{

    private static $that;

    private static \Redis $redis;

    private function __construct() {
        
    }

    // 防止实例clone
    private function __clone() {

    }

    // 防止反序列化
    private function __wakeup(){}


    public static function getInstance($redis = null) {
        if(!empty($redis)) {
            self::$redis = $redis;
        }
        if (empty(self::$that)) {
            self::$that = new Self;
        }
        return self::$that;
    }

    private static function check() {
        if(empty(self::$redis)) {
            throw new \Exception('object redis is empty');
        }
    }

    public function get(string $key) {
        self::check();
        try {
            return self::$redis->get($key);
        } catch(\Exception $e) {
            throw $e;
        }
    }

    public function set(string $key, string $value, int $seconds) {
        self::check();
        try {
            self::$redis->setex($key, $seconds, $value);
        } catch(\Exception $e) {
            throw $e;
        }
    }

}