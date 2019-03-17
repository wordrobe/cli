<div>
  <a href="https://www.fiad.one/wordrobe/">
    <img src="https://www.fiad.one/media/wordrobe/banner-cli.jpg" style="display:block; margin:0 auto; width:100%;" />
  </a>
</div>

###

*Wordrobe* is a *Wordpress* development boosting tool that combines the quickness of automated processes with the solidity of a structured workflow.

By taking charge of the most common operations concerning *Wordpress* themes development and by providing a well organized development model, it allows you to save time and to produce a better quality code with an eye towards modularity and maintainability.

## Installation
In order to install and use *Wordrobe*, *[Composer](https://getcomposer.org)* is required. After verifying it's correctly installed, run the following command at the root of your project:

```
composer require wordrobe/cli
```

Once installed, *Wordrobe* also requires a simple starting configuration to fit in your project's structure so it could write and handle files at correct paths.

By running
```
vendor/bin/wordrobe setup
```
a setup wizard will start and a *wordrobe.json* file will be automatically created at your project's root. It will look like this:
```
{
  "themes-path": "your/path/to/themes",
  "themes": {}
}
```
:bulb: In a standard *Wordpress* installation, "themes-path" will be "wp-content/themes".

#### The *Wordrobe Skeleton*
*Wordrobe* also offers its own skeleton to setup a new project from scratch. It provides an advanced and boosted structure based on *[Bedrock](https://github.com/roots/bedrock)* by *[Roots](https://roots.io)*. Check *Wordrobe Skeleton* [documentation](https://bitbucket.org/fiad/wordrobe-skeleton/src/master) for more information.

## Usage
*Wordrobe* exposes all its power of adding features to your *Wordpress* based application by the simplest way ever: just run the following command and enjoy the wizard:
```
vendor/bin/wordrobe add:[feature]
```
Check the complete [commands list](./doc/COMMANDS.md) to discover all supported features.

### Fundamentals
#### Adding a new theme
One of the most basic but most important operations you deal with when you start a new *Wordpress* project, is the creation of a brand new theme. At this stage, many doubts about structure and files organization usually arise, specially when focus about code modularity and maintainability is required.

*Wordrobe* offers an optimized theme's file system that helps you to keep your code scoped and well organized, encouraging frontend/backend separation, integration and reusability. For more information, check theme's [in-depth documentation](./doc/THEME.md).

To create a new theme, simply run the command below and follow the wizard:
```
vendor/bin/wordrobe add:theme
```
:bulb: Wordrobe* supports multi-theme development, so if more than one theme will be added to your project, you will be asked which theme you want to deal with each time you'll run the __*add*__ command.

#### Adding a page template
Page templates are one of the most commonly used ways to make an association between specific contents and layout. To let *Wordrobe* to create one for you, just run the following command:
```
vendor/bin/wordrobe add:page
``` 
The page template creation process let us to observe the first pillar of *Wordrobe* architecture: the __*controller/view pattern*__ implementation. It's entrusted to *[Twig](https://twig.symfony.com)* through the use of *[Timber]()* plugin by *[Upstatement](https://www.upstatement.com)* and allows a perfect separation between frontend and backend development.

For more information, check the __*controller/view pattern*__'s [in-depth documentation](./doc/PATTERN.md).

#### Adding a custom post-type
Another common operation in *Wordpress* development is the creation of custom post-types, aimed to handle project-specif content types. By the following command, *Wordrobe* comes in support for this goal too:
```
vendor/bin/wordrobe add:post-type
```
Along with the post-type registration code and the __*controller/view pattern*__ implementation, the command above provides the scaffolding that allows *Wordrobe* to carry out its own architecture through the introduction of its second pillar: the __*entity/repository/dto framework*__.

It's inspired by *[Doctrine](https://www.doctrine-project.org)* and you can learn more about it by reading its [in-depth documentation](./doc/FRAMEWORK.md).

### More?
Check the [example](./example) for more details and information about *Wordrobe* usage.