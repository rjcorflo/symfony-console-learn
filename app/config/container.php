<?php

use function DI\get;
use function DI\object;
use function DI\string;
use Psr\Container\ContainerInterface;
use RJ\Command\GreetCommand;
use RJ\Command\RandomCommand;
use RJ\Repository\GreetDataInterface;
use RJ\Repository\Implementation\GreetDataFile;

return [
    // Parameters
    'path.resources'    => __DIR__.'/../../resources/',
    'greeting.filename' => string('{path.resources}/greetings.yml'),

    // Bind interface to implementation
    GreetDataInterface::class => object(GreetDataFile::class)->constructor(get('greeting.filename')),

    // App commands
    'commands' => [
        get(GreetCommand::class),
        get(RandomCommand::class),
    ],

    // App construction
    'app' => function (ContainerInterface $c) {
        $application = new \Symfony\Component\Console\Application();

        $application->addCommands($c->get('commands'));

        return $application;
    },
];
