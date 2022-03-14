<?php
/**
 * Add on to the woocommerce product category taxonomy
 *
 * @since 1.0.0
 */
 
/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_admin_register_taxonomy_product') ) {
	class Raven_admin_register_taxonomy_product {
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			
			// Taxonomy meta fields
			add_action( 'product_cat_add_form_fields', array( $this, 'tax_meta_fields' ), 10, 2);
			add_action( 'product_cat_edit_form_fields', array( $this, 'edit_tax_meta_fields' ), 10 );
			add_action( 'edited_product_cat', array( $this, 'save_tax_meta_fields' ) );
			add_action( 'created_product_cat', array( $this, 'save_tax_meta_fields' ) );
			
			add_filter( 'manage_edit-product_cat_columns', array( $this, 'orderby_column_header'), 10, 1 );
			add_action( 'manage_product_cat_custom_column',  array( $this, 'orderby_field'), 10, 3 );
			
			add_filter( 'manage_edit-product_cat_sortable_columns', array( $this, 'register_date_column_for_issues_sortable' ) );
			add_filter( 'pre_get_terms', array( $this,'taxonomy__orderby' ) );
		}
		
		
		/**
		  * Add supplier taxonomy meta fields
		  *
		  * @since 1.0.0
		  */
		public function tax_meta_fields( $term ) {
			global $wv_helper_fields;
			
			// Logo
			echo '<div><label>Order</label>';
			echo '<input type="text" name="_product_cat_order">';
			echo '</div>';
		}


		/**
		 * Add inputs to tax edit page
		 *
		 * @since 1.0.0
		 */
		public function edit_tax_meta_fields( $term ) {
			global $wv_helper_fields;
			
			$term_meta = get_term_meta( $term->term_id, '_product_cat_order', true);
			?>

			<tr class="form-field">


				<th scope="row" valign="top"><label>Order</label></th>
				<td>
					<input type="text" name="_product_cat_order" value="<?php echo $term_meta; ?>">
				</td>

			</tr>

			<?php
		}
		
		
		/**
		 * Order by column header
		 *
		 * @since 1.0.0
		 */
		public function orderby_column_header($columns) {

			$columns['orderby'] = 'Orderby';

			return $columns;

		}


		/**
		 * order by column field
		 *
		 * @since 1.0.0
		 */
		public function orderby_field($empty = '', $custom_column, $term_id) {

			if ( $custom_column == 'orderby' ) {

				 echo get_term_meta( $term_id, '_product_cat_order', true );

			}

		}
	

		/**
		 * Save custom meta
		 *
		 * @since 1.0.0
		 */
		public function save_tax_meta_fields( $term_id ) {

			if( isset( $_POST['_product_cat_order'] ) ) {

				// Update term field
				update_term_meta( $term_id, '_product_cat_order', sanitize_text_field($_POST['_product_cat_order']) );

			}

		}
		
		
		/**
		 * Register for sortable
		 *
		 * @since 1.0.0
		 */
		public function register_date_column_for_issues_sortable($columns) {
		  	$columns['orderby'] = '_product_cat_order';
		  	
			return $columns;
		}
		
		
		/**
		 * Make Sortable
		 *
		 * @since 1.0.0
		 */
		public function taxonomy__orderby( $term_query ) {
			global $pagenow;
			
			if(!is_admin()) {
				return $term_query;
			}
			
			// WP_Term_Query does not define a get() or a set() method so the query_vars member must
			// be manipulated directly
			if(is_admin() && $pagenow == 'edit-tags.php' && $term_query->query_vars['taxonomy'][0] == 'product_cat' && (!isset($_GET['orderby']) || $_GET['orderby'] == '_product_cat_order')) {
				// set orderby to the named clause in the meta_query
				$term_query->query_vars['orderby'] = '_product_cat_order';
				$term_query->query_vars['order'] = isset($_GET['order']) ? $_GET['order'] : "DESC";
				// the OR relation and the NOT EXISTS clause allow for terms without a meta_value at all
				$args = array('relation' => 'OR',
				  'order_clause' => array(
					'key' => '_product_cat_order',
					'type' => 'NUMERIC'
				  ),
				  array(
					'key' => '_product_cat_order',
					'compare' => 'NOT EXISTS'
				  )
				);
				$term_query->meta_query = new WP_Meta_Query( $args );
			}
			
			return $term_query;
		}

	}

}