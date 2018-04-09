# Wordress

### Wordpress Development Booster

###### A COMMAND LINE INTERFACE PROVIDING (AWE)SOME AUTOMATED PROCESSES THAT HELP TO EASILY CREATE POST TYPES, TAXONOMIES, TEMPLATES AND MORE.

## Setup and Configuration
```
php app/console wordress:setup
```
This command starts a setup wizard that helps you to configure your project.

It provides/creates:
- a configuration file (wordress-config.json) on your project's root directory
- a brand new theme directory with basic theme's scaffolding and template files

## Development
During setup, you can choose between two different templating modes: "Twig (Timber)" and "Standard (native Wordpress)".

### Twig
It provides an advanced theme scaffolding/boilerplate based on Controller/View paradigm. It involves Timber and lets you to use Twig template engine in your theme development.

Please read [Timber documentation](TIMBER.md) for more information.

### Standard
It provides a standard theme scaffolding/boilerplate based on default Wordpress templates files.

## The wordress:create Command
To help you to easily create your theme's templates and features, the tool provides the wordress:create command:
```
php app/console wordress:create [template] [filename]
```
where [template] defines which type of content you want to be created (examples below), while [filename] (optional*) is the template's filename.

*If you don't provide filename as direct command argument, the tool will ask you to define it. 

### Examples

#### Creating Pages
```
php app/console wordress:create page [basename]
```
In "Twig" mode, both Controller and View will be created.

#### Creating Partials
```
php app/console wordress:create partial [basename]
```

#### Creating Post Types
```
php app/console wordress:create post-type [basename]
```
This command also creates the __single-[post-type].php__.

In "Twig" mode, the __single-[post-type].html.twig__ file is created too.

#### Creating Taxonomies
```
php app/console wordress:create taxonomy [basename]
```
This command also creates the __taxonomy-[taxonomy].php__.

In "Twig" mode, the __taxonomy-[taxonomy].html.twig__ file is created too.

#### Creating Singles
```
php app/console wordress:create single [basename]
```
In "Twig" mode, both Controller and View will be created.

#### Creating Archives
```
php app/console wordress:create archive [basename]
```
In "Twig" mode, both Controller and View will be created.

#### Creating Services*
```
php app/console wordress:create service [basename]
```
*Creates an API-like ajax service

#### Creating Shortcodes*
```
php app/console wordress:create shortcode [basename]
```
*Creates a shortcode bundle (php + js plugin)

#### Creating Utils*
```
php app/console wordress:create utility [basename]
```
*Creates a generic php file to wrap utility functions 

