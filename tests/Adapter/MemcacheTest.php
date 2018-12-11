<?php

namespace RickySu\Tagcache\tests\Adapter;

use RickySu\Tagcache\tests\Adapter\BaseTagcacheAdapter;
use RickySu\Tagcache\Adapter\Memcache;

class MemcacheTest extends BaseTagcacheAdapter
{

    protected function setUp()
    {
        if (!extension_loaded('memcache')) {
            $this->markTestSkipped(
                'The Memcache extension is not available.'
            );
        }
        parent::setUp();
    }

    protected function setupCache($EnableLargeObject=false)
    {
        $this->Cache = new Memcache(md5(microtime() . rand()), array(
                    'hashkey' => true,
                    'servers' => array('127.0.0.1:11211:10'),
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
