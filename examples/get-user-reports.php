#!/usr/bin/env php
<?php

require dirname(__FILE__).'/_global.php';

echo "Please enter the ID of the user whose reports you wish to retrieve: ";
$id = trim(fgets(STDIN));

print_r($api->getReportsForUser($id));
