<?php

namespace RickySu\Tagcache\tests\Adapter;

use RickySu\Tagcache\tests\Adapter\BaseTagcacheAdapter;
use RickySu\Tagcache\Adapter\Apc;

class ApcTest extends BaseTagcacheAdapter
{
    protected function setUp()
    {
        if (!extension_loaded('apc')) {
            $this->markTestSkipped(
                'The APC extension is not available.'
            );
        }

        parent::setUp();
    }

    protected function setupCache($EnableLargeObject=false)
    {
        $this->Cache = new Apc(md5(microtime() . rand()), array(
                    'cache_dir' => $this->cache_dir,
                    'hashkey' => true,
                    'enable_largeobject'=>$EnableLargeObject,
                ));
    }

    public function testBigObject()
    {
        $this->setupCache(true);
        $BigString = '';
        for ($i = 0; $i < 1024 * 1024 * 4; $i++) {
            $BigString .= rand(0,9);
        }
        $Hash=md5($BigString);
        $this->assertEquals(1024 * 1024 * 4, strlen($BigString));
        $this->Cache->set('TestBigString',$BigString);
        $this->assertEquals($Hash,md5($this->Cache->get('TestBigString')));
        $this->setupCache(false);
    }

}
