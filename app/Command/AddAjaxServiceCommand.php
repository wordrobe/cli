<?php

namespace Wordrobe\Command;

/**
 * Class AddAjaxServiceCommand
 * @package Wordrobe\Command
 */
class AddAjaxServiceCommand extends AddCommand
{
  protected function configure()
  {
    $this->setName('add:ajax-service');
    $this->setDescription('Adds a new ajax service to your theme');
    $this->setBuilder('AjaxServiceBuilder');
  }
}
