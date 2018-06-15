<?php

namespace Wordrobe\Helper;

use Wordrobe\Config;
use Wordrobe\Builder\ConfigBuilder;
use Wordrobe\Builder\ThemeBuilder;

/**
 * Class SetupManager
 * @package Wordrobe\Helper
 */
class SetupManager
{
  /**
   * Handles first time setup
   */
  public static function install()
  {
    if (!Config::exists()) {
      ConfigBuilder::startWizard();
    }

    if (Dialog::getConfirmation('Do you want to add a new theme right now?', true)) {
      ThemeBuilder::startWizard();
    }
  }
}