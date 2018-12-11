<?php

namespace RickySu\Tagcache\tests\Adapter;

use RickySu\Tagcache\tests\Adapter\BaseTagcacheAdapter;
use RickySu\Tagcache\Adapter\Sqlite;

class SqliteTest extends BaseTagcacheAdapter
{
    protected function setUp()
    {
        if (!extension_loaded('sqlite3')) {
            $this->markTestSkipped(
                'The Sqlite extension is not available.'
            );
        }
        parent::setUp();
    }

    protected function setupCache()
    {
        $this->Cache = new Sqlite(md5(microtime() . rand()), array(
                    'hashkey' => false,
                    'cache_dir' => $this->cache_dir,
                ));
    }

}
