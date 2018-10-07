<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;

/**
 * Class TemplateBuilder
 * @package Wordrobe\Builder
 */
abstract class TemplateBuilder
{
  /**
   * Asks for target theme
   * @return mixed
   * @throws \Exception
   */
  protected static function askForTheme()
  {
    Config::check('themes-path', 'string');
    $themes = Config::get('themes', ['type' => 'array']);
    
    switch (count($themes)) {
      case 0:
        Dialog::write("Your project doesn't have any themes yet. Please run 'vendor/bin/wordrobe add:theme' first.", 'red');
        exit;
      case 1:
        $theme = array_keys($themes)[0];
        break;
      default:
        $theme = Dialog::getChoice('Please choose the theme you want to add the content to:', array_keys($themes), null, false, 'yellow');
        break;
    }

    return $theme;
  }
}
