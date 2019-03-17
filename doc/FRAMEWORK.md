# *Wordrobe* framework

## Entity
An *Entity* basically represents a *Post* object, so you can see it as an alternative of a *WP_Post*. The advantage of using *Entities* instead of standard *WP_Post* (or *Timber\Post*) is that they grant you full control on data and so allow you to manage cleaner and more circumscribed sets of information. This way you can easily prevent to get lost in rubbish.

See the *Entity* example *[here](../example/core/Entity/Event.php)*.


## Repository
Generally, a *Repository* is an abstraction layer for accessing data. More specifically, a *Repository* takes care of retrieving one or more instances of a specific *Entity* the *Repository* refers to. With *Wordrobe*, in fact, for each *Entity* there is a related *Repository* that wraps operations like *get_post* or *get_posts* and uses them with default params according to the *Entity* type. In other words, a *Wordrobe Repository* handles queries to retrieve posts of specific type (or of specific template for pages case).

See the *Repository* example *[here](../example/core/Repository/EventRepository.php)*.


## DTO (Data Transfer Object)
*DTO* are the last layer of the framework and they represent the shape through which *Enitity*'s data is transmitted. They allow to eventually reorganize *Entity*'s data in order to be more suitable for the "places" they are delivered to, so that you haven't to update your *Entity* each time only format changes are required.

See the *DTO* example *[here](../example/core/DTO/EventDTO.php)*.