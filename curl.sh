#!/bin/bash

while read query
do
    content=$(curl "${query}")
    echo $query
    echo $content >> output.txt
done 
