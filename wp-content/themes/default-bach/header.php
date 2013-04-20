<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
		<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> 
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/i/favicon.ico" type="image/x-icon" />
		<script type='text/javascript' src='<?php bloginfo('url'); ?>/wp-admin/js/post.js'></script>
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/actions.js"></script>
		<?php wp_head(); ?>

	</head>

<?php
if ( is_user_logged_in() ) {
    echo '<h3>Hey! Todavía estamos trabajando. No te asustes si no ves tu información :)</h3>';
} else {
    die();
};
?>
<body>

<div id="wrapper">
	<!-- Our logo. If none provided in options, we will use default logo -->
	<a href="<?php bloginfo( 'url' ); ?>/"><img src="<?php echo get_option('bach_logo') == '' ? bloginfo('template_directory').'/i/logo.jpg' : get_option('bach_logo'); ?>" /></a>
	
	<br style="clear:both;">