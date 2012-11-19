Tagcache
==============

[![Build Status](https://api.travis-ci.org/RickySu/Tagcache.png)](https://api.travis-ci.org/RickySu/Tagcache.png])

Introduction
------------

This is a cache storage engine.

Features
------------

* Stores a cache with multiple tags. And deletes cache by using tag.

Requirements
------------

* PHP 5.3 above

Installation
------------

editing the composer.json file in the root project.

### Editing the composer.json under require: {} section add

```
"rickysu/tagcache": "0.1.*",
```

### Update Composer :

```
php composer.phar update
```

#### driver

The cache driver. Currently support "Memcache,Memcached,File,Sqlite,Apc,Nullcache". Nullcache is for testing only.

#### hashkey

some driver like Memcached,only support 250 characters key length. Enable this option will use md5 hashed key.

#### enable_largeobject

Memcache cannot store object over 1MB.Enable these option will fix this issue,but cause lower performance.default false.

#### servers

Memcache server configs. format => "Host:Port:Weight"


How to Use
----------

### Using Tagcache for storing data.

```php
<?php
use RickySu\Tagcache\TagcacheFactory;

$Tagcache=TagcacheFactory::factory(array(
    'driver'   => 'Memcache',
    'namespace' => 'Name_Space_For_Your_Project',
    'options'   => array(
        'hashkey'  => true,
        'enable_largeobject'  =>    false,
        'cache_dir'  =>  'Temp_Cache_File_Store_Path',
        'servers'    => array(
            'localhost:11211:10',
            'otherhost:11211:20',
        ),
    ),    
));

//store cache with Tags:{TagA,TagB} for 300 secs.
$Tagcache->set('Key_For_Store','Data_For_Store',array('TagA','TagB'),300);

//get cache.
$Tagcache->get('Key_For_Store');

//delete cache.
$Tagcache->delete('Key_For_Store');

//delete cache by Tag.
$Tagcache->deleteTag('TagA');

//acquire a lock.If a lock already exists,It will be blocked for 5 secs.
$Tagcache->getLock('Your_Lock_Name',5);

//release a lock.
$Tagcache->releaseLock('Your_Lock_Name');

//increment a cache
$Tagcache->inc('Key_For_increment');

//decrement a cache
$Tagcache->dec('Key_For_decrement');

TODO
----


LICENSE
-------

MIT