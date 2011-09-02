#!/usr/bin/env php
<?php

require dirname(__FILE__).'/_global.php';

$datasets = $api->getDatasets();

echo "Datasets in my organization:\n";
foreach ($datasets as $dataset) {
    echo "{$dataset->id}\t{$dataset->name}\n";
}
