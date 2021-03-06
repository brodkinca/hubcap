#!/bin/sh

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
git checkout -b $DEST_BRANCH
git pull origin $DEST_BRANCH
git push --all origin
git rm -r *
git checkout $COMMIT -- ./$SOURCE_PATH
git mv ./$SOURCE_PATH/* ./$DEST_PATH
git rm -r ./$SOURCE_PATH
git add -u .
git commit -m "Github pages update via Hubcap http://hubcap.it/"
git push origin $DEST_BRANCH
cd ~
rm -rf $TEMP_PATH
ssh-add -D
