#!/usr/bin/env php
<?php

require dirname(__FILE__).'/_global.php';

echo "Please enter the ID of the report you wish to change: ";
$id = trim(fgets(STDIN));

echo "New name: ";
$name = trim(fgets(STDIN));

print_r($api->updateReportName($id, $name));
