<?php

require dirname(__FILE__).'/_global.php';

$users = $api->getUsers();

echo "Users in my organization:\n";
foreach ($users as $user) {
    echo "\t{$user->firstname} {$user->lastname} ({$user->type})\n";
}
