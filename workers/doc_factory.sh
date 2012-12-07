#!/bin/sh

if [ $# != 4 ]
    then
    echo This script requires exactly four arguments.
    exit
fi

USER=$1
REPO=$2
COMMIT=$3
TEMP_PATH=$4

echo Retrieving $COMMIT from $USER/$REPO...

git clone -n git@github.com:$USER/$REPO.git $TEMP_PATH
cd $TEMP_PATH
git checkout -b gh-pages
git pull origin gh-pages
git push --all origin
git rm -r *
git checkout $COMMIT -- docs
git mv ./docs/* .
git rm -r ./docs
git add -u .
git commit -m "Github pages update via Hubcap http://hubcap.it/"
git push origin gh-pages
cd ~
rm -rf $TEMP_PATH
