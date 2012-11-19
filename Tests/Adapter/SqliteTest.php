<?php

namespace RickySu\Tagcache\Tests\Adapter;

use RickySu\Tagcache\Tests\Adapter\BaseTagcacheAdapter;
use RickySu\Tagcache\Adapter\Sqlite;

class SqliteTest extends BaseTagcacheAdapter
{
    protected function setupCache()
    {
        $this->Cache = new Sqlite(md5(microtime() . rand()), array(
                    'hashkey' => false,
                    'cache_dir' => $this->cache_dir,
                ));
    }

}
