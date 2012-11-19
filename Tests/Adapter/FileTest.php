<?php

namespace RickySu\Tagcache\Tests\Adapter;

use RickySu\Tagcache\Tests\Adapter\BaseTagcacheAdapter;
use RickySu\Tagcache\Adapter\File;

class FileTest extends BaseTagcacheAdapter
{
    protected function setupCache()
    {
        $this->Cache = new File(md5(microtime() . rand()), array(
                    'hashkey' => true,
                    'cache_dir' => $this->cache_dir,
                ));
    }

}
