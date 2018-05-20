<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordrobe\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;

/**
 * Class AddCommand
 * @package Wordrobe\Command
 */
class AddCommand extends BaseCommand
{
	const FEATURES = [
		'theme',
		'child-theme',
		'page',
		'single',
		'archive',
		'custom-post-type',
		'custom-taxonomy',
		'ajax-service',
		'shortcode',
		'widget'
	];

	protected function configure()
	{
		$this->setName('add');
		$this->setDescription("Adds a new project's feature");
		$this->addArgument('feature', InputArgument::OPTIONAL, 'The feature name');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		parent::execute($input, $output);

		if (!Config::exists()) {
			$runInit = Dialog::getConfirmation('Your project is not configured yet. Do you want to run setup right now?', true, 'yellow');
			if (!$runInit) {
				exit();
			}
			$command = $this->getApplication()->find('init');
			$arguments = ['command' => 'init'];
			$command->run(new ArrayInput($arguments), Dialog::$output);
			return self::execute($input, $output);
		}

		if (!$feature = Dialog::read('feature')) {
			$feature = Dialog::getChoice('What kind of content do you want to add?', self::FEATURES, null);
		}

		if (in_array($feature, self::FEATURES) && $factory = self::getFactory($feature)) {
			$factory::startWizard();
		}
	}

	/**
	 * Entity factory getter
	 *
	 * @param $name
	 * @return string
	 */
	protected function getFactory($name)
	{
		$factory = 'Wordrobe\Factory\\' . StringsManager::toPascalCase($name) . 'Factory';
		if (!class_exists($factory)) {
			Dialog::write('Error: ' . $factory . ' is not defined.', 'red');
			return null;
		}
		return $factory;
	}
}
