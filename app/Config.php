<?php

namespace Wordrobe;

use Wordrobe\Entity\Template;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\FilesManager;
use Wordrobe\Helper\ArraysManager;

/**
 * Class Config
 * @package Wordrobe\Config
 */
class Config
{
    const FILENAME = 'wordrobe.json';
    const FILEPATH = PROJECT_ROOT . '/' . self::FILENAME;

    private static $params = null;

    /**
     * Checks Config existence
     * @return bool
     */
    public static function exists()
    {
        return FilesManager::fileExists(self::FILEPATH);
    }

    /**
     * Initializes Config
     * @param null $params
     */
    public static function init($params = null)
    {
        $template = new Template('project-config', $params);
        $template->save(self::FILEPATH);
    }

    /**
     * Gets Config param
     * @param $path
     * @return mixed|null
     */
    public static function get($path)
    {
        self::getContent();
        return ArraysManager::get(self::$params, $path);
    }

    /**
     * Gets Config param strictly
     * @param $path
     * @param null $type
     * @return mixed|null
     */
    public static function expect($path, $type = null)
    {
        $param = self::get($path);

        if (($type && gettype($param) !== $type) || is_null($param) || (gettype($param) === 'string' && empty($param))) {
            Dialog::write("Error: the required param '$path' is missing or invalid in " . self::FILEPATH . ". Please fix your configuration file in order to continue.", 'red');
            exit;
        }

        return $param;
    }

    /**
     * Sets Config param
     * @param $path
     * @param $value
     * @return array
     */
    public static function set($path, $value)
    {
        self::getContent();
        $param = ArraysManager::set(self::$params, $path, $value);
        self::updateContent();
        return $param;
    }

    /**
     * Adds Config param
     * @param $path
     * @param $value
     * @return array|null
     */
    public static function add($path, $value)
    {
        self::getContent();
        $param = ArraysManager::add(self::$params, $path, $value);
        self::updateContent();
        return $param;
    }

    /**
     * Gets Config file contents
     */
    private static function getContent()
    {
        try {
            $content = FilesManager::readFile(self::FILEPATH);
            if ($content) {
                self::$params = json_decode($content, true);
            } else {
                self::$params = null;
            }
        } catch (\Exception $e) {
            // continue
        }
    }

    /**
     * Updates Config file content
     */
    private static function updateContent()
    {
        try {
            FilesManager::writeFile(self::FILEPATH, json_encode(self::$params, JSON_PRETTY_PRINT), true);
        } catch (\Exception $e) {
            Dialog::write($e->getMessage(), 'red');
            exit();
        }

        Dialog::write(self::FILEPATH . ' updated!', 'cyan');
    }
}
