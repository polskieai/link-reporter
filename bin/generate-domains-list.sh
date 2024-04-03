#!/bin/sh

if [ "$2" != "" ] && [ -d "$1" ]; then
	ls -1 $1/*/ |grep -v : |grep -v ^$ |egrep -v '([0-9]{1,3}\.){3}[0-9]{1,3}' |cut -d'&' -f1 >$2.new
	if [ -s $2.new ]; then
		mv $2 $2.prev
		mv $2.new $2
	fi
fi
