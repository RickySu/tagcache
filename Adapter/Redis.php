<?php

namespace RickySu\Tagcache\Adapter;

class Redis extends TagcacheAdapter
{
    /**
     * @var \Redis
     */
    protected $redis;


    public function __construct($namespace, $options)
    {
        parent::__construct($namespace, $options);

        $class = isset($this->Options['redis_connection_class']) ? $this->Options['redis_connection_class'] : \Redis::class;

        $this->redis = new $class();

        if (empty($options['servers'][0])) {
            throw new \Exception('Redis TagcacheAdapter not configured properly.');
        }

        list($host, $port) = explode(":", $options['servers'][0]);

        $this->redis->connect($host, $port);
    }

    protected function getNamespace()
    {
        return $this->Namespace;
    }

    protected function setRaw($key, $obj, $expire = 0)
    {
        $key = $this->getNamespace() . ':' . $key;
        $obj = serialize($obj);

        if ($expire > 0) {
            return $this->redis->setex($key, $expire, $obj);
        }

        return $this->redis->set($key, $obj);
    }

    protected function getRaw($key)
    {
        $key = $this->getNamespace() . ':' . $key;

        $data = $this->redis->get($key);

        $data = unserialize($data);

        return $data;
    }

    protected function deleteRaw($key)
    {
        $key = $this->getNamespace() . ':' . $key;

        return $this->redis->delete($key) > 0;
    }
}