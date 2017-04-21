<?php
namespace Tests;

use RJ\Greeter;
use RJ\Repository\GreetDataInterface;

class GreeterTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    private $dummyGreeterInterface;

    protected function _before()
    {
        $this->dummyGreeterInterface = new class implements GreetDataInterface
        {
            private $greetings = [
                'perro' => 3
            ];

            public function getNumberOfGreetings(string $name) : int
            {
                return $this->greetings[$name] ?? 0;
            }

            public function setNumberOfGreeting(
                string $name,
                int $numberOfGreeting
            ) : void
            {
                $this->greetings[$name] = $numberOfGreeting;
            }

            public function incrementNumberOfGreetings(string $name) : void
            {
                $actualNumberOfGreetings = $this->greetings[$name] ?? 0;
                $this->setNumberOfGreeting($name, $actualNumberOfGreetings + 1);
            }
        };
    }

    protected function _after()
    {
    }

    public function testGreetStandardToAlreadyGreetedName()
    {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $name = 'perro';

        $this->assertEquals('Hello ' . $name, $greeter->greet($name));
        $this->assertEquals(4, $greeter->countGreetings($name));
    }

    public function testGreetMultipleCalls()
    {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $firstName = 'ramon';
        $secondName = 'otro';

        $this->assertEquals('Hello ' . $firstName, $greeter->greet($firstName));
        $this->assertEquals(1, $greeter->countGreetings($firstName));

        $this->assertEquals('Hello ' . $secondName,
            $greeter->greet($secondName));
        $this->assertEquals(1, $greeter->countGreetings($secondName));

        $this->assertEquals('Hello ' . $firstName, $greeter->greet($firstName));
        $this->assertEquals(2, $greeter->countGreetings($firstName));
    }

    /**
     * @example ["ramon", 200]
     */
    public function testGreetNameTransliteration()
    {
        $greeter = new Greeter($this->dummyGreeterInterface);



    }

    public function testGreetNameNotString()
    {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $name = ['a', 'b'];

        $this->expectException(\TypeError::class);
        $greeter->greet($name);
    }

    public function testCountGreetingsNotExistingName()
    {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $name = 'not';
        $this->assertEquals(0, $greeter->countGreetings($name));
    }

    public function testCountGreetingsExistingName()
    {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $name = 'perro';
        $this->assertEquals(3, $greeter->countGreetings($name));
    }

    public function testCountGreetingsNameNotString()
    {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $name = ['perro', 'dot'];

        $this->expectException(\TypeError::class);
        $greeter->countGreetings($name);
    }
}