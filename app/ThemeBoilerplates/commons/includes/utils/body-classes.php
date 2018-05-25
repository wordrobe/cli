<?php

/*==============*/
/* PAGE CLASSES */
/*==============*/
function setPageClass($classes)
{
    global $post;

    if (is_page_template()) {
        $classes = array_merge($classes, array('page--' . str_replace('.php', '', basename(get_page_template_slug()))));
    } elseif (is_page()) {
        $classes = array_merge($classes, array('page--' . $post->post_name));
    }

    return $classes;
}

/*========*/
/* ROUTER */
/*========*/
function setRouteClass($classes)
{
    if (is_page_template()) {
        $template = str_replace('.php', '', basename(get_page_template_slug()));

        switch ($template) {
            case 'homepage':
                $classes = array_merge($classes, array('js-router--homepage'));
                break;
            default:
                break;
        }
    } elseif (is_page()) {
        $classes = array_merge($classes, array('js-router--default-page'));
    }

    return $classes;
}

/*==============*/
/* BODY CLASSES */
/*==============*/
function setBodyClasses($classes)
{
    $classes = setPageClass($classes);
    $classes = setRouteClass($classes);
    return $classes;
}

add_filter('body_class', 'setBodyClasses');
