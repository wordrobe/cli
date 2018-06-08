<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordrobe\Helper\Dialog;

/**
 * Class BaseCommand
 * @package Wordrobe\Command
 */
abstract class BaseCommand extends Command
{
  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int|null|void
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    Dialog::init($this->getHelper('question'), $input, $output);
  }
}
