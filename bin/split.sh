#!/usr/bin/env bash

set -e
set -x

CURRENT_BRANCH="master"

function split()
{
    SHA1=`./bin/splitsh-lite --prefix=$1`
    git push $2 "$SHA1:refs/heads/$CURRENT_BRANCH" -f
}

function remote()
{
    git remote add $1 $2 || true
}

git pull origin $CURRENT_BRANCH

remote anvil git@github.com:gildsmith/anvil.git
remote contract git@github.com:gildsmith/contract.git
remote product git@github.com:gildsmith/product.git
remote skeleton git@github.com:gildsmith/skeleton.git
remote support git@github.com:gildsmith/support.git

split 'packages/gildsmith/anvil' anvil
split 'packages/gildsmith/contract' contract
split 'packages/gildsmith/product' product
split 'packages/gildsmith/skeleton' skeleton
split 'packages/gildsmith/support' support
