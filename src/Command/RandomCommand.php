<?php

namespace RJ\Command;

use RJ\Service\Greeter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RandomCommand extends Command
{
    /**
     * @var Greeter
     */
    protected $greeter;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('random')
            ->setDescription('Return a random number between the numbers passed')
            ->setHelp('It returns a number between a range.')
            ->addArgument('initial-number', InputArgument::REQUIRED,
                'First number')
            ->addArgument('end-number', InputArgument::REQUIRED,
                'Second number');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $initialNumber = (int) $input->getArgument('initial-number');
        $endNumber = (int) $input->getArgument('end-number');

        $number = rand($initialNumber, $endNumber);

        $io->text("The random number created: $number");
    }
}
