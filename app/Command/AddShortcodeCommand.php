<?php

namespace Wordrobe\Command;

/**
 * Class AddShortcodeCommand
 * @package Wordrobe\Command
 */
class AddShortcodeCommand extends AddCommand
{
  protected function configure()
  {
    $this->setName('add:shortcode');
    $this->setDescription('Adds a new shortcode to your theme');
    $this->setBuilder('ShortcodeBuilder');
  }
}
