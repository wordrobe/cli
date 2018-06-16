<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddThemeCommand
 * @package Wordrobe\Command
 */
class AddThemeCommand extends BaseCommand
{
  protected function configure()
  {
    $this->setName('add:theme');
    $this->setDescription('Adds a new theme to your project');
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
    $command->run(new ArrayInput(['content-type' => 'theme']), $output);
    self::execute($input, $output);
  }
}
