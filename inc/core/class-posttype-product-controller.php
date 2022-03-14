<?php
/**
 * Post type product controller
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_posttype_product_controller') ) {
	
	class Raven_posttype_product_controller {

		protected static $instance = null;
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {}

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
		 * Get latest products
		 *
		 * @since 1.0.0
		 */
		public function latest_products() {
			$paged 		= (get_query_var('paged')) ? get_query_var('paged') : 1;
			$max_date	= date( 'Y-m-d', strtotime('-30 days') );
			
			$orderby = $this->sortby_output();

			$args = array(
				'posts_per_page'	=> get_option('posts_per_page'),
				'post_type'			=> 'product',
				'post_status'		=> array('publish'),
				'date_query'		=> array(
					array(
						'after'	=> $max_date, // 10 days
						'inclusive' => true,
					)
				),
				'orderby'			=> $orderby['orderby'],
				'meta_key'			=> $orderby['meta_key'],
				'order'				=> $orderby['order'],
				'paged' 			=> $paged
			);

			$products = new WP_Query( $args );

			if( $products->have_posts() ) {

				woocommerce_output_all_notices();
				
				echo '<div class="loop-top-bar container">';
				
				$this->result_count($products);
				$this->new_arrivals_ordering($products);
			
				echo '</div>';

				woocommerce_product_loop_start();

				while( $products->have_posts() ) {
					$products->the_post();
					wc_get_template_part( 'content', 'product' );
				}

				woocommerce_product_loop_end();
				
				// Pagination
				$this->pagination($products);

			}else{
				/**
				 * Hook: woocommerce_no_products_found.
				 *
				 * @hooked wc_no_products_found - 10
				 */
				do_action( 'woocommerce_no_products_found' );
			}

			wp_reset_postdata();
		}
		
		/**
		 * Product pagination 
		 *
		 * @since 1.0.0
		 */
		public function pagination($query) {

			// Create the variables
			$total = $query->max_num_pages;

			// Only bother with the rest if we have more than 1 page!
			if ( $total > 1 )  {
				
				// get the current page
				if ( !$current_page = get_query_var('paged') ) {
					  $current_page = 1;
				}
				 
				$format = 'page/%#%/';
				$base = preg_replace('/\?.*/', '/', get_pagenum_link(1)) . '%_%';
				
				$args = array(
					'total'   => $total,
					'current' => $current_page,
					'base'    => $base,
					'format'  => $format,
				);
				
				wc_get_template( 'loop/pagination.php', $args );
				
			}
			
						
		}
		
		/**
		 * Output the result count text (Showing x - x of x results).
		 *
		 * @since 1.0.0
		 */
		public function result_count($query) {
			if ( ! $query->have_posts() ) {
				return;
			}
			
			// get the current page
			if ( !$current_page = get_query_var('paged') ) {
				  $current_page = 1;
			}
			
			$args = array(
				'total'    => $query->found_posts,
				'per_page' => get_option('posts_per_page'),
				'current'  => $current_page,
			);

			wc_get_template( 'loop/result-count.php', $args );
		}
		
		/**
		 * Sortby dropdown output
		 *
		 * @since 1.0.0
		 */
		public function sortby_output() {

			// Default order
			$meta_key 	= '';
			$orderby 	= 'date';
			$order 		= 'DESC';

			// Sort by
			if(isset($_GET['orderby'])){

				switch($_GET['orderby']){
					case('a-z'):
						$orderby 	= 'title';
						$order 		= 'ASC';
						break;
					case('z-a'):
						$orderby 	= 'title';
						$order 		= 'DESC';
						break;
					case('date'):
						$orderby 	= 'date';
						$order 		= 'DESC';
						break;
					case('price') :
						$meta_key 	= '_price';
						$orderby 	= 'meta_value_num';
						$order 		= 'ASC';
						break;
					case('price-desc') :
						$meta_key 	= '_price';
						$orderby 	= 'meta_value_num';
						$order 		= 'DESC';
						break;
				}

			}

			return array( 'meta_key' => $meta_key, 'orderby' => $orderby, 'order' => $order );

		}

		/**
		 * Output the product sorting options for new arrivals loop.
		 *
		 * @since 1.0.0
		 */
		public function new_arrivals_ordering() {
			
			$show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
			$catalog_orderby_options = apply_filters(
				'woocommerce_catalog_orderby',
				array(
					'menu_order' => __( 'Default sorting', 'woocommerce' ),
					'popularity' => __( 'Sort by popularity', 'woocommerce' ),
					'rating'     => __( 'Sort by average rating', 'woocommerce' ),
					'date'       => __( 'Sort by latest', 'woocommerce' ),
					'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
					'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
				)
			);

			$default_orderby = wc_get_loop_prop( 'is_search' ) ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
			$orderby         = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby; // WPCS: sanitization ok, input var ok, CSRF ok.

			if ( wc_get_loop_prop( 'is_search' ) ) {
				$catalog_orderby_options = array_merge( array( 'relevance' => __( 'Relevance', 'woocommerce' ) ), $catalog_orderby_options );

				unset( $catalog_orderby_options['menu_order'] );
			}

			if ( ! $show_default_orderby ) {
				unset( $catalog_orderby_options['menu_order'] );
			}

			if ( ! wc_review_ratings_enabled() ) {
				unset( $catalog_orderby_options['rating'] );
			}

			if ( ! array_key_exists( $orderby, $catalog_orderby_options ) ) {
				$orderby = current( array_keys( $catalog_orderby_options ) );
			}

			wc_get_template(
				'loop/orderby.php',
				array(
					'catalog_orderby_options' => $catalog_orderby_options,
					'orderby'                 => $orderby,
					'show_default_orderby'    => $show_default_orderby,
				)
			);
		}
		
	}
}