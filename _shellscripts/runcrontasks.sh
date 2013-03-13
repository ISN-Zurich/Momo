#!/bin/bash

if [ "$1" == "hourly" ]; then
	php ../index.php CronController runhourlytasks
fi

if [ "$1" == "daily" ]; then
	php ../index.php CronController rundailytasks
fi

if [ "$1" == "weekly" ]; then
	php ../index.php CronController runweeklytasks
fi

if [ "$1" == "monthly" ]; then
	php ../index.php CronController runmonthlytasks
fi
