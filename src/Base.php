<?php

namespace Easemob;

use Easemob\Cache\ICache;

class Base {

    protected ICache $cache;

    public function setCache(ICache $cache) {
        $this->cache = $cache;
    }
    
}