# *Wordrobe* pattern

## Controllers
*Controller* files basically have the aim to handle *HTTP* requests. According to the route and any params, they retrieve data and:

- pass them to a *View* (for *GET* requests)
- return a *Json* response (for *POST* requests)

:bulb: For example, calling a *Controller* in *POST* is very useful to build single page applications with pushstate navigation. In fact, page data can be loaded via *AJAX* by calling directly a page's url with a *POST* request. This way, no specific *REST API* or other kinds of services are ever required in order to retrieve page data.

See the *Controller* example *[here](../example/controllers/event.php)*.

## Views
*View* files are aimed to represent on the front-end the data provided by the *Controllers*. They uses *[Twig](https://twig.symfony.com)* template engine in order to encourage front-end/back-end separation, modularity, extensibility and reusability of code. They can be used to build page layouts direcly with standard *HTML* or simply to pass data to *Javascript*, so that the layout can be implemented with modern frameworks like *React*.

See the *View* example *[here](../example/templates/views/event.html.twig)*.