#!/usr/bin/env php
<?php
/**
 * @var \Psr\Container\ContainerInterface
 */
$container = require __DIR__.'/bootstrap.php';

$container->get('app')->run();
