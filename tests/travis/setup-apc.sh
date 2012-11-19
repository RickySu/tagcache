#!/bin/bash
echo "" | pecl install apc-beta
echo "extension=apc.so" >> `php --ini |grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
echo "apc.enabled=1" >> `php --ini |grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
echo "apc.enable_cli=1" >> `php --ini |grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
sudo ldconfig -v
