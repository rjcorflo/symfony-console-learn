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

    public function checkFileIsCreatedOnConstruct(UnitTester $I)
    {
        $I->amInPath($this->directory);
        $I->dontSeeFileFound($this->file);

        $I->amGoingTo('create Data object');
        $greetData = new GreetDataFile($this->fileWithDir);

        $I->amGoingTo('destruct object');
        unset($greetData);

        $I->expectTo('see file prueba.yml exists');
        $I->seeFileFound($this->file);

        $I->deleteFile($this->file);
    }

    public function seeGreetingCorrectlyUpdateOnFile(UnitTester $I)
    {
        $I->amInPath($this->directory);
        $I->dontSeeFileFound($this->file);

        $I->amGoingTo('create Data Object');
        $greetData = new GreetDataFile(__DIR__ . '/' . $this->file);

        $greetData->setNumberOfGreeting('nombre', 3);
        $greetData->incrementNumberOfGreetings('nuevo');

        $I->assertEquals(3, $greetData->getNumberOfGreetings('nombre'),
            'check number of greetings are as expected');
        $I->assertEquals(1, $greetData->getNumberOfGreetings('nuevo'));

        $I->amGoingTo('destruct Data Object');
        $greetData = null;
        $I->expectTo(sprintf('see the file %s created', $this->file));
        $I->seeFileFound($this->file);

        $I->expectTo('see file has expected content');
        $I->canSeeInThisFile('nombre: 3');
        $I->canSeeInThisFile('nuevo: 1');

        $I->deleteFile($this->file);
    }

    public function loadDataFromFileOnConstruct(UnitTester $I)
    {
        $I->amInPath($this->directory);
        $I->dontSeeFileFound($this->file);

        $I->amGoingTo('write content in file');

        $content = <<<"EOT"
perro: 1
nombre: 3
EOT;

        $I->writeToFile($this->file, $content);
        $I->canSeeFileFound($this->file);

        $I->amGoingTo('create Data Object');
        $greetData = new GreetDataFile($this->fileWithDir);

        $I->expect('that the data from file has been loaded');
        $I->assertEquals(1, $greetData->getNumberOfGreetings('perro'),
            'perro has been greeted 3 times');
        $I->assertEquals(3, $greetData->getNumberOfGreetings('nombre'),
            'nombre has been greeted 1 times');

        $I->amGoingTo('destruct object and delete file');
        $greetData = null;
        $I->deleteFile($this->file);
    }
}
