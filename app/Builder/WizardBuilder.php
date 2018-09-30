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
   */
  public static function startWizard();
}
