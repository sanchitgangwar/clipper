#! /bin/bash

start=$2
end=$3
length=$((end - start))

start=$(date -u -d @${start} +"%T")
length=$(date -u -d @${length} +"%T")

filename=../upload/$1
outputfilename=../upload/tmp/$1/$4

echo $start
echo $length

if [ ! -d "../upload/tmp/$1" ]; then
echo 	mkdir ../upload/tmp/$1
fi
echo ffmpeg -acodec copy -vcodec copy --ss $start -t $length -i $filename $outputfilename
