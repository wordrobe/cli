<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;

/**
 * Class AddCommand
 * @package Wordrobe\Command
 */
class AddCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('add');
        $this->setDescription('Adds a new template.');
        $this->addArgument('template-type', InputArgument::REQUIRED, 'The template type.');
        $this->addArgument('filename', InputArgument::OPTIONAL, 'The template filename.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		parent::execute($input, $output);

        // GETTING TEMPLATE TYPE PARAMETER
        $builderName = $this->readTemplateType();

        // GETTING FILENAME PARAMETER
        $filename = Dialog::read('filename');

        // CHECKING SETUP
		$this->checkSetup();

        // CREATING TEMPLATE
        $this->buildTemplate($builderName, $filename);
    }

	/**
	 * Reads template type from cli
	 *
	 * @return mixed
	 */
    private function readTemplateType()
	{
		$type = Dialog::read('template-type');
		if (empty($type)) {
			Dialog::write('Error: you must provide the template type parameter! Try with wordrobe add template-type', 'red');
			exit();
		}
		return StringsManager::toCamelCase($type);
	}

	/**
	 * Template builder getter
	 *
	 * @param $name
	 * @return string
	 */
	private function getBuilder($name)
	{
		$builder = 'Wordrobe\TemplateBuilder\\' . $name . 'TemplateBuilder';
		if (!class_exists($builder)) {
			Dialog::write('Error: ' . $builder . ' is not defined.', 'red');
			exit();
		}
		return $builder;
	}

	/**
	 * Checks config existence and starts setup if missing
	 */
	private function checkSetup()
	{
		if (!Config::get()) {
			$runSetup = Dialog::getConfirmation('Attention: your project is not configured. Do you want to run setup?', true);
			if ($runSetup) {
				$command = $this->getApplication()->find('setup');
				$arguments = ['command' => 'setup'];
				$command->run(new ArrayInput($arguments), Dialog::$output);
			}
			exit();
		}
	}

	/**
	 * Handles template building
	 *
	 * @param $builderName
	 * @param $filename
	 * @return mixed
	 */
	private function buildTemplate($builderName, $filename)
	{
		$builder = $this->getBuilder($builderName);
		return new $builder($filename);
	}
}
