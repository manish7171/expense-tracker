#!/usr/bin/env bash
docker run --rm --interactive --tty \
	--workdir /app \
	--volume $PWD/src:/app \
	--user $(id -u):$(id -g) \
	node:latest /bin/sh
