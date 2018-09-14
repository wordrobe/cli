<?php

use Symfony\Component\Console\Application;
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

$ascii = '
 _      __            __         __      
| | /| / /__  _______/ /______  / /  ___ 
| |/ |/ / _ \/ __/ _  / __/ _ \/ _ \/ -_)
|__/|__/\___/_/  \_,_/_/  \___/_.__/\__/ 
                                   ';

$wordrobe = new Application($ascii, 'v1.1');

$wordrobe->add(new AddAjaxServiceCommand());
$wordrobe->add(new AddArchiveCommand());
$wordrobe->add(new AddMenuCommand());
$wordrobe->add(new AddPageCommand());
$wordrobe->add(new AddPartialCommand());
$wordrobe->add(new AddPostTypeCommand());
$wordrobe->add(new AddShortcodeCommand());
$wordrobe->add(new AddSingleCommand());
$wordrobe->add(new AddTaxonomyCommand());
$wordrobe->add(new AddTermCommand());
$wordrobe->add(new AddThemeCommand());
$wordrobe->add(new SetupCommand());

try {
  $wordrobe->run();
} catch (\Exception $exception) {
  exit($exception->getMessage());
}
