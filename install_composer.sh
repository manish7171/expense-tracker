#!/usr/bin/env bash
docker run --rm --interactive --tty \
	--volume $PWD/src:/app \
	--user $(id -u):$(id -g) \
	composer/composer /bin/sh
