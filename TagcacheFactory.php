<?php

namespace RickySu\Tagcache;

class TagcacheFactory
{
    protected static $Instance = null;

    protected function __construct()
    {
    }

    public static function factory($Config)
    {
        $Driver='RickySu\\Tagcache\\Adapter\\'.$Config['driver'];
        self::$Instance=new $Driver($Config['namespace'],$Config['options'], $Config['debug']);

        return self::$Instance;
    }

}
