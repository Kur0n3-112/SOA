#!/bin/bash

i=0

while [ $i -le 1000 ]
do
     curl 'http://localhost:8000/api/v1/sum?a=3&b=34'&& echo "[$i]"
     curl 'http://localhost:8000/api/v1/sum?a=3&b=52'&& echo "[$i]"
     curl 'http://localhost:8000/api/v1/sum?a=3&b=98'&& echo "[$i]"
     curl 'http://localhost:8000/api/v1/sum?a=3&b=48'&& echo "[$i]"
  ((i++))
done
