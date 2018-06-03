<?php

/*================*/
/* ADMIN SETTINGS */
/*================*/
function cleanAdminMenu()
{
    // remove_menu_page('edit-comments.php'); (e.g. removing Comments Menu)
}
add_action('admin_menu', 'cleanAdminMenu');
