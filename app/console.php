#!/usr/bin/env php
<?php
/**
 * @var \Psr\Container\ContainerInterface $container
 */
$container = require __DIR__ . '/bootstrap.php';

$application = $container->get('app');
$application->run();