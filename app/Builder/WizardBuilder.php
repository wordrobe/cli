<?php

namespace Wordrobe\Builder;

/**
 * Interface WizardBuilder
 * @package Wordrobe\Builder
 */
interface WizardBuilder extends Builder
{
  /**
   * Handles entity creation wizard
   * @param null|array $args
   */
  public static function startWizard($args = null);
}
