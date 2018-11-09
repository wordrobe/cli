<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Builder\ConfigBuilder;
use Wordrobe\Builder\ThemeBuilder;

/**
 * Class AddCommand
 * @package Wordrobe\Command
 */
abstract class AddCommand extends BaseCommand
{
  private $builder;

  /**
   * Builder setter
   * @param string $builder
   */
  protected function setBuilder($builder)
  {
    if (class_exists("Wordrobe\Builder\\$builder")) {
      $this->builder = $builder;
    } else {
      Dialog::write('Error: ' . $builder . ' is not defined. Run "vendor/bin/wordrobe list" to show available commands list.', 'red');
      exit;
    }
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
    $this->checkSetup();
    $this->checkThemes();
    call_user_func('Wordrobe\Builder\\' . $this->builder . '::startWizard', $input->getArguments());
  }

  /**
   * Checks project setup
   * @throws \Exception
   */
  private function checkSetup()
  {
    if (Config::exists()) {
      return;
    }

    if (Dialog::getConfirmation('Your project is not configured yet. Do you want to run setup right now?', true, 'yellow')) {
      ConfigBuilder::startWizard();

      if (Dialog::getConfirmation('Do you want to add a new theme right now?', true, 'yellow')) {
        ThemeBuilder::startWizard();

        if ($this->builder !== 'ThemeBuilder') {
          Dialog::write('Resuming ' . $this->builder . ' wizard...', 'cyan');
          return;
        }

        exit;
      }
    }

    Dialog::write('Unable to continue.', 'red');
    exit;
  }

  /**
   * Checks themes existence
   * @throws \Exception
   */
  private function checkThemes()
  {
    if ($this->builder !== 'ThemeBuilder' && empty(Config::get('themes'))) {
      if (Dialog::getConfirmation('Your project has no themes yet. Do you want to add one right now?', true, 'yellow')) {
        ThemeBuilder::startWizard();
        Dialog::write('Resuming ' . $this->builder . ' wizard...', 'cyan');
        return;
      }

      Dialog::write('Unable to continue.', 'red');
      exit;
    }
  }
}
