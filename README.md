[![Build Status](https://travis-ci.org/kolibri/whhato.svg?branch=master)](https://travis-ci.org/kolibri/whhato)


# whhato

## A "WHat HAppened TOday" service

Welcome to this project. It's a very simple webservice for a slack app, that has a slash command which tells you what happened today some years ago.

You can visit the project at https://whhato.vogelschwarz.de.

## How to setup

```bash
git clone git@github.com:kolibri/whhato.git
cd whhato
make install
make test
```

Use symfony webserver:

```bash
cd project
./bin/console server:start
```

## deploy

```bash
# from main directory
make tarball
make STAGE=prod provision
make STAGE=prod deploy
```

## Contributing new Messages

Edit the file `./project/data/prod/data.yaml` and create a pull request on github.
