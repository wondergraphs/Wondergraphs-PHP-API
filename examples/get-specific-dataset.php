#!/usr/bin/env php
<?php

require dirname(__FILE__).'/_global.php';

echo "Please enter the ID of the dataset you wish to retrieve: ";
$id = trim(fgets(STDIN));

print_r($api->getDataset($id));
