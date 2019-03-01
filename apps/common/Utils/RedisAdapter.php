<?php

namespace Phvue\Common\Utils;
use Exception;

/**
 * @package Phvue\Common\Utils
 */
class RedisAdapter
{
    private static $_redis;

    private $overrideMethods = [
        'psetex', 'sScan', 'zScan', 'hScan', 'hGet', 'set', 'setex', 'setnx', 'incrByFloat', 'incrBy', 'decrBy',
        'lPush', 'rPush', 'lPushx', 'rPushx', 'lIndex', 'lGet', 'lSet', 'lRange', 'lGetRange', 'lTrim', 'listTrim',
        'lRem', 'lRemove', 'lInsert', 'sAdd', 'sAddArray', 'sRem', 'sRemove', 'sIsMember', 'sContains', 'sRandMember',
        'getSet', 'move', 'expire', 'pExpire', 'setTimeout', 'expireAt', 'pExpireAt', 'append', 'getRange', 'substr',
        'setRange', 'bitpos', 'getBit', 'setBit', 'sort', 'zAdd', 'zRange', 'zRem', 'zDelete', 'zRevRange',
        'zRangeByScore', 'zRevRangeByScore', 'zRangeByLex', 'zRevRangeByLex', 'zCount', 'zRemRangeByScore',
        'zDeleteRangeByScore', 'zRemRangeByRank', 'zDeleteRangeByRank', 'zScore', 'zRank', 'zRevRank', 'zIncrBy',
        'hSet', 'hSetNx', 'hDel', 'hExists', 'hIncrBy', 'hIncrByFloat', 'hMset', 'hMGet', 'restore', 'pfAdd',
        'get','watch','exists','incr','decr','lPop','rPop','lLen','lSize','sCard','sPop','sMembers','sGetMembers',
        'type','strlen','bitCount','ttl','pttl','persist','zCard','zSize','hLen','hKeys','hVals','hGetAll','dump','pfCount'
    ];

    private $checkMethods = [
        'psetex', 'set', 'setex', 'setnx', 'setRange', 'setBit', 'hMset', 'lSet', 'getSet', 'hSet', 'hSetNx',
    ];

    public function __construct($doConnect = true, $persistent = false, $config = [])
    {
        self::$_redis = new \Redis();

        if ($doConnect) {
            $config = empty($config) ? config('database.redis') : $config;
            $connect = 'connect';
            $timeout = 3600;
            if ($persistent) {
                ini_set('default_socket_timeout', -1);
                $connect = 'pconnect';
                $timeout = 0;
            }
            if (!self::$_redis->$connect($config['host'], $config['port'], $timeout) ||
                (!empty($config['auth']) && !self::$_redis->auth($config['auth']))
            ) {
                throw new Exception("Redis-connect fail！");
            }
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        if (in_array($name, $this->overrideMethods)) {
            $arguments[0] = $this->getKey($name, $arguments[0]);
        }

        return self::$_redis->$name(...$arguments);
    }

    public function getKey($name, $keySet)
    {
        if (is_array($keySet)) {
            $key = array_shift($keySet);
        } else {
            $key = $keySet;
        }

        if (is_array($keySet)) {
            if (substr_count($key, '*') != count($keySet)) {
                throw new Exception("Redis-error happen");
            }
            $key = preg_replace_callback('/\*/', function () use ($keySet) {
                static $i = 0;
                return $keySet[$i++];
            }, $key);
        }

        return $key;
    }
}