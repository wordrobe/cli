<?php

namespace Wordrobe\Builder;

use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;
use Wordrobe\Helper\FilesManager;

/**
 * Class SchemaBuilder
 * @package Wordrobe\Builder
 */
class SchemaBuilder extends TemplateBuilder implements WizardBuilder
{
  /**
   * Handles component template build wizard
   * @param null|array $args
   */
  public static function startWizard($args = null)
  {
    try {
      $theme = self::askForTheme();
      $schema = is_array($args) ? $args['schema'] : null;
      self::build([
        'schema' => $schema,
        'theme' => $theme,
        'override' => 'ask'
      ]);
      Dialog::write('Schema added!', 'green');
    } catch (\Exception $e) {
      Dialog::write($e->getMessage(), 'red');
      exit;
    }
  }

  /**
   * Builds component template
   * @param array $params
   * @example SchemaBuilder::build([
   *  'schema' => $schema
   *  'theme' => $theme,
   *  'override' => 'ask'|'force'|false
   * ]);
   * @throws \Exception
   */
  public static function build($params)
  {
    $params = self::prepareParams($params);

    foreach ($params['schema'] as $feature => $entries) {
      foreach ($entries as $entry) {
        $entry['theme'] = $params['theme'];
        $entry['text-domain'] = $params['text-domain'];
        $entry['override'] = $params['override'];

        try {
          call_user_func('Wordrobe\Builder\\' . StringsManager::toPascalCase($feature) . 'Builder::build', $entry);
        } catch (\Exception $e) {
          Dialog::write($e->getMessage());
        }
      }
    }
  }

  /**
   * Checks params existence and normalizes them
   * @param array $params
   * @return mixed
   * @throws \Exception
   */
  private static function prepareParams($params)
  {
    // checking theme
    $theme = StringsManager::toKebabCase($params['theme']);
    Config::check("themes.$theme", 'array', "Error: theme '$theme' doesn't exist.");

    // checking params
    if (!$params['schema'] || !FilesManager::fileExists($params['schema'])) {
      throw new \Exception('Error: missing schema.');
    }

    // normalizing
    $schema = FilesManager::readFile($params['schema']);
    $override = strtolower($params['override']);

    if ($override !== 'ask' && $override !== 'force') {
      $override = false;
    }

    return [
      'schema' => json_decode($schema, true),
      'override' => $override,
      'theme' => $theme,
      'text-domain' => Config::get("themes.$theme.text-domain")
    ];
  }
}
