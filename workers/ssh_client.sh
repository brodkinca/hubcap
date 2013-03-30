#!/bin/sh

BASE_PATH=$(dirname $0)

ssh -i $BASE_PATH/../repo_temp/rsa.key $1 $2