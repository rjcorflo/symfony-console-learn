<?php

namespace RJ\Repository;

/**
 * Greeting repository interface.
 *
 * Provide three methods that control how to retrieve, set and increment the number of greeting for a given name.
 */
interface GreetDataInterface
{
    /**
     * Get number of times a person was greeted.
     *
     * @param string $name Name of person greeted.
     *
     * @return int Number of times this name was greeted.
     */
    public function getNumberOfGreetings(string $name) : int;

    /**
     * Set the number of times a person was greeted.
     *
     * @param string $name             Name of person greeted.
     * @param int    $numberOfGreeting Number of times greeted.
     */
    public function setNumberOfGreeting(string $name, int $numberOfGreeting) : void;

    /**
     * Increment in 1 the number of times a person was greeted.
     *
     * @param string $name Name of person.
     */
    public function incrementNumberOfGreetings(string $name) : void;
}
