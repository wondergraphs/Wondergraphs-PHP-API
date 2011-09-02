<?php

require dirname(__FILE__).'/_global.php';

echo "Please enter the ID of the dataset for which you wish to retrieve the import status: ";
$id = trim(fgets(STDIN));

print_r($api->getImportStatus($id));
