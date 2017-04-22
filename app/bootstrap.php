<?php
/**
 * Bootstrap file.
 *
 * Require composer autolader.
 * Create PSR-11 compliant container and return it.
 */
set_time_limit(0);

require __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/config/container.php');

/**
 * @var \Psr\Container\ContainerInterface $container
 */
$container = $containerBuilder->build();
return $container;