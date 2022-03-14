<?php
/**
 * Register and call classes
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) )
	die("Direct access is not permitted");


if( ! function_exists('raven_theme_get_account_link') ) :
	/**
	 * Get the account link
	 *
	 * @since 1.0.0
	 */
	function raven_theme_get_account_link() {
		
		$url = get_site_url() . '/my-account';
		
		if( is_user_logged_in() ) {
			$title = "My Account";
		} else {
			$title = "Sign up/Sign in";
		}
		
		echo sprintf( '<a href="%1$s" title="%2$s">%2$s</a>', $url, $title );
		
	}
endif;