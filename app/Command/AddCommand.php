<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordrobe\Helper\Dialog;

/**
 * Class AddCommand
 * @package Wordrobe\Command
 */
class AddCommand extends BaseCommand
{
	protected function configure()
	{
		$this->setName('add');
		$this->setDescription("Adds a new theme's feature");
		$this->addArgument('entity', InputArgument::REQUIRED, 'The entity name');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		parent::execute($input, $output);
		$entity = Dialog::read('entity');
		$factory = self::getFactory($entity);
		$factory::startWizard();
	}

	/**
	 * Entity factory getter
	 *
	 * @param $entity
	 * @return string
	 */
	protected function getFactory($entity)
	{
		$factory = 'Wordrobe\Factory\\' . $entity . 'Factory';
		if (!class_exists($factory)) {
			Dialog::write('Error: ' . $factory . ' is not defined.', 'red');
			exit();
		}
		return $factory;
	}
}
