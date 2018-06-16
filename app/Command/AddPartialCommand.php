<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddPartialCommand
 * @package Wordrobe\Command
 */
class AddPartialCommand extends BaseCommand
{
  protected function configure()
  {
    $this->setName('add:partial');
    $this->setDescription('Adds a new partial template to your theme');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int|null|void
   * @throws \Exception
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    parent::execute($input, $output);
    $command = $this->getApplication()->find('add');
    $command->run(new ArrayInput(['content-type' => 'partial']), $output);
    self::execute($input, $output);
  }
}
