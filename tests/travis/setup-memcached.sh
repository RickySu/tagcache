#!/bin/bash
sudo apt-get install libcloog-ppl0
#wget -c 'http://launchpad.net/libmemcached/1.0/1.0.14/+download/libmemcached-1.0.14.tar.gz'
#tar -xvf libmemcached-1.0.14.tar.gz
#sh -c "cd libmemcached-1.0.14 && ./configure --disable-sasl && make all && sudo make install && sudo ldconfig"
sudo apt-get install -y libmemcached-dev
echo 'y' | pecl install memcached
echo "extension=memcached.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
sudo ldconfig
