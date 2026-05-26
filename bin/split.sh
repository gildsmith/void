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

# packages
remote anvil git@github.com:gildsmith/anvil.git
remote auth git@github.com:gildsmith/auth.git
remote contract git@github.com:gildsmith/contract.git
remote product git@github.com:gildsmith/product.git
remote skeleton git@github.com:gildsmith/skeleton.git
remote support git@github.com:gildsmith/support.git
remote testing git@github.com:gildsmith/testing.git

split 'packages/gildsmith/anvil' anvil
split 'packages/gildsmith/auth' auth
split 'packages/gildsmith/contract' contract
split 'packages/gildsmith/product' product
split 'packages/gildsmith/skeleton' skeleton
split 'packages/gildsmith/support' support
split 'packages/gildsmith/testing' testing

# docs
remote docs-developer git@github.com:gildsmith/docs-developer.git
remote docs-merchant git@github.com:gildsmith/docs-merchant.git

split 'docs/developer' docs-developer
split 'docs/merchant' docs-merchant
