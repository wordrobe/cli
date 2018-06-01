<?php

namespace Wordrobe\Builder;

use Wordrobe\Config;
use Wordrobe\Helper\Dialog;

abstract class TemplateBuilder
{
  /**
   * Asks for target theme
   * @param null|array $requirements
   * @return mixed
   */
  protected static function askForTheme($requirements = null)
  {
    $themes = Config::expect('themes', 'array');
    
    switch (count($themes)) {
      case 0:
        Dialog::write("Your project doesn't have any themes yet. Please run 'vendor/bin/wordrobe add theme' first.", 'red');
        exit;
      case 1:
        $theme = array_keys($themes)[0];
        break;
      default:
        $theme = Dialog::getChoice('Please choose the theme you want to add the content to:', array_keys($themes), null, false, 'yellow');
        break;
    }
    
    Config::expect('themes-path');
    Config::expect("themes.$theme");
    
    if ($requirements) {
      foreach ($requirements as $requirement) {
        Config::expect("themes.$theme.$requirement");
      }
    }
    
    return $theme;
  }
  
  /**
   * Asks for override confirmation
   * @param $filepath
   * @return mixed
   */
  protected static function askForOverrideConfirmation($filepath)
  {
    return Dialog::getConfirmation('Attention: ' . $filepath . ' already exists! Do you want to override it?', false, 'red');
  }
}
