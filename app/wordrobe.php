<?php

use Symfony\Component\Console\Application;
use Wordrobe\Command\AddCommand;
use Wordrobe\Command\AddAjaxServiceCommand;
use Wordrobe\Command\AddArchiveCommand;
use Wordrobe\Command\AddMenuCommand;
use Wordrobe\Command\AddPageCommand;
use Wordrobe\Command\AddPartialCommand;
use Wordrobe\Command\AddPostTypeCommand;
use Wordrobe\Command\AddShortcodeCommand;
use Wordrobe\Command\AddSingleCommand;
use Wordrobe\Command\AddTaxonomyCommand;
use Wordrobe\Command\AddTermCommand;
use Wordrobe\Command\AddThemeCommand;
use Wordrobe\Command\SetupCommand;
use Composer\Factory;

define('PROJECT_ROOT', dirname(Factory::getComposerFile()));

$wordrobe = new Application('Wordrobe', 'v1.0.0');
$wordrobe->add(new AddThemeCommand());
$wordrobe->add(new AddPostTypeCommand());
$wordrobe->add(new AddTaxonomyCommand());
$wordrobe->add(new AddTermCommand());
$wordrobe->add(new AddMenuCommand());
$wordrobe->add(new AddPageCommand());
$wordrobe->add(new AddPartialCommand());
$wordrobe->add(new AddShortcodeCommand());
$wordrobe->add(new AddAjaxServiceCommand());
$wordrobe->add(new AddSingleCommand());
$wordrobe->add(new AddArchiveCommand());
$wordrobe->add(new SetupCommand());
$wordrobe->add(new AddCommand());

try {
  $wordrobe->run();
} catch (\Exception $exception) {
  exit($exception->getMessage());
}
