#!/usr/bin/env bash

./build.sh
cd public/
php -S 0.0.0.0:8080
