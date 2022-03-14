<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Tanganyika
 * @since 1.0.0
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php echo raven_favicon(); ?>
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="page" class="site">

	<header id="masthead">
		
		<div class="top-bar dark">
			<?php get_template_part( 'template-parts/header/top-bar' ); ?>
		</div>

		<div  class="site-header">
			<?php get_template_part( 'template-parts/header/site', 'branding' ); ?>
		</div>

	</header>

	<div id="content" class="site-content <?php raven_theme_content_class(); ?>">