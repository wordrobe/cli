<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddTermCommand
 * @package Wordrobe\Command
 */
class AddTermCommand extends BaseCommand
{
  protected function configure()
  {
    $this->setName('add:term');
    $this->setDescription('Adds a new term to your theme');
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
    $command->run(new ArrayInput(['content-type' => 'term']), $output);
    self::execute($input, $output);
  }
}
