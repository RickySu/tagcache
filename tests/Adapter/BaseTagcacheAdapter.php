<?php

namespace RickySu\Tagcache\tests\Adapter;

use RickySu\Tagcache\Adapter\TagcacheAdapter;

abstract class BaseTagcacheAdapter extends \PHPUnit_Framework_TestCase
{
    protected $Cache;
    protected $StaticsetTestData = null;
    protected $cache_dir=null;
    protected function setUp()
    {
        $this->cache_dir=__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'temp';
        if (!is_dir($this->cache_dir)) {
            mkdir($this->cache_dir);
        }
        $this->setupCache();
    }

    /**
     * @return TagcacheAdapter;
     */
    abstract protected function setupCache();

    public function DataProvider_testSet($DataRows = 5)
    {
        $Rows = array();
        for ($i = 0; $i <= $DataRows; $i++) {
            $Rows[] = array(md5(microtime() . rand()), md5(microtime() . rand()));
        }

        return $Rows;
    }

    /**
     *
     * @dataProvider DataProvider_testSet
     */
    public function testSet($Key, $Data)
    {
        $this->assertTrue($this->Cache->set($Key, $Data));
    }

    /**
     *
     * @dataProvider DataProvider_testSet
     */
    public function testGet($Key, $Data)
    {
        $this->Cache->set($Key, $Data);
        $this->assertEquals($Data, $this->Cache->get($Key));
    }

    /**
     *
     * @dataProvider DataProvider_testSet
     */
    public function testDelete($Key, $Data)
    {
        $this->Cache->set($Key, $Data);
        $this->Cache->delete($Key);
        $this->assertFalse($this->Cache->get($Key));
    }

    /**
     *
     * @dataProvider DataProvider_testSet
     */
    public function testClear($Key, $Data)
    {
        $this->Cache->set($Key, $Data);
        $this->Cache->clear();
        $this->assertFalse($this->Cache->get($Key));
    }

    public function testInc()
    {
        $Key="test:forInc";
        $this->Cache->delete($Key);
        for ($i=1;$i<=3;$i++) {
            $this->assertTrue($this->Cache->Inc($Key),"Inc pass $i");
        }
        $this->assertEquals(3,$this->Cache->get($Key),"Inc $Key");
    }

    /**
     *
     * @dataProvider DataProvider_testSet
     */
    public function testdeleteTag()
    {
        $Data = $this->DataProvider_testSet(20);
        foreach ($Data as $Index => $Row) {
            list($Key, $Val) = $Row;
            $this->Cache->set($Key, $Val, array("Tag:" . ($Index % 4)));
        }
        $this->Cache->deleteTag("Tag:1");
        $this->Cache->deleteTag("Tag:3");
        foreach ($Data as $Index => $Row) {
            list($Key, $Val) = $Row;
            if (in_array($Index % 4, array(1, 3)) !== false) {
                $this->assertFalse($this->Cache->get($Key), "$Index::$Key");
            } else {
                $this->assertEquals($Val, $this->Cache->get($Key), "$Index::$Key");
            }
        }
    }

    /**
     *
     * @dataProvider DataProvider_testSet
     */
    public function testgetTags()
    {
        $Tags = array();
        for ($i = 0; $i < 5; $i++) {
            $Tags[] = md5(microtime() . rand());
        }
        $Key = md5(microtime() . rand());
        $this->Cache->set($Key, 'Test', $Tags);
        $ActualTags = array_intersect($Tags, $this->Cache->getTags($Key));
        $this->assertEquals($Tags, $ActualTags);
    }

    public function testgetLock()
    {
        $this->Cache->getLock('TestLock',3);
        $StartTime=microtime(true);
        $this->Cache->getLock('TestLock',3);
        $EndTime=microtime(true);
        $this->assertTrue($EndTime-$StartTime>2);
        $this->Cache->releaseLock('TestLock');
    }

    public function testreleaseLock()
    {
        $this->Cache->getLock('TestLock',5);
        $StartTime=microtime(true);
        $this->Cache->releaseLock('TestLock');
        $this->Cache->getLock('TestLock',5);
        $EndTime=microtime(true);
        $this->assertTrue($EndTime-$StartTime<1);
        $this->Cache->releaseLock('TestLock');
    }

    protected function tearDown()
    {
        if (!$this->Cache) {
            return;
        }
        $this->Cache->clear();
        unset($this->Cache);
        $this->emptyCacheDir($this->cache_dir);
    }

    protected function emptyCacheDir($dir)
    {
        $iterator=new \DirectoryIterator($dir);
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            $FullPath=realpath($dir.DIRECTORY_SEPARATOR.$fileInfo->getFilename());
            if ($fileInfo->isFile() || $fileInfo->isLink()) {
                unlink($FullPath);
            }
            if ($fileInfo->isDir()) {
                $this->emptyCacheDir($FullPath);
                rmdir($FullPath);
            }
        }
    }
}
