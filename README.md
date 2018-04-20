# Wordrobe

### Wordpress Development Booster

###### A COMMAND LINE INTERFACE PROVIDING (AWE)SOME AUTOMATED PROCESSES THAT HELP TO EASILY CREATE POST TYPES, TAXONOMIES, TEMPLATES AND MORE.

## Setup and Configuration
```
vendor/bin/wordrobe setup
```
This command starts a setup wizard that helps you to configure your project.

It provides/creates:
- a configuration file (wordrobe-config.json) on your project's root directory
- a brand new theme directory with basic theme's scaffolding and template files

## Template engine
During setup, you can choose between two different templating modes: "Twig (Timber)" and "Standard (native Wordpress)".

### Twig
It provides an advanced theme scaffolding/boilerplate based on Controller/View paradigm. It involves Timber and lets you to use Twig template engine in your theme development.

Please read [Timber documentation](TIMBER.md) for more information.

### Standard
It provides a standard theme scaffolding/boilerplate based on default Wordpress templates files.

## Theme development
To help you to easily create your theme's templates and features, the tool provides the _add_ command:
```
vendor/bin/wordrobe add [template] [basename]
```
where [template] defines which type of content you want to be created (examples below), while [filename] (optional*) is the template's filename.

*If you don't provide filename as direct command argument, the tool will ask you to define it. 

### Examples

#### Creating Pages
```
vendor/bin/wordrobe add page [basename]
```
In "Twig" mode, both Controller and View will be created.

#### Creating Partials
```
vendor/bin/wordrobe add partial [basename]
```

#### Creating Post Types
```
vendor/bin/wordrobe add post-type [basename]
```
This command also creates the __single-[post-type].php__.

In "Twig" mode, the __single-[post-type].html.twig__ file is created too.

#### Creating Taxonomies
```
vendor/bin/wordrobe add taxonomy [basename]
```
This command also creates the __taxonomy-[taxonomy].php__.

In "Twig" mode, the __taxonomy-[taxonomy].html.twig__ file is created too.
