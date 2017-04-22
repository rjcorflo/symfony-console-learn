<?php
use Psr\Container\ContainerInterface;
use RJ\Command\GreetCommand;
use RJ\Repository\GreetDataInterface;
use RJ\Repository\Implementation\GreetDataFile;
use function DI\get;
use function DI\object;

return [
    // Parameters
    'greeting.filename' => 'greetings.yml',

    // Bind interface to implementation
    GreetDataInterface::class => object(GreetDataFile::class)
        ->constructor(get('greeting.filename')),

    'commands' => [
        get(GreetCommand::class)
    ],

    'app' => function (ContainerInterface $c) {
        $application = new \Symfony\Component\Console\Application();

        $application->addCommands($c->get('commands'));

        return $application;
    }
];