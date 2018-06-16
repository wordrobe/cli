<?php

namespace Wordrobe\Command;

/**
 * Class AddThemeCommand
 * @package Wordrobe\Command
 */
class AddThemeCommand extends AddCommand
{
  protected function configure()
  {
    $this->setName('add:theme');
    $this->setDescription('Adds a new theme to your project');
    $this->setBuilder('ThemeBuilder');
  }
}
