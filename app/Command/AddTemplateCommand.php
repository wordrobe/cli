<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;

/**
 * Class AddTemplateCommand
 * @package Wordrobe\Command
 */
class AddTemplateCommand extends AddCommand
{
    protected function configure()
    {
        $this->setName('add:template');
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
			Dialog::write('Error: you must provide the template type parameter! Try with vendor/bin/wordrobe add:template template-type', 'red');
			exit();
		}
		return StringsManager::toCamelCase($type);
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
