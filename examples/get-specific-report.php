#!/usr/bin/env php
<?php

require dirname(__FILE__).'/_global.php';

echo "Please enter the ID of the report you wish to retrieve: ";
$id = trim(fgets(STDIN));

print_r($api->getReport($id));
