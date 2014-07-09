#!/bin/bash

while read query
do
    	echo $query
	curl "${query}" > /dev/null
done 
