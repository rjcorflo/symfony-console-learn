<?php
namespace Tests;

use Codeception\Example;
use RJ\Greeter;
use RJ\Repository\GreetDataInterface;

class GreeterTestCest
{
    private $dummyGreeterInterface;

    public function _before(\UnitTester $tester)
    {
        $this->dummyGreeterInterface = new class implements GreetDataInterface
        {
            private $greetings = [
                'perro' => 3
            ];

            /**
             * @inheritdoc
             */
            public function getNumberOfGreetings(string $name) : int
            {
                $cleanName = $this->sanitizeAndLowercaseName($name);

                return $this->greetings[$cleanName] ?? 0;
            }

            /**
             * @inheritdoc
             */
            public function setNumberOfGreeting(
                string $name,
                int $numberOfGreeting
            ) : void
            {
                $cleanName = $this->sanitizeAndLowercaseName($name);
                $this->greetings[$cleanName] = $numberOfGreeting;
            }

            /**
             * @inheritdoc
             */
            public function incrementNumberOfGreetings(string $name) : void
            {
                $cleanName = $this->sanitizeAndLowercaseName($name);
                $actualNumberOfGreetings = $this->greetings[$cleanName] ?? 0;
                $this->setNumberOfGreeting($name, $actualNumberOfGreetings + 1);
            }

            protected function sanitizeAndLowercaseName(string $name) : string
            {
                $cleanName = strtolower(\URLify::transliterate($name));

                return $cleanName;
            }
        };
    }

    public function _after(\UnitTester $tester)
    {
    }

    /**
     * @example {"name":"ramon", "count":1}
     * @example {"name":"other", "count":1}
     */
    public function testGreetStandardOk(
        \UnitTester $tester,
        \Codeception\Example $example
    ) {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $tester->wantTo('Check standard functionality.');

        $tester->amGoingTo('greet to ' . $example['name']);
        $tester->expectTo('see Hello ' . $example['name']);
        $tester->assertEquals('Hello ' . $example['name'],
            $greeter->greet($example['name']));

        $tester->amGoingTo('see number of times ' . $example['name'] . ' has been greeted');
        $tester->expectTo('see the numeber of times is 1');
        $tester->assertEquals($example['count'],
            $greeter->countGreetings($example['name']));
    }

    public function testGreetNameNotString(\UnitTester $tester)
    {
        $tester->expectError(\TypeError::class, function () {
            $greeter = new Greeter($this->dummyGreeterInterface);

            $name = ['a', 'b'];

            $greeter->greet($name);
        });
    }

    public function testGreetStandardToAlreadyGreetedName(\UnitTester $tester)
    {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $name = 'perro';

        $tester->assertEquals('Hello ' . $name, $greeter->greet($name));
        $tester->assertEquals(4, $greeter->countGreetings($name));
    }

    public function testGreetMultipleCalls(\UnitTester $tester)
    {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $firstName = 'ramon';
        $secondName = 'otro';

        $tester->assertEquals('Hello ' . $firstName,
            $greeter->greet($firstName));
        $tester->assertEquals(1, $greeter->countGreetings($firstName));

        $tester->assertEquals('Hello ' . $secondName,
            $greeter->greet($secondName));
        $tester->assertEquals(1, $greeter->countGreetings($secondName));

        $tester->assertEquals('Hello ' . $firstName,
            $greeter->greet($firstName));
        $tester->assertEquals(2, $greeter->countGreetings($firstName));
    }

    /**
     * @example {"name1":"Ramon", "name2":"raMoN", "name3":"Ramón"}
     */
    public function testGreetNameTransliteration(
        \UnitTester $tester,
        Example $example
    ) {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $greeter->greet($example['name1']);
        $tester->assertEquals(1, $greeter->countGreetings($example['name1']));

        $greeter->greet($example['name2']);
        $tester->assertEquals(2, $greeter->countGreetings($example['name2']));

        $greeter->greet($example['name3']);
        $tester->assertEquals(3, $greeter->countGreetings($example['name3']));
    }

    public function testCountGreetingsNotExistingName(\UnitTester $tester)
    {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $name = 'not';
        $tester->assertEquals(0, $greeter->countGreetings($name));
    }

    public function testCountGreetingsExistingName(\UnitTester $tester)
    {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $name = 'perro';
        $tester->assertEquals(3, $greeter->countGreetings($name));
    }
}
