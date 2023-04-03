<?php

namespace Easemob\Cache;


interface ICache {
    public function get(string $key);
    public function set(string $key, string $value, int $seconds);
}