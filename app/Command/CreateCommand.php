<?php

namespace Wordress\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Wordress\Helper\Config;
use Wordress\Helper\Dialog;
use Wordress\TemplateBuilder\TemplateBuilder;

/**
 * Class CreateCommand
 * @package Wordress\Command
 */
class CreateCommand extends Command
{

	private static $dialog;

    protected function configure()
    {
        $this->setName('wordress:create');
        $this->setDescription('Creates a new template.');
        $this->addArgument('template', InputArgument::REQUIRED, 'The template type.');
        $this->addArgument('name', InputArgument::OPTIONAL, 'The template name.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        self::$dialog = new Dialog($this->getHelper('question'), $input, $output);

        // GETTING TEMPLATE TYPE PARAMETER
        $type = self::$dialog->read('template');
        if (empty($type)) {
            self::$dialog->write('Error: you must provide the template type parameter! Try with wordress:create template-type', 'red');
            exit();
        }
        $type = TemplateBuilder::normalizeString($type);
        $type = str_replace(' ', '', ucwords($type));

        // GETTING FILENAME PARAMETER
        $name = self::$dialog->read('name');

        // CHECKING BUILDER EXISTANCE
        $builder = 'Wordress\TemplateBuilder\\' . $type . 'TemplateBuilder';
        if (!class_exists($builder)) {
            self::$dialog->write('Error: ' . $builder . ' is not defined.', 'red');
            exit();
        }

        // GETTING CONFIG
        if (!Config::get()) {
            $runSetup = self::$dialog->getConfirmation('Attention: your project is not configured. Do you want to run setup?', true);
            if ($runSetup) {
                $command = $this->getApplication()->find('wordress:setup');
                $arguments = array('command' => 'wordress:setup');
                $command->run(new ArrayInput($arguments), $output);
            }
            exit();
        }

        // CREATING TEMPLATE
        $template = new $builder(self::$dialog);
        if ($name) {
            $template->setFilename($name);
        }
        $template->create();
    }
}
