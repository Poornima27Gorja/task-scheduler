#!/bin/bash

# Get PHP path (like: /usr/bin/php)
PHP_PATH=$(which php)

# Path to your cron.php file (it grabs the current folder path + cron.php)
CRON_FILE="$(pwd)/cron.php"

# What CRON should run
CRON_CMD="$PHP_PATH $CRON_FILE > /dev/null 2>&1"

# Add this command to crontab (only if not already added)
( crontab -l | grep -v -F "$CRON_FILE" ; echo "0 * * * * $CRON_CMD" ) | crontab -
