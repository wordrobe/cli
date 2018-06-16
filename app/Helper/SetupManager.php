<?php

namespace Wordrobe\Helper;

use Composer\Script\Event;
use Symfony\Component\Process\Process;
use Wordrobe\Builder\ConfigBuilder;
use Wordrobe\Builder\ThemeBuilder;

/**
 * Class SetupManager
 * @package Wordrobe\Helper
 */
class SetupManager
{
  /**
   * Handles library installation
   */
  public static function install()
  {
    if (!Config::exists()) {
      ConfigBuilder::startWizard();
    } else {
      Dialog::write('Project configuration already exists! Check wordrobe.json file in the project root for details.', 'green');
    }

    if (Dialog::getConfirmation('Do you want to add a new theme right now?', true, 'yellow')) {
      ThemeBuilder::startWizard();
    }
  }

  /**
   * Handles library installation from script
   * @param Event $event
   */
  public static function installFromScript(Event $event)
  {
    $process = new Process('php ' . __DIR__ . '/../../bin/wordrobe init');
    $process->run(function ($type, $buffer) use ($event) {
      $event->getIO()->write($buffer, false);
    });
  }
}