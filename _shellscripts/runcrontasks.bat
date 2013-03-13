@ECHO OFF
IF "%1"=="hourly" php ../index.php croncontroller runhourlytasks

IF "%1"=="daily" php ../index.php croncontroller rundailytasks

IF "%1"=="weekly" php ../index.php croncontroller runweeklytasks

IF "%1"=="monthly" php ../index.php croncontroller runmonthlytasks
