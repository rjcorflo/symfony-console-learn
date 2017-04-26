<?php
namespace RJ\Repository\Implementation;

use RJ\Repository\GreetDataInterface;
use Symfony\Component\Yaml\Yaml;

class GreetDataFile implements GreetDataInterface
{
    /**
     * @var string $file
     */
    protected $file;

    /**
     * @var array $greetings
     */
    protected $greetings;

    /**
     * Constructor
     *
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;

        if (file_exists($file)) {
            $this->greetings = Yaml::parse(file_get_contents($file));
        } else {
            $this->greetings = [];
        }
    }

    /**
     * On destruction dumps content of greeting to file
     */
    public function __destruct()
    {
        file_put_contents($this->file, Yaml::dump($this->greetings, 4));
    }

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
    ) : void {
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
        $cleanName =  strtolower(\URLify::transliterate($name));

        return $cleanName;
    }
}
