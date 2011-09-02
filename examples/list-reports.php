<?php

require dirname(__FILE__).'/_global.php';

$reports = $api->getReports();

echo "Reports in my organization:\n";
foreach ($reports as $report) {
    echo "{$report->id}\t{$report->name}\n";
}
