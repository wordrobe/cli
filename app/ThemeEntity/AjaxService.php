<?php

namespace Wordrobe\ThemeEntity;

use Wordrobe\Helper\StringsManager;

/**
 * Class AjaxService
 * @package Wordrobe\ThemeEntity
 */
class AjaxService implements ThemeEntity
{
    private $name;

    /**
     * AjaxService constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = StringsManager::toSnakeCase($name);
        add_action("wp_ajax_nopriv_$this->name", [$this, 'register']);
        add_action("wp_ajax_$this->name", [$this, 'register']);
    }

    public function register()
    {
        // Service logic here
    }
}
