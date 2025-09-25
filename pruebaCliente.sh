#!/bin/bash

i=0 

while [ $i -le 1000 ]
do
    ./client RES 12 2
    ./client SUM 12 2
    ./client DIV 12 2
    ./client MUL 12 2
  ((i++))
done
