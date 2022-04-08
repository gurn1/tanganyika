<?php
/**
 * Post Type loops
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_loops_controller') ) {
	
	class Raven_loops_controller {

		protected static $instance = null;
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			add_action( 'pre_get_posts', array( $this, 'product_loop_modifier' ) );
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
		 * Home latest products loop
		 *
		 * @since 1.0.0
		 */
		public function home_latest_products() {
			$args = array(
				'post_type'			=> 'product',
				'posts_per_page'	=> 12,
				'orderby'			=> 'date',
				'order'				=> 'DESC',
				'tax_query' 		=> array(
					array(
						'taxonomy' => 'product_cat',
					 	'field'    => 'slug',
					 	'terms'    => array( 'archives', 'archive' ),
					 	'operator' => 'NOT IN',
					)
				 ),
			);
			
			$products = new WP_Query($args); 

			if( $products->have_posts() ) {
				woocommerce_product_loop_start();

				while( $products->have_posts() ) {
					$products->the_post();
					wc_get_template_part( 'content', 'product' );
				}

				woocommerce_product_loop_end();
			}
		}

		/**
		 * Modify shop page 
		 * 
		 * @since 1.0.0
		 */
		public function product_loop_modifier($query) {

			if( ! $query->is_main_query() || $query->get('page_id') == get_option('page_on_front') ) {
				return;
			}

			if( ! class_exists('woocommerce') ) {
				return;
			}

			if(is_shop()) { 	
				$tax_query = array( array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => array( 'archives', 'archive' ),'operator' => 'NOT IN') );
				$query->set('tax_query', $tax_query);
			}
		}
		 
	}
	
}