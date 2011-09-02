#!/usr/bin/env php
<?php

require dirname(__FILE__).'/_global.php';

$user = $api->createUser('alice@example.com', 'Alice', 'Doe', 'alice123', 'viewer');
print_r($user);
