<?php

namespace Phvue\Common\Utils;

defined('MEMCACHE_COMPRESSED') or define('MEMCACHE_COMPRESSED', 2);

class MemcacheAdapter
{
    public static $memcache;

    public function __construct($config)
    {
        if (class_exists(\Memcache::class)) {
            self::$memcache = new \Memcache();
            self::$memcache->connect($config['host'], $config['port']);
        } else {
            self::$memcache = new \Memcached();
            self::$memcache->addServer($config['host'], $config['port']);
        }
        return self::$memcache;
    }

    public function set($key, $var, $flag = MEMCACHE_COMPRESSED, $expire = 3600)
    {
        if (class_exists(\Memcache::class)) {
            return self::$memcache->set($key, $var, $flag, $expire);
        } else {
            return self::$memcache->set($key, $var, $expire);
        }
    }

    public function __call($name, $arguments)
    {
        return self::$memcache->$name(...$arguments);
    }

}