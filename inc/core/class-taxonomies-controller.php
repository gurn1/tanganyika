<?php
/**
 * Taxonomies controller
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_taxonomies_controller') ) {
	
	class Raven_taxonomies_controller {
		
		protected static $instance = null;
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {			
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
		 * Order the terms by custom meta data
		 *
		 * @since 1.0.0
		 */
		public function order_terms($object) {	
			if( ! is_wp_error($object) ) {
				foreach( $object as $term ){
					$order = get_term_meta( $term->term_id, '_product_cat_order', true );
					// if there is no order number set, use term slug instead.
					if( ! $order ){
						$order = $term->slug;
					}
					$output[$order] = $term;
				}
				ksort($output);
				return $output;
			}
		}

		/**
		 * Get child terms and sort them by menu order (custom meta data)
		 *
		 * @since 1.0.0
		 */
		public function get_child_terms( $terms_id ) {
			if( $terms_id ) {
				foreach( $terms_id as $id ) {
					$term = get_term( $id );
					$output[] = $term; 
				}
				return $this->order_terms( $output );
			}
		}

		/** 
		 * sort the shop categories by term meta
		 *
		 * @since 1.0.0
		 */
		public function sort_shop_categories($taxonomy = 'product_cat') {
			$categories = get_terms( array(
				'taxonomy'			=> $taxonomy,
				'hide_empty' 		=> false,
				'parent'			=> 0,
				'include_children' 	=> false
			) );

				
			return ($this->order_terms($categories) != null) ? $this->order_terms($categories) : array();
		}

		/**
		 * Display shop front categories
		 *
		 * @since 1.0.0
		 */
		public function display_product_categories($cols = 1) {
			$categories = $this->sort_shop_categories();
			
			if( $categories ) {
				$count = count($categories);
				$columns = $count / $cols;
				$columns = ceil($columns);
				$num = new NumberFormatter("en", NumberFormatter::SPELLOUT);
		
				$ul = '<ul class="product-categories-section">';

				echo sprintf('<div class="%s-columns list-category-products">', $num->format($cols));
				echo $ul;

				$i = 0;
				foreach( $categories as $category ) {
					if( $i % $columns == 0 && $i > 1) {
						echo '</ul>'.$ul;
					}
					/**
					 * Get template
					 * params: $terms
					 *
					 * @since 1.0.0
					 */
					get_template_part( 'template-parts/content/content', 'product-categories', $category );	
					$i++;			
				}
				echo '</ul></div>';
			}
		}

		/**
		 * Display category child terms
		 *
		 * @since 1.0.0
		 */
		public function display_child_categories($term_id = null) {
			if(! $term_id ) {
				$term_id = isset(get_queried_object()->term_id) ? get_queried_object()->term_id : '';
			}

			$terms = get_terms( array(
				'taxonomy'			=> 'product_cat',
				'hide_empty' 		=> false,
				'parent'			=> $term_id,
			) );
			$children = $this->get_child_terms($terms);

			if( $children ) {
				echo sprintf('<ul id="term_child_categories_%s" class="product-categories sub-menu">', $term_id );
				foreach( $children as $term ) {
					echo sprintf(
						'<li class="child-item child-item-%1$s"><a href="%2$s" aria-title="%3$s">%3$s</a></li>',
						$term->term_id, get_term_link($term->term_id), $term->name
					);
				}
				echo '</ul>';
			}
			
		}

		/**
		 * Count category children 
		 * 
		 * @since 1.0.0
		 */
		public function get_category_children_count() {
			global $wp_query;

			$term_id = isset(get_queried_object()->term_id) ? get_queried_object()->term_id : '';
			$child_terms 	= get_term_children( $term_id, 'product_cat' );

			if($child_terms) {
				return count($child_terms);
			}

		}
	
		/**
		 * Display category children on product page
		 * 
		 * @since 1.0.0
		 */
		public function get_category_children() {
			global $wp_query;

			$term_id = isset(get_queried_object()->term_id) ? get_queried_object()->term_id : '';
			$child_terms 	= get_term_children( $term_id, 'product_cat' );
	
			if( ! $child_terms ) {
				return;
			}

			$count = count($child_terms);
			$cols = '';

			switch($count) {
				case '1' :
					$cols = 'one-col';
					break;
				case '2' :
					$cols = 'two-cols';
					break;
				case '3' :
					$cols = 'three-cols';
					break;
				default:
					$cols = 'four-cols';
			}
			
			//include_once AB_DIR . 'inc/core/class-product-cat-list-walker-controller.php';

			$list_args = array(
				//'walker'	               		=> new AB_product_cat_list_walker(),
				'hierarchical' 					=> true,
				'taxonomy'     					=> 'product_cat',
				'orderby' 	   					=> 'meta_value_num',
				'order' 	   					=> 'ASC',
				'hide_empty'   					=> 0,
				'meta_query'   					=> [[
					'key' 	=> '_product_cat_order',
					'type' 	=> 'NUMERIC',
				]],
				'title_li'						=> '',
				'pad_counts'					=> 1,
				'current_category'				=> $term_id,
				'child_of'					 	=> $term_id ,
				'depth' 			      		=> 1
			);

			echo '<h3 class="child-header">Sub Categories</h3>';
			
			echo '<ul class="child-product-categories '.$cols.'">';

			wp_list_categories( $list_args );

			echo '</ul>';
		}
		
	}

}