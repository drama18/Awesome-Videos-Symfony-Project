<?php
/*
|-----------------------------------------------------------
| available only at Udemy.com | copyright netprogs.pl | further distribution is prohibited
|-----------------------------------------------------------
*/
namespace App\Utils;

use App\Utils\Interfaces\CacheInterface;

use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

class RedisCache implements CacheInterface {

    public $cache;
    public function __construct()
    {
        $this->cache =  new TagAwareAdapter(
            new RedisAdapter( RedisAdapter::createConnection('redis://localhost/1') )
        );
    }

}
