<?php

namespace Wordrobe\Builder;

/**
 * Class Builder
 * @package Wordrobe\Builder
 */
interface Builder
{
    /**
     * Handles entity creation wizard
     */
    public static function startWizard();

    /**
     * Builds entity
     * @param array $params
     */
    public static function build($params);
}
