<?php

namespace Wordrobe\Helper;

/**
 * Class FilesManager
 * @package Wordrobe\Helper
 */
final class FilesManager
{
  
  /**
   * Checks file existence
   * @param string $filepath
   * @return bool
   */
  public static function fileExists($filepath)
  {
    return file_exists($filepath);
  }
  
  /**
   * Checks directory existence
   * @param string $path
   * @return bool
   */
  public static function directoryExists($path)
  {
    return is_dir($path);
  }
  
  /**
   * Handles directory creation
   * @param string $path
   * @param int $mode
   * @param bool $recursive
   * @throws \Exception
   */
  public static function createDirectory($path, $mode = 0755, $recursive = true)
  {
    if (!self::directoryExists($path)) {
      $dir = mkdir($path, $mode, $recursive);
      
      if (!$dir) {
        throw new \Exception("Error: unable to create $path.");
      }
    }
  }
  
  /**
   * Handles file write
   * @param string $filename
   * @param string $content
   * @param bool $force_override
   * @throws \Exception
   */
  public static function writeFile($filename, $content, $force_override = false)
  {
    $file_exists = self::fileExists($filename);
  
    if ($file_exists && !$force_override) {
      throw new \Exception("Error: $filename already exists.");
    }
    
    self::createDirectory(dirname($filename));
    $file = fopen($filename, 'w');
    $written = fwrite($file, $content);
    fclose($file);
    
    if ($written === false) {
      throw new \Exception("Error: unable to write $filename.");
    }
  }
  
  /**
   * File contents getter
   * @param string $filename
   * @return string
   * @throws \Exception
   */
  public static function readFile($filename)
  {
    if (!self::fileExists($filename)) {
      throw new \Exception("Error: $filename doesn't exist.");
    }
    
    return file_get_contents($filename);
  }

  /**
   * Removes file
   * @param string $filename
   */
  public static function deleteFile($filename)
  {
    if (self::fileExists($filename)) {
      unlink($filename);
    }
  }
  
  /**
   * Handles file/directory permissions modification
   * @param string $path
   * @param int $mode
   * @throws \Exception
   */
  public static function setPermissions($path, $mode)
  {
    if (!chmod($path, $mode)) {
      throw new \Exception("Error: unable to change $path permissions.");
    }
  }
  
  /**
   * Handles files copy
   * @param string $source
   * @param string $destination
   * @param array $errors
   * @throws \Exception
   */
  public static function copyFiles($source, $destination, $errors = [])
  {
    if (!self::directoryExists($source)) {
      throw new \Exception("Error: $source doesn't exist.");
    }
    
    $files = scandir($source);
    self::createDirectory($destination);
    
    foreach ($files as $file) {
      if ($file != '.' && $file != '..') {
        if (self::directoryExists("$source/$file")) {
          try {
            self::copyFiles("$source/$file", "$destination/$file", $errors);
          } catch (\Exception $e) {
            // continue
          }
        } else {
          $copied = copy("$source/$file", "$destination/$file");
          if (!$copied) {
            $errors[] = $file;
          }
        }
      }
    }
    
    if (count($errors)) {
      $error_files = implode(', ', $errors);
      throw new \Exception("Error: unable to copy following files [$error_files].");
    }
  }
}
