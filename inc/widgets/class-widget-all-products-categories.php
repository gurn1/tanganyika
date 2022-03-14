<?php
/**
 * Product Categories Widget - excluding current category
 *
 * @version 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( class_exists('WC_Widget') ) {
	/**
	 * Product categories widget class.
	 *
	 * @extends WC_Widget
	 */
	class raven_widget_all_product_categories extends WC_Widget {

		/**
		 * Category ancestors.
		 *
		 * @var array
		 */
		public $cat_ancestors;

		/**
		 * Current Category.
		 *
		 * @var bool
		 */
		public $current_cat;

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->widget_cssclass    = 'woocommerce widget_product_categories raven-all-product-categories raven-product-categories';
			$this->widget_description = __( 'A list or dropdown of all product categories.', RAVEN_DOMAIN );
			$this->widget_id          = 'raven_all_product_categories';
			$this->widget_name        = __( 'Raven Product Categories', RAVEN_DOMAIN );
			$this->settings           = array(
				'title'              => array(
					'type'  => 'text',
					'std'   => __( 'Product categories', RAVEN_DOMAIN ),
					'label' => __( 'Title', RAVEN_DOMAIN ),
				),
				'count'              => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => __( 'Show product counts', RAVEN_DOMAIN ),
				),
				'hide_empty'         => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => __( 'Hide empty categories', RAVEN_DOMAIN ),
				),
				'max_depth'          => array(
					'type'  => 'text',
					'std'   => '',
					'label' => __( 'Maximum depth', RAVEN_DOMAIN ),
				),
			);

			parent::__construct();
		}

		/**
		 * Output widget.
		 *
		 * @see WP_Widget
		 * @param array $args     Widget arguments.
		 * @param array $instance Widget instance.
		 */
		public function widget( $args, $instance ) {
			global $wp_query, $post;

			$count              = isset( $instance['count'] ) ? $instance['count'] : $this->settings['count']['std'];
			$hide_empty         = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : $this->settings['hide_empty']['std'];
			$list_args          = array(
				'show_count'   => $count,
				'hierarchical' => true,
				'taxonomy'     => 'product_cat',
				'orderby' 	   => 'meta_value_num',
				'order' 	   => 'ASC',
				'hide_empty'   => $hide_empty,
				'meta_query'   => [[
					'key' 	=> '_product_cat_order',
					'type' 	=> 'NUMERIC',
				]],
			);
			$max_depth          = absint( isset( $instance['max_depth'] ) ? $instance['max_depth'] : $this->settings['max_depth']['std'] );

			$this->current_cat   = false;
			$this->cat_ancestors = array();

			if( is_tax( 'product_cat' ) ) {
				$this->current_cat   = $wp_query->queried_object;
				$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );

			}
			
			$this->widget_start( $args, $instance );
			
			//include_once RAVEN_DIR . 'inc/core/class-product-cat-list-walker-controller.php';

			//$list_args['walker']                     = new raven_product_cat_list_walker();
			$list_args['title_li']                   = '';
			$list_args['pad_counts']                 = 1;
			$list_args['show_option_none']           = __( 'No product categories exist.', 'woocommerce' );
			$list_args['current_category']           = ( $this->current_cat ) ? $this->current_cat->term_id : '';
			$list_args['current_category_ancestors'] = $this->cat_ancestors;
			$list_args['max_depth']                  = $max_depth;

			echo '<ul class="all-product-categories">';

			wp_list_categories( $list_args );

			echo '</ul>';

			$this->widget_end( $args );
		}

	}
}