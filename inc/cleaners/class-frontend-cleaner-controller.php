<?php
/**
 * Frontend cleanup
 * Removes un-needed files from the front end
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_frontend_cleaner_controller') ) {
	
	class Raven_frontend_cleaner_controller {
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			
			// Display the links to the extra feeds such as category feeds
			remove_action( 'wp_head', 'feed_links_extra', 3 );
			
			// Display the links to the general feeds: Post and Comment Feed
			remove_action( 'wp_head', 'feed_links', 2 ); 
			
			// Display the link to the Really Simple Discovery service endpoint, EditURI link
			remove_action( 'wp_head', 'rsd_link' ); 
			
			// Display the link to the Windows Live Writer manifest file.
			remove_action( 'wp_head', 'wlwmanifest_link' );
			
			// index link
			remove_action( 'wp_head', 'index_rel_link' );
			
			// prev link
			remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
			
			// start link
			remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
			
			// Display relational links for the posts adjacent to the current post.
			remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
			
			// Remove generator tag
			remove_action( 'wp_head', 'wp_generator' );

			// Remove emoji related scripts
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			
			// De-register footer scripts
			add_action( 'wp_footer', array( $this, 'deregister_footer_scripts' ) );
			
			// Remove core scripts
			add_action( 'wp_default_scripts', array( $this, 'remove_jquery_migrate' ) );
			
			// Remove woocommerce scripts on unnecessary pages
			//add_filter( 'wp_enqueue_scripts', array( $this, 'woocommerce_remove_scripts' ), PHP_INT_MAX );

			// Remove woocommerce styles, then add woo styles back in on woo-related pages
			add_action( 'wp_enqueue_scripts', array( $this, 'de_enqueue_styles' ), 100 );
			add_action( 'init', array( $this, 'deregister_styles' ), 100 );

			// Remove woocommerce generator tag
			add_action( 'wp_enqueue_scripts', array( $this, 'remove_woocommerce_generator_tag' ), 99 );
			
		}

		/**
		 * De-register footer scripts
		 *
		 * @since 1.0.0
		 */
		public function deregister_footer_scripts() {
			wp_deregister_script( 'wp-embed' );
		}
		
		/**
		 * De-enqueue styles
		 *
		 * @since 1.0.0
		 */
		public function de_enqueue_styles() {		
		
			if (function_exists( 'is_woocommerce' )) {
				wp_dequeue_style('wc-blocks-style');
				wp_dequeue_style('wc-blocks-vendors-style');
				wp_dequeue_style('woocommerce-layout');
				wp_dequeue_style('woocommerce-smallscreen');
				wp_dequeue_style('woocommerce-general');
			}

			if( ! is_page() ) {
				wp_dequeue_style('wp-block-library');
				wp_dequeue_style('global-styles');
			}
		}

		/**
		 * De-register styles
		 * 
		 * @since 1.0.0
		 */
		public function deregister_styles() {
			if (function_exists( 'is_woocommerce' )) {
				wp_deregister_style('wc-blocks-style');
				wp_deregister_style('wc-blocks-vendors-style');
			}
		}
		
		/**
		 * Remove core scripts
		 *
		 * @since 1.0.0
		 */
		function remove_jquery_migrate($scripts) {
			
			if ( !is_admin() && isset($scripts->registered['jquery']) ) {
				$script = $scripts->registered['jquery'];

				if ($script->deps) { // Check whether the script has any dependencies
					$script->deps = array_diff($script->deps, array(
						'jquery-migrate'
					));
				}
			}
			
		}
		
		/**
		 * Remove woocommerce scripts on non woocommerce pages
		 *
		 * @since 1.0.0
		 */
		public function woocommerce_remove_scripts() {
			if (function_exists( 'is_woocommerce' )) {
			
				if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page() ) { // if we're not on a Woocommerce page, dequeue all of these scripts
					wp_dequeue_script('wc-add-to-cart');
					wp_deregister_script('wc-add-to-cart');
					
					wp_dequeue_script('jquery-blockui'); 
					//wp_deregister_script('jquery-blockui');
					
					wp_dequeue_script('jquery-placeholder');
					//wp_deregister_script('jquery-placeholder');
					
					//wp_dequeue_script('woocommerce'); // removes jquery library too if activated
					
					wp_dequeue_script('jquery-cookie');
					wp_deregister_script('jquery-cookie');
					
					wp_dequeue_script('wc-cart-fragments');
					wp_deregister_script('wc-cart-fragments');
			  	}
			
			}
		}
		
		/**
		 * Remove woocommerce generator tag
		 *
		 * @since 1.0.0
		 */
		public function remove_woocommerce_generator_tag() {
			if (function_exists( 'is_woocommerce' )) {
				if (!is_woocommerce()) { // if we're not on a woo page, remove the generator tag
					remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
				}
			}
		}
		
	}
}