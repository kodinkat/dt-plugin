#!/bin/bash

set -e

cd "$(dirname "${BASH_SOURCE[0]}")/../"

 vendor/bin/phpcs dt-plugin.php
