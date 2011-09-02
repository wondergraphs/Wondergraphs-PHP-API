#!/usr/bin/env php
<?php

require dirname(__FILE__).'/_global.php';

echo "Report name: ";
$name = trim(fgets(STDIN));

echo "Dataset ID: ";
$dataset = trim(fgets(STDIN));

echo "Owner ID: ";
$owner = trim(fgets(STDIN));

$report = $api->createReport($name, $dataset, $owner);
print_r($report);
