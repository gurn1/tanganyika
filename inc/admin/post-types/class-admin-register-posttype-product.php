<?php
/**
 * Post Type - Product
 * Added by woocommerce plugin
 * This file makes changes to the existing post type
 *
 * @since 1.0.0
 */

// Exit if accessed directly
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_admin_register_posttype_product') ) {
	class Raven_admin_register_posttype_product {
		
		private $name = '';
		private $ID = '';
		private $taxonomy_id = '';
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct(  ) {
			
			$this->ID 				= 'product';
			$this->taxonomy_id		= 'product_archive'; 
			
			// Register Custom Post Type
			// Registered with woocommerce - 'product';

			// Register taxonomies
			//add_action( 'init', array( $this, 'register_taxonomy' ) );
		
			// Products list page filter
			//add_filter( 'woocommerce_product_filters', array( $this, 'product_admin_filter' ), 10, 1 );

			// Admin Columns
			//add_filter( 'manage_'.$this->ID.'_posts_columns', array( $this, 'add_table_head' ) );
			//add_action( 'manage_'.$this->ID.'_posts_custom_column', array( $this, 'add_table_content'), 10, 2 );
			
			// Register post statuses
			add_action('init', array( $this, 'register_post_status' ) );
			
			// Add post statuses to admin drop down
			add_action('admin_footer-post.php', array( $this, 'admin_post_status_dropdown' ) );

			// Register other scripts 
			add_action( 'add_meta_boxes', array( $this, 'meta_boxes' ) );

			//  Remove archived items from search
			//add_action( 'pre_get_posts', array( $this, 'remove_archived_products_from_search' ) );
			
			// Save meta fields
			add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
				
		}
		
		
		/**
		 * Register taxonomies
		 *
		 * @since 1.0.0
		 */
		public function register_taxonomy() {
			
			/*
			$labels = array(
				'name'              => esc_html_x( 'Archives', 			RAVEN_DOMAIN ),
				'singular_name'     => esc_html_x( 'Archive', 				RAVEN_DOMAIN ),
				'search_items'      => esc_html__( 'Search Archives', 	RAVEN_DOMAIN ),
				'all_items'         => esc_html__( 'All Archives', 		RAVEN_DOMAIN ),
				'parent_item'       => esc_html__( 'Parent Archive',		RAVEN_DOMAIN ),
				'parent_item_colon' => esc_html__( 'Parent Archive:', 		RAVEN_DOMAIN ),
				'edit_item'         => esc_html__( 'Edit Archive', 		RAVEN_DOMAIN ),
				'update_item'       => esc_html__( 'Update Archive', 		RAVEN_DOMAIN ),
				'add_new_item'      => esc_html__( 'Add Archive', 			RAVEN_DOMAIN ),
				'new_item_name'     => esc_html__( 'New Archive Name', 	RAVEN_DOMAIN ),
				'menu_name'         => esc_html__( 'Archives', 			RAVEN_DOMAIN ),
				'view_item'         => esc_html__( 'View Archive', 			RAVEN_DOMAIN ),
				'popular_items'     => esc_html__( 'Popular Archives', 		RAVEN_DOMAIN ),
			);

			register_taxonomy( $this->taxonomy_id, array( $this->ID ), array(
				'labels'            => $labels,
				'hierarchical'      => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'show_ui'           => true,
				'rewrite'           => true,
			) );
			*/
			
		}
		
		
		/**
		 * Register Post status - reserved
		 *
		 * @since 2.0.0
		 */
		public function register_post_status() {

			register_post_status( 'raven_reserved', array(
				'label'          			=> _x( 'Reserved', RAVEN_DOMAIN ),
				'public'         			=> true,
				'internal'       			=> true,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'exclude_from_search'       => true,
				'label_count'    			=> _n_noop( 'Reserved <span class="count">(%s)</span>', 'Reserved <span class="count">(%s)</span>' )
			) );

			register_post_status( 'raven_sold', array(
				'label'          			=> _x( 'Sold', RAVEN_DOMAIN ),
				'public'         			=> true,
				'internal'       			=> true,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'exclude_from_search'       => true,
				'label_count'    			=> _n_noop( 'Sold <span class="count">(%s)</span>', 'Sold <span class="count">(%s)</span>' )
			) );

		}
		
		
		/**
		 * Products list page filter
		 *
		 * @since 1.0.0
		 */
		public function product_admin_filter( $output ) {
			$archived_selected = isset( $_GET['raven-archived'] ) ? wc_clean( wp_unslash( $_GET['raven-archived'] ) ) : false;
			$checked = checked( '1', $archived_selected, false );

			$output .= '<label class="raven-checkbox">Show Archived Items ';
			$output .= '<input type="checkbox" name="raven-archived" id="raven_archive" value="1" '. $checked .'/></label>';

			return $output;
		}
			
		
		/**
		 * Meta box creation
		 *
		 * @since version 1.0.0
		 */
		public function meta_boxes() {	
			add_meta_box( 'product_notes', __( 'Product Notes (private)', RAVEN_DOMAIN ), array($this, 'product_notes'), $this->ID, 'normal' );
		}
		
		
		/**
		 * Sticky box
		 *
		 * @since 1.0.0
		 */
		public function product_notes() {
			global $post;
			
			$meta = get_post_meta( $post->ID, '_raven-private-note', true );
		
			echo '<table class="form-table"><tr>';	

			// The textarea field
			echo sprintf( '<td><textarea id="raven_private_note" name="_raven-private-note" class="large-text" placeholder="Add an internal note about this product (customers cannot see this).">%s</textarea></td>', esc_textarea( $meta ) );

			echo '</tr></table>';
			
		}
		
		
		/**
		 * Remove archived items from search
		 *
		 * @since 1.0.0
		 */
		public function remove_archived_products_from_search( $query ) {

		   /*
		   if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {

			   $query->set( 'post_type', array( 'product' ) );

			   $tax_query = array(
				   array(
					   'taxonomy' => 'product_archive',
					   'operator' => 'NOT EXISTS',
				   ),
			   );

			   $query->set( 'tax_query', $tax_query );

		   }
		   */

		}
		
		
		/**
		 * Display post status in admin
		 *
		 * @since 1.0.0
		 */
		public function admin_post_status_dropdown(){
			global $post;
			
			$selected = '';
			$label = '';
			
			$statuses = array(
				'raven_reserved' => 'Reserved',
				'raven_sold' 	=> 'Sold',
			);

			if($post->post_type == 'product'){
				
				echo '<script>jQuery(document).ready(function($){';
				
				foreach( $statuses as $id => $name ) {
					
					if( $post->post_status == $id ) {
						$selected 	= ' selected=\"selected\"';
						$label 		= $name;
					}
					
					echo '
						$("select#post_status").append("<option value=\"'.$id.'\" '.$selected.'>'.$name.'</option>");
						$("#post-status-display").append("'.$label.'");
					';
					
				}
				
				echo '});</script>';
				
			}
			
		}
	
		
		/**
		 * Save Post
		 *
		 * @since 1.0.0
		 */
		public function save_post( $post_id, $post ) {
			
			// Prevent infinite loop
			if ( ! wp_is_post_revision( $post_id ) ){
				
				// Update private note
				if( isset($_POST['_ravem-private-note']) ) {
					update_post_meta( $post_id, '_raven-private-note', sanitize_text_field($_POST['_raven-private-note']) );
				}

				// Update SKU field
				if( ! get_post_meta( $post_id, '_sku', true ) ) {
					update_post_meta( $post_id, '_sku', $post_id );
				}
			
				// check if post has an archive term
				/*
				if( has_term('', $this->taxonomy_id, $post_id ) ){

					// get product categories
					$categories = get_the_terms( $post_id, 'product_cat' );

					if( $categories ) {	
						foreach( $categories as $category ) {
							$terms[] = $category->slug;
						} 

						// remove from all categories
						wp_remove_object_terms( $post_id, $terms, 'product_cat' );

					}

					
					// unhook this function so it doesn't loop infinitely
					remove_action( 'save_post', array( $this, 'save_post' ) );
					
					// Set post status to archived
					wp_update_post( array(
						'ID'			=> $post_id,
						'post_status'	=> 'raven_archived'
					) );
					
					// re-hook this function
					add_action( 'save_post', array( $this, 'save_post' ) );

				}
				*/
				
			}
		
		}
		
	}
}