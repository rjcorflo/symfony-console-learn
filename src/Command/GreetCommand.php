<?php
namespace RJ\Command;

use RJ\Service\Greeter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GreetCommand extends Command
{
    /**
     * @var Greeter $greeter
     */
    protected $greeter;

    /**
     * Constructor
     *
     * @param Greeter $greeter
     */
    public function __construct(Greeter $greeter)
    {
        parent::__construct();
        $this->greeter = $greeter;
    }

    protected function configure()
    {
        $this->setName('greet')
            ->setDescription('Greet to someone')
            ->setHelp('Launch a greet to introduced name.')
            ->addArgument('name', InputArgument::REQUIRED,
                'Name of person to be greeted')
            ->addOption('yell', 'y', InputOption::VALUE_NONE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $name = $input->getArgument('name');
        $yell = $input->getOption('yell');

        $greetNameString = $this->greeter->greet($name, $yell);

        $io->text($greetNameString);

        $numberOfTimesGreeted = $this->greeter->countGreetings($name);
        if (1 === $numberOfTimesGreeted) {
            $io->text('(First time!)');
        } else {
            $io->text(sprintf('(%d times)', $numberOfTimesGreeted));
        }
    }
}
