<?php

if (!file_exists(dirname(__FILE__) . '/_config.php')) {
    echo "You need to copy _config.php.dist to _config.php (in the examples folder)\n";
    echo "and change the values to match your API credentials before you can use\n";
    echo "the examples.\n";
    exit;
}

require dirname(__FILE__) . '/_config.php';

if (strlen(trim(WG_EXAMPLES_ORGANIZATION)) == 0 || strlen(trim(WG_EXAMPLES_APIKEY)) == 0) {
    echo "You need to change the values in _config.php to match your API credentials\n";
    echo "before you can use the examples.\n";
    exit;
}

require dirname(__FILE__).'/../lib/WG/API.php';

$api = new WG\API(WG_EXAMPLES_ORGANIZATION, WG_EXAMPLES_APIKEY);

if (defined('WG_EXAMPLES_URLROOT')) {
    $api->setUrlRoot(WG_EXAMPLES_URLROOT);
}
