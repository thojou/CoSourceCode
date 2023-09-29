<?php

$ILIAS_ROOT = realpath(__DIR__ . '/../../../../../../../../');

if(file_exists($ILIAS_ROOT . '/libs/composer/vendor/autoload')) {
    require_once $ILIAS_ROOT . '/libs/composer/vendor/autoload.php';
}

require_once __DIR__ . '/../vendor/autoload.php';