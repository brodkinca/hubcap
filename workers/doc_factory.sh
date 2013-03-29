#!/bin/sh

echo Shell script taking the reigns\!

if [ $# != 8 ]
    then
    echo This script requires exactly eight arguments.
    exit
fi

# Runtime Parameters
USER=$1
REPO=$2
COMMIT=$3
TEMP_PATH=$4
SOURCE_PATH=$5
DEST_BRANCH=$6
DEST_PATH=$7
KEY_PATH=$8

# System Configuration
chmod 0600 $KEY_PATH
ssh-add $KEY_PATH

echo Retrieving $COMMIT from $USER/$REPO...

git clone -n git@github.com:$USER/$REPO.git $TEMP_PATH
cd $TEMP_PATH
git checkout -v -b $DEST_BRANCH
git pull -v origin $DEST_BRANCH
git push -v --all origin
git rm -v -r *
git checkout -v $COMMIT -- ./$SOURCE_PATH
git mv -v ./$SOURCE_PATH/* ./$DEST_PATH
git rm -v -r ./$SOURCE_PATH
git add -v -u .
git commit -v -m "Github pages update via Hubcap http://hubcap.it/"
git push -v origin $DEST_BRANCH
cd ~
rm -rfv $TEMP_PATH
ssh-add -D
