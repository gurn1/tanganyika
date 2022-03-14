<?php
/**
 * Media controller
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_media_controller') ) {
	
	class Raven_media_controller {

		protected static $instance = null;
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			
			// Clean up product data - types
			add_action( 'init', array( $this, 'remove_image_sizes' ) );
			
			// Create image sizes
			add_action( 'init', array( $this, 'create_image_sizes' ) );

			// Remove images when a product is removed
			add_action( 'before_delete_post', array( $this, 'delete_product_images' ), 10, 1 );
			
			// Allow SVG in admin upload
			add_filter('upload_mimes', array( $this, 'admin_allow_svg'));

		}	

		/**
		 * Instance
		 *
		 * @since 1.0.0
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
		
		/**
		 * Remove unwanted image sizes
		 *
		 * @since 1.0.0
		 */
		public function remove_image_sizes() {
			
			$unwanted_image_sizes = array(
				'medium_large',
				'woocommerce_thumbnail',
				'woocommerce_gallery_thumbnail',
				'woocommerce_single',
				'shop_catalog'
			);
			
			foreach( $unwanted_image_sizes as $size ) {
				remove_image_size($size);
			}
		}
		
		/**
		 * Create image sizes
		 *
		 * @since 3.1.0
		 */
		public function create_image_sizes() {
			
			// Basic thumbnail
			add_image_size( 'raven_basic_thumb', 500, 550, true );
		}

		/**
		 * Remove images when a product is removed
		 *
		 * @since 1.0.0
		 */
		public function delete_product_images( $post_id ) {
			
			if( class_exists( 'woocommerce' ) ) {
				$product = wc_get_product( $post_id );

				if ( !$product ) {
					return;
				}

				$featured_image_id = $product->get_image_id();
				$image_galleries_id = $product->get_gallery_image_ids();

				if( !empty( $featured_image_id ) ) {
					wp_delete_post( $featured_image_id );
				}

				if( !empty( $image_galleries_id ) ) {
					foreach( $image_galleries_id as $single_image_id ) {
						wp_delete_post( $single_image_id );
					}
				}
			}
			
		}

		/**
		 * Allow SVG in admin upload
		 *
		 * @since 1.0.0
		 */
		public function admin_allow_svg($mimes) {
			$mimes['svg'] = 'image/svg+xml';

			return $mimes;
		}
		 
	}
}