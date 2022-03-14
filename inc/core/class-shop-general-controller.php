<?php
/**
 * Shop controller
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_shop_general_controller') ) {
	
	class Raven_shop_general_controller {
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			// Declare support for Woocommerce
			add_action( 'after_setup_theme', array( $this, 'woocommerce_support' ) );
			
			// Default sort order
			add_filter('woocommerce_default_catalog_orderby', array( $this, 'default_order' ) );
			
			// Remove woocommerce breadcrumbs
			add_action( 'init', array( $this, 'remove_wc_breadcrumbs' ) );
			
			// Loops per page
			add_filter( 'loop_shop_per_page', array( $this, 'products_per_page'), 20 );
			
			add_filter( 'body_class', array( $this, 'woocommerce_home_body_class' ) );
			
			// Change place order button html
			add_filter( 'woocommerce_order_button_html', array( $this, 'woocommerce_place_order' ), 10, 1 );
			
		}
		
		
		/**
		 * Declare support for Woocommerce
		 *
		 * @since 1.0.0
		 */
		public function woocommerce_support() {
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
		}
		
		
		/**
		 * Default sort order
		 *
		 * @since 3.1.0
		 */
		public function default_order() {
			return 'date'; // Can also use title and price
		}
		
		
		/**
		 * Remove the breadcrumbs - we'll let yoast handle them
		 *
		 * @since 1.0.0
		 */
		public function remove_wc_breadcrumbs() {
			//remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
		}
		
		
		/**
		 * Change number of products that are displayed per page (shop page)
		 *
		 * @since 1.0.0
		 */
		public function products_per_page( $cols ) {
			// $cols contains the current number of products per page based on the value stored on Options -> Reading
		  	// Return the number of products you wanna show per page.
		  	$cols = get_option('posts_per_page');
			
		 	return $cols;
		}
		
		
		/**
		 * Add class to home page body tag
		 *
		 * @since 1.0.0
		 */
		public function woocommerce_home_body_class( $classes ) {
			if( class_exists( 'woocommerce' ) && is_front_page() ) {
				return array_merge( $classes, array('woocommerce') );
			} else {
				return $classes;
			}					   
		}
		
		
		/**
		 * Place order button
		 *
		 * @since 1.0.0
		 */
		public function woocommerce_place_order($order_button_html) {
			$order_button_text = 'Place Order';
			
			return '<button type="submit" class="button large alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>';
		}
		
	}

}