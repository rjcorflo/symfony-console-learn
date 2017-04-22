<?php
namespace Repository;

use RJ\Repository\Implementation\GreetDataFile;
use UnitTester;

class GreetDataFileCest
{
    private $directory;

    private $file;

    private $fileWithDir;

    public function _before(UnitTester $I)
    {
        $this->directory = __DIR__;
        $this->file = 'prueba.yml';
        $this->fileWithDir = $this->directory . '/' . $this->file;
    }

    public function _after(UnitTester $I)
    {
        // Cleanup if test fail
        if (file_exists($this->fileWithDir)) {
            unlink($this->fileWithDir);
        }
    }

    public function checkFileIsCreatedOnConstruct(UnitTester $tester)
    {
        $tester->amInPath($this->directory);
        $tester->dontSeeFileFound($this->file);

        $tester->amGoingTo('create Data object');
        $greetData = new GreetDataFile($this->fileWithDir);

        $tester->amGoingTo('destruct object');
        unset($greetData);

        $tester->expectTo('see file prueba.yml exists');
        $tester->seeFileFound($this->file);

        $tester->deleteFile($this->file);
    }

    public function seeGreetingCorrectlyUpdateOnFile(UnitTester $tester)
    {
        $tester->amInPath($this->directory);
        $tester->dontSeeFileFound($this->file);

        $tester->amGoingTo('create Data Object');
        $greetData = new GreetDataFile(__DIR__ . '/' . $this->file);

        $greetData->setNumberOfGreeting('nombre', 3);
        $greetData->incrementNumberOfGreetings('nuevo');

        $tester->assertEquals(3, $greetData->getNumberOfGreetings('nombre'),
            'check number of greetings are as expected');
        $tester->assertEquals(1, $greetData->getNumberOfGreetings('nuevo'));

        $tester->amGoingTo('destruct Data Object');
        $greetData = null;
        $tester->expectTo(sprintf('see the file %s created', $this->file));
        $tester->seeFileFound($this->file);

        $tester->expectTo('see file has expected content');
        $tester->canSeeInThisFile('nombre: 3');
        $tester->canSeeInThisFile('nuevo: 1');

        $tester->deleteFile($this->file);
    }

    public function loadDataFromFileOnConstruct(UnitTester $tester)
    {
        $tester->amInPath($this->directory);
        $tester->dontSeeFileFound($this->file);

        $tester->amGoingTo('write content in file');

        $content = <<<"EOT"
perro: 1
nombre: 3
EOT;

        $tester->writeToFile($this->file, $content);
        $tester->canSeeFileFound($this->file);

        $tester->amGoingTo('create Data Object');
        $greetData = new GreetDataFile($this->fileWithDir);

        $tester->expect('that the data from file has been loaded');
        $tester->assertEquals(1, $greetData->getNumberOfGreetings('perro'),
            'perro has been greeted 3 times');
        $tester->assertEquals(3, $greetData->getNumberOfGreetings('nombre'),
            'nombre has been greeted 1 times');

        $tester->amGoingTo('destruct object and delete file');
        $greetData = null;
        $tester->deleteFile($this->file);
    }
}
