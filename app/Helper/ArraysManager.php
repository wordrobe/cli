<?php

namespace Wordrobe\Helper;

/**
 * Class ArraysManager
 * @package Wordrobe\Helper
 */
final class ArraysManager
{
  
  /**
   * Array param getter
   * @param array $array
   * @param string $path
   * @return mixed|null
   */
  public static function get($array, $path)
  {
    if (isset($array[$path])) {
      return $array[$path];
    }
    
    foreach (explode('.', $path) as $segment) {
      if (!is_array($array) || !array_key_exists($segment, $array)) {
        return null;
      }
      
      $array = $array[$segment];
    }
    
    return $array;
  }
  
  /**
   * Array param setter
   * @param array $array
   * @param string $path
   * @param string $value
   * @return mixed
   */
  public static function set(&$array, $path, $value)
  {
    $keys = explode('.', $path);
    
    while (count($keys) > 1) {
      $key = array_shift($keys);
      
      if (!isset($array[$key]) || !is_array($array[$key])) {
        $array[$key] = [];
      }
      
      $array =& $array[$key];
    }
    
    $array[array_shift($keys)] = $value;
    
    return $array;
  }
  
  /**
   * Adds value to array param
   * @param array $array
   * @param string $path
   * @param mixed $value
   * @return array
   */
  public static function add(&$array, $path, $value)
  {
    $keys = explode('.', $path);
    
    while (count($keys) > 1) {
      $key = array_shift($keys);
      
      if (!isset($array[$key]) || !is_array($array[$key])) {
        $array[$key] = [];
      }
      
      $array =& $array[$key];
    }
  
    $array =& $array[array_shift($keys)];
    
    if (!in_array($value, $array)) {
      $array[] = $value;
    }
    
    return $array;
  }
}
