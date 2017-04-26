<?php

namespace RJ\Service;

use RJ\Repository\GreetDataInterface;

class Greeter
{
    /**
     * @var \RJ\Repository\GreetDataInterface Repository to access greeter data
     */
    protected $greeterRepository;

    /**
     * Greeter constructor.
     *
     * @param \RJ\Repository\GreetDataInterface $greeterRepository
     */
    public function __construct(GreetDataInterface $greeterRepository)
    {
        $this->greeterRepository = $greeterRepository;
    }

    /**
     * Builds the greeting for someone (you can yell on it if you want!).
     *
     * @param string $name
     * @param bool   $yell wanna yell?
     *
     * @return string
     */
    public function greet(string $name, bool $yell = false)
    {
        $output = sprintf('Hello %s', $name);

        if ($yell) {
            $output = strtoupper($output);
        }

        $this->greeterRepository->incrementNumberOfGreetings($name);

        return $output;
    }

    /**
     * Will tell you how many times you greet someone.
     *
     * @param string $name
     *
     * @return int
     */
    public function countGreetings(string $name) : int
    {
        return $this->greeterRepository->getNumberOfGreetings($name);
    }
}
