#!/bin/sh

if [ "$2" != "" ] && [ -d "$1" ]; then
	find $1/ -mindepth 2 -type d |sed "s#$1/##g" |cut -d'/' -f2- |egrep -v '([0-9]{1,3}\.){3}[0-9]{1,3}' |cut -d'&' -f1 >$2.new
	if [ -s $2.new ]; then
		mv $2 $2.prev
		mv $2.new $2
	fi
fi
