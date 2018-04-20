<?php

namespace Wordrobe\Helper;

/**
 * Class TemplateManager
 * @package Wordrobe\Helper
 */
class TemplateManager {

	/**
	 * Template content getter
	 *
	 * @param $template
	 * @return string
	 */
	public static function getTemplateContent($template)
	{
		$templateFile = TEMPLATES_PATH . $template;
		if (!file_exists($templateFile)) {
			Dialog::write('Error: "' . $template . '" template not found! Unable to continue.', 'red');
			exit();
		}
		return file_get_contents($templateFile);
	}

}