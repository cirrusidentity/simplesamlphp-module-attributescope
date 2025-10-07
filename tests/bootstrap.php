<?php

$projectRoot = dirname(__DIR__);
require_once($projectRoot . '/vendor/autoload.php');
putenv('SIMPLESAMLPHP_CONFIG_DIR=' . __DIR__ . '/config');
