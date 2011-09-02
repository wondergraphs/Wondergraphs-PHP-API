#!/usr/bin/env php
<?php

require dirname(__FILE__).'/_global.php';

echo "Dataset name: ";
$name = trim(fgets(STDIN));

echo "Owner ID: ";
$owner = trim(fgets(STDIN));

echo "Filename: ";
$file = trim(fgets(STDIN));

$dataset = $api->createDataset($name, $owner, $file);
print_r($dataset);
