<?php

set_time_limit(0);

require __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/config/container.php');
return $containerBuilder->build();