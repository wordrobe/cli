<?php

namespace Wordress\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Wordress\Helper\Config;
use Wordress\TemplateBuilder\TemplateBuilder;

/**
 * Class CreateCommand
 * @package Wordress\Command
 */
class CreateCommand extends BaseCommand
{
    protected function configure()
    {
        $this->setName('create');
        $this->setDescription('Creates a new template.');
        $this->addArgument('template', InputArgument::REQUIRED, 'The template type.');
        $this->addArgument('name', InputArgument::OPTIONAL, 'The template name.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
        // GETTING TEMPLATE TYPE PARAMETER
        $type = $this->getTemplateType();
        // GETTING FILENAME PARAMETER
        $name = self::$dialog->read('name');
        // CHECKING BUILDER EXISTANCE
        $builder = $this->getBuilder($type);
        // CHECKING CONFIG
		$this->checkConfig();
        // CREATING TEMPLATE
        $this->buildTemplate($builder, $name);
    }

	/**
	 * Reads template type from cli
	 *
	 * @return mixed
	 */
    private function getTemplateType()
	{
		$type = self::$dialog->read('template');
		if (empty($type)) {
			self::$dialog->write('Error: you must provide the template type parameter! Try with wordress:create template-type', 'red');
			exit();
		}
		$type = TemplateBuilder::normalizeString($type);
		return str_replace(' ', '', ucwords($type));
	}

	/**
	 * Template builder getter
	 *
	 * @param $type
	 * @return string
	 */
	private function getBuilder($type)
	{
		$builder = 'Wordress\TemplateBuilder\\' . $type . 'TemplateBuilder';
		if (!class_exists($builder)) {
			self::$dialog->write('Error: ' . $builder . ' is not defined.', 'red');
			exit();
		}
		return $builder;
	}

	/**
	 * Checks config existence and starts setup if missing
	 */
	private function checkConfig()
	{
		if (!Config::get()) {
			$runSetup = self::$dialog->getConfirmation('Attention: your project is not configured. Do you want to run setup?', true);
			if ($runSetup) {
				$command = $this->getApplication()->find('wordress:setup');
				$arguments = array('command' => 'wordress:setup');
				$command->run(new ArrayInput($arguments), self::$dialog->output);
			}
			exit();
		}
	}

	/**
	 * Handles template building
	 *
	 * @param $builder
	 * @param $name
	 */
	private function buildTemplate($builder, $name)
	{
		$template = new $builder(self::$dialog);
		if ($name) {
			$template->setFilename($name);
		}
		$template->create();
	}
}
