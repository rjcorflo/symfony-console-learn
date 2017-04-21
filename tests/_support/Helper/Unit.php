<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Unit extends \Codeception\Module
{
    public function expectError($error, $callback)
    {
        $code = null;
        $msg = null;
        if (is_object($error)) {
            /** @var $error \Error **/
            $class = get_class($error);
            $msg = $error->getMessage();
            $code = $error->getCode();
        } else {
            $class = $error;
        }
        try {
            $callback();
        } catch (\Error $e) {
            if (!$e instanceof $class) {
                $this->fail(sprintf("Error of class $class expected to be thrown, but %s caught", get_class($e)));
            }
            if (null !== $msg and $e->getMessage() !== $msg) {
                $this->fail(sprintf(
                    "Error of $class expected to be '$msg', but actual message was '%s'",
                    $e->getMessage()
                ));
            }
            if (null !== $code and $e->getCode() !== $code) {
                $this->fail(sprintf(
                    "Error of $class expected to have code $code, but actual code was %s",
                    $e->getCode()
                ));
            }
            $this->assertTrue(true); // increment assertion counter
            return;
        }
        $this->fail("Expected Error to be thrown, but nothing was caught");
    }
}
