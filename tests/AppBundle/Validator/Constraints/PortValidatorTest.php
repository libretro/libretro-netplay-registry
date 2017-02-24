<?php

namespace Tests\AppBundle\Validator\Constraints;

use AppBundle\Validator\Constraints\Port;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class PortValidatorTest.
 *
 * @covers AppBundle\Validator\Constraints\PortValidator
 */
class PortValidatorTest extends WebTestCase
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var Application
     */
    private $application;

    /**
     * Prepares environment for tests.
     */
    public function setup()
    {
        self::bootKernel();
        $this->application = new \Symfony\Bundle\FrameworkBundle\Console\Application(self::$kernel);
        $this->application->setAutoExit(false);
        $this->runConsole('doctrine:schema:drop', ['--force' => true]);
        $this->runConsole('doctrine:schema:create');
        $this->container = self::$kernel->getContainer();
    }

    /**
     * @param       $command
     * @param array $options
     *
     * @return mixed
     */
    protected function runConsole($command, array $options = [])
    {
        $options['-e'] = 'test';
        $options['-q'] = null;
        $options       = array_merge($options, ['command' => $command]);

        return $this->application->run(new ArrayInput($options));
    }

    public function testValidate()
    {
        $validator = $this->container->get('validator');

        $errors = $validator->validate('660', new Port());
        $this->assertEquals(0, count($errors));

        $errors = $validator->validate(660, new Port());
        $this->assertEquals(0, count($errors));

        $errors = $validator->validate(0, new Port());
        $this->assertEquals(1, count($errors));

        $errors = $validator->validate('asdf', new Port());
        $this->assertEquals(1, count($errors));

        $errors = $validator->validate('567898654456', new Port());
        $this->assertEquals(1, count($errors));
    }
}
