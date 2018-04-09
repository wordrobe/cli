<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
		<title><?php wp_title(''); ?></title>
		<meta name="description" content="<?php bloginfo(); ?>">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="format-detection" content="telephone=no">
		<meta name="application-name" content="">
		<meta name="msapplication-TileColor" content="#FFFFFF">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/assets/images/favicon/mstile-144x144.png">
		<link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon/apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon/apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon/apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon/apple-touch-icon-152x152.png">
		<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon/favicon-16x16.png" sizes="16x16">
        <script type="text/javascript">
            window.ENV = '<?php echo defined('WP_ENV') ? WP_ENV : false; ?>';
            window.AJAX_URL = '<?php echo site_url(); ?>/wp-admin/admin-ajax.php';
            window.LANG = '<?php echo defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : false; ?>';
        </script>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>

        <div class="pn-wrapper">

            <?php get_template_part('partials/header'); ?>

            <main class="content">