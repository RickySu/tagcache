#!/bin/bash
pecl install apc
echo "extension=apc.so" >> `php --ini |grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
echo "apc.enabled=1" >> `php --ini |grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
echo "apc.enable_cli=1" >> `php --ini |grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
