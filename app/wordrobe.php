<?php

use Symfony\Component\Console\Application;
use Wordrobe\Command\AddMenuCommand;
use Wordrobe\Command\AddPageCommand;
use Wordrobe\Command\AddComponentCommand;
use Wordrobe\Command\AddPostTypeCommand;
use Wordrobe\Command\AddServiceCommand;
use Wordrobe\Command\AddShortcodeCommand;
use Wordrobe\Command\AddTaxonomyCommand;
use Wordrobe\Command\AddTermCommand;
use Wordrobe\Command\AddThemeCommand;
use Wordrobe\Command\SetupCommand;

$ascii = '
                     yshN                       
                     y///oym                    
                     y//////+sdN                
                     y//////////oym             
                     y/////////////+sdN         
            Nmm      y//////oo+////////ohm      
        Nmmdddm      y//////ssssso+///////N     
      mdddddddm      y//////sssssssso/////N     
      ddddddddm      y//////sssssssss/////N     
      ddddddddm      y//////sssssssss/////N     
      ddddddddm      y//////sssssssss/////N     
      ddddddddm      y//////sssssssss/////N     
      ddddddddm   Ndyhhs+///sssssssss/////N     
      ddddddddmmhyssshdddhyosssssssss/////N     
      ddddddddhsssssshddddddyssssssss/////N     
      ddddddddhsssssshddddddyssssssss/////N     
      mdddddddhsssssshddddddyssssssso/////N     
        NNmdddhsssydmyoyhdddysssoo+///////N     
            NmhhmN   y///+shso+///////+shN      
                     y/////////////oym          
                     y//////////shN             
                     y//////+ym                 
                     y///shN                    
                     yyd               ';

$wordrobe = new Application($ascii, 'v1.0');

$wordrobe->add(new AddMenuCommand());
$wordrobe->add(new AddPageCommand());
$wordrobe->add(new AddComponentCommand());
$wordrobe->add(new AddPostTypeCommand());
$wordrobe->add(new AddServiceCommand());
$wordrobe->add(new AddShortcodeCommand());
$wordrobe->add(new AddTaxonomyCommand());
$wordrobe->add(new AddTermCommand());
$wordrobe->add(new AddThemeCommand());
$wordrobe->add(new SetupCommand());

try {
  $wordrobe->run();
} catch (\Exception $exception) {
  exit($exception->getMessage());
}
