# Wordrobe CLI

### Wordpress Development Booster

###### A COMMAND LINE INTERFACE PROVIDING (AWE)SOME AUTOMATED PROCESSES THAT HELP TO EASILY CREATE THEMES, POST TYPES, TAXONOMIES, TEMPLATES AND MORE.

## Setup and Configuration
To make your project ready for **Wordrobe**, a *wordrobe.json* configuration file is needed. It must contain basic info about project's structure in order to allow **Wordrobe** to create and handle files at right paths.

The following command provides a tiny wizard to setup your project:
```
vendor/bin/wordrobe setup
```
It creates the *wordrobe.json* configuration file and lets you setup a new theme.

## Theme development
As easily as project's setup, **Wordrobe** allows you to create themes and themes' features by running simple command line wizards.

Run the following command to add the wanted feature:
```
vendor/bin/wordrobe add:[feature]
```
where *feature* can be one from the list below:

- theme
- post-type
- taxonomy
- term
- menu
- shortcode
- ajax-service
- page
- single
- archive
- partial

### Examples

#### Custom post types
```
vendor/bin/wordrobe add:post-type
```
The wizard allows to create both a new custom post type and relative template files.

#### Custom page templates
```
vendor/bin/wordrobe add:page
```
The wizard allows to create a new custom page template. 

## Template engine
During a theme setup wizard, you can choose between two different templating modes: **Twig (Timber)** and **PHP (Standard Wordpress)**.

#### Twig (Timber)
It provides an advanced theme scaffolding/boilerplate based on Controller/View paradigm. It involves *Timber* plugin and lets you to use *Twig* template engine in your theme development.

With this mode, the *add* command provides both controllers and views files if chosen content requires both. 

Please read [Timber documentation](TIMBER.md) for more information.

#### PHP (Standard Wordpress)
It provides a standard theme scaffolding/boilerplate based on default Wordpress templates files.

## Theme boilerplate
**Wordrobe** provides an optimized theme's boilerplate that helps you to automatically keep all your files and features organized and ready-to-use.

The *functions.php* file will automatically load files stored in *[theme-name]/includes* subfolders so you don't have to care about adding new features to it.
Each feature type (post-type, taxonomy, etc) will have its own subfolder at *[theme-name]/includes* that will be automatically created when you run the wizard of that type for the first time.

The *Theme.php* file located in *[theme-name]/includes* folder will provide basic theme options such as support, assets enqueueing, etc. You can extend it for custom settings.

## Complete command list
```
vendor/bin/wordrobe setup
```
```
vendor/bin/wordrobe add:theme
```
```
vendor/bin/wordrobe add:post-type
```
```
vendor/bin/wordrobe add:taxonomy
```
```
vendor/bin/wordrobe add:term
```
```
vendor/bin/wordrobe add:menu
```
```
vendor/bin/wordrobe add:shortcode
```
```
vendor/bin/wordrobe add:ajax-service
```
```
vendor/bin/wordrobe add:page
```
```
vendor/bin/wordrobe add:single
```
```
vendor/bin/wordrobe add:archive
```
```
vendor/bin/wordrobe add:partial
```