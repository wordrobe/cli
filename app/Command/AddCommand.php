<?php

namespace Wordrobe\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wordrobe\Helper\Config;
use Wordrobe\Helper\Dialog;
use Wordrobe\Helper\StringsManager;

/**
 * Class AddCommand
 * @package Wordrobe\Command
 */
class AddCommand extends BaseCommand
{
  const CONTENT_TYPES = [
    'theme',
    'page',
    'single',
    'archive',
    'partial',
    'post-type',
    'taxonomy',
    'term',
    'menu',
    'ajax-service',
    'shortcode'
  ];

  protected function configure()
  {
    $this->setName('add');
    $this->setDescription('Adds a new content to your project');
    $this->addArgument('content-type', InputArgument::OPTIONAL, 'The content type');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int|mixed|null|void
   * @throws \Exception
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    parent::execute($input, $output);
    
    if (!Config::exists()) {
      $runInit = Dialog::getConfirmation('Your project is not configured yet. Do you want to run setup right now?', true, 'yellow');
      if (!$runInit) {
        exit;
      }
      $command = $this->getApplication()->find('init');
      $command->run(new ArrayInput([]), Dialog::$output);
      self::execute($input, $output);
      return;
    }
    
    if (!$content_type = Dialog::read('content-type')) {
      $content_type = Dialog::getChoice('What kind of content do you want to add?', self::CONTENT_TYPES, null);
    }
    
    if (in_array($content_type, self::CONTENT_TYPES) && $builder = self::getBuilder($content_type)) {
      $builder::startWizard();
    } else {
      Dialog::write("Error: content type '$content_type' not found.", 'red');
      $blank_add_command = $this->getApplication()->find('add');
      $blank_add_command->run(new ArrayInput([]), $output);
    }
  }
  
  /**
   * Entity builder getter
   *
   * @param string $name
   * @return string
   */
  protected function getBuilder($name)
  {
    $builder = 'Wordrobe\Builder\\' . StringsManager::toPascalCase($name) . 'Builder';
    if (!class_exists($builder)) {
      Dialog::write('Error: ' . $builder . ' is not defined.', 'red');
      exit;
    }
    return $builder;
  }
}
