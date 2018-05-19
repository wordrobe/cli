<?php

/*================*/
/* ADMIN SETTINGS */
/*================*/
function cleanAdminMenu() {
	remove_menu_page( 'edit-comments.php' );	// Comments
}
add_action('admin_menu', 'cleanAdminMenu');
