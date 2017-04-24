<?php
namespace Service;

use Codeception\Example;
use Codeception\Util\Stub;
use RJ\Repository\Implementation\GreetDataFile;
use RJ\Service\Greeter;

class GreeterTestCest
{
    private $dummyGreeterInterface;

    public function _before(\UnitTester $I)
    {
        $this->dummyGreeterInterface = Stub::make(GreetDataFile::class, [
            'greetings' => [
                'perro' => 3
            ],
            '__destruct' => function () {
            }
        ]);
    }

    public function _after(\UnitTester $I)
    {
    }

    /**
     * @example {"name":"ramon", "count":1}
     * @example {"name":"other", "count":1}
     */
    public function testGreetStandardOk(
        \UnitTester $I,
        \Codeception\Example $example
    ) {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $I->wantTo('Check standard functionality.');

        $I->amGoingTo('greet to ' . $example['name']);
        $I->expectTo('see Hello ' . $example['name']);
        $I->assertEquals('Hello ' . $example['name'],
            $greeter->greet($example['name']));

        $I->amGoingTo('see number of times ' . $example['name'] . ' has been greeted');
        $I->expectTo('see the numeber of times is 1');
        $I->assertEquals($example['count'],
            $greeter->countGreetings($example['name']));
    }

    public function testGreetNameNotString(\UnitTester $I)
    {
        $I->expectError(\TypeError::class, function () {
            $greeter = new Greeter($this->dummyGreeterInterface);

            $name = ['a', 'b'];

            $greeter->greet($name);
        });
    }

    public function testGreetYell(\UnitTester $I)
    {
        $greeter = new Greeter($this->dummyGreeterInterface);
        $name = 'Ramon';
        $nameUppercase = 'RAMON';

        $I->amGoingTo('greet someone yelling');
        $I->expectTo('see greeting in uppercase letter');
        $I->assertEquals('HELLO ' . $nameUppercase,
            $greeter->greet($name, true));
    }

    public function testGreetStandardToAlreadyGreetedName(\UnitTester $I)
    {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $name = 'perro';

        $I->assertEquals('Hello ' . $name, $greeter->greet($name));
        $I->assertEquals(4, $greeter->countGreetings($name));
    }

    public function testGreetMultipleCalls(\UnitTester $I)
    {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $firstName = 'ramon';
        $secondName = 'otro';

        $I->assertEquals('Hello ' . $firstName,
            $greeter->greet($firstName));
        $I->assertEquals(1, $greeter->countGreetings($firstName));

        $I->assertEquals('Hello ' . $secondName,
            $greeter->greet($secondName));
        $I->assertEquals(1, $greeter->countGreetings($secondName));

        $I->assertEquals('Hello ' . $firstName,
            $greeter->greet($firstName));
        $I->assertEquals(2, $greeter->countGreetings($firstName));
    }

    /**
     * @example {"name1":"Ramon", "name2":"raMoN", "name3":"Ramón"}
     */
    public function testGreetNameTransliteration(
        \UnitTester $I,
        Example $example
    ) {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $greeter->greet($example['name1']);
        $I->assertEquals(1, $greeter->countGreetings($example['name1']));

        $greeter->greet($example['name2']);
        $I->assertEquals(2, $greeter->countGreetings($example['name2']));

        $greeter->greet($example['name3']);
        $I->assertEquals(3, $greeter->countGreetings($example['name3']));
    }

    public function testCountGreetingsNotExistingName(\UnitTester $I)
    {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $name = 'not';
        $I->assertEquals(0, $greeter->countGreetings($name));
    }

    public function testCountGreetingsExistingName(\UnitTester $I)
    {
        $greeter = new Greeter($this->dummyGreeterInterface);

        $name = 'perro';
        $I->assertEquals(3, $greeter->countGreetings($name));
    }
}
