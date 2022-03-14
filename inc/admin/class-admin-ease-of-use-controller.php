<?php
/**
 * Ease of use controller
 * used in admin to tidy up unneeded settings, making for a smoother operating experience. 
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_admin_ease_of_use_controller') ) {
	class Raven_admin_ease_of_use_controller {
		
		public static $ID = 'raven_theme_easeofuse';
		
		public static $title	= 'Ease of Use';
		
		public static $name 	= 'ease-of-use-options';
		
		static $data = array(
			'product-types'		=> array(
				'virtual' 			=> array( 'name' => 'Virtual', 				'field' => 'checkbox' ), 
				'downloadable' 		=> array( 'name' => 'Downloadable', 		'field' => 'checkbox' ), 
				'grouped' 			=> array( 'name' => 'Grouped Product', 		'field' => 'checkbox' ),
				'external'			=> array( 'name' => 'External Product', 	'field' => 'checkbox' ),
				'variable'			=> array( 'name' => 'Variable Product', 	'field' => 'checkbox' ),
			),
			'product-tabs'		=> array(
				'linked_product'	=> array( 'name' => 'Linked Products', 		'field' => 'checkbox' ),
				'attribute'			=> array( 'name' => 'Attribute Product', 	'field' => 'checkbox' ),
			),
			'metaboxes'			=> array(
				'product_tabs'		=> array( 'name' => 'Product Tabs', 		'field' => 'checkbox' ), 
			),
			'excluded-pages'	=> array(
				'excluded_pages'	=> array( 'name' => 'Pages to exclude from Site Manager', 'field' => 'textarea' )
			)
		);
		
		public $values;
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			
			// Clean up product data - types
			add_action('product_type_options', array( $this, 'woocommerce_product_types' ) );
			
			// Clean up product data - selector
			add_action('product_type_selector', array( $this, 'woocommerce_product_selector' ) );
			
			// Clean up product data - tabs
			add_filter('woocommerce_product_data_tabs', array( $this, 'woocommerce_product_tabs' ), 10, 1 );
			
			// Clean up product data - inventory
			add_action('admin_footer', array( $this, 'woocommerce_stock_quantity' ) );
			
			// Remove product tabs metabox
			add_action('add_meta_boxes', array( $this, 'woocommerce_product_tags_metabox' ), 50 );
			
			// Remove the connect to woocommerce 
			add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );
			
			// Exclude dummy pages from site manager
			add_filter( 'parse_query', array( $this, 'exclude_pages_from_site_manager' ) );
			
			$this->values = get_option(self::$ID);

			$this->register_admin_options();
		}	

		/**
		 * Register Admin options
		 * 
		 * @since 1.0.0
		 */
		protected function register_admin_options() {
			if( is_admin() ){
				new Raven_admin_register_option('themes.php', self::$ID, self::$title, self::$name, self::$data);
			}
		}
		
		
		/**
		 * Woocommerce clean up product data - types
		 * removes vitual and downloadable options
		 *
		 * @since 1.0.0
		 */
		public function woocommerce_product_types( $types ) {
			
			$value = $this->values;
			
			if( isset($value['virtual']) == 'on' ) {
				unset( $types['virtual'] );
			}
			
			if( isset($value['downloadable']) == 'on' ) {
				unset( $types['downloadable'] );
			}
			
			return $types;
		}
		
		
		/**
		 * Woocommerce clean up product data - type selector
		 * removes grouped, external, and variable options
		 *
		 * @since 1.0.0
		 */
		public function woocommerce_product_selector( $types ) {
			
			$value = $this->values;
			
			if( isset($value['grouped']) == 'on' ) {
				unset( $types['grouped'] );
			}
			
			if( isset($value['external']) == 'on' ) {
				unset( $types['external'] );
			}
			
			if( isset($value['variable']) == 'on' ) {
				unset( $types['variable'] );
			}
			
			return $types;
		}
		
		
		/**
		 * Woocommerce clean up prpduct data - tabs 
		 * removes linked products, and attributes
		 *
		 * @since 1.0.0
		 */
		public function woocommerce_product_tabs( $tabs ) {
			
			$value = $this->values;
			
			if( isset($value['linked_product']) == 'on' ) {
				unset( $tabs['linked_product'] );
			}
			
			if( isset($value['attribute']) == 'on' ) {
				unset( $tabs['attribute'] );	
			}
			
			return $tabs;
		}
		
		
		/**
		 * Woocommerce clean up product data - inventory
		 * remove backorders, low stock notice, & stock status fields
		 * Set stock management to checked, add stock quantatiy to 1, and set sold individually to checked.
		 *
		 * @since 1.0.0
		 */
		public function woocommerce_stock_quantity() {
		  	global $pagenow, $post;

		  	$default_stock_quantity = 1;
		  	$screen = get_current_screen();
			//$post_status = get_post_status( $post->ID );
			
			if ( ( $pagenow == 'post-new.php' || $pagenow == 'post.php' || $pagenow == 'edit.php' ) && $screen->post_type == 'product' ) :

			?>

				<script type="text/javascript">
				jQuery(document).ready(function($){
					var stockCheckbox 	 = $('#_manage_stock'),
						hiddenFields	 = $('.stock_fields'),
						stockQuantity	 = $('#_stock'),
						soldIndividually = $('#_sold_individually');

					if( ! stockCheckbox.attr('checked') ) {

						// Mark stock management box as checked
						stockCheckbox.attr('checked', 'checked');

						// Show hidden stock management fields
						if( stockCheckbox.prop("checked") == true ) {
							hiddenFields.css('display', 'block');
						}
					}

					// set quantity to 1
					if( 0 == stockQuantity.val() && '<?php echo $pagenow; ?>' == 'post-new.php' ) {
						stockQuantity.val(<?php echo $default_stock_quantity; ?>);
					}

					// Mark the sold individually box as checked
					if( ! soldIndividually.attr('checked') ) {
						soldIndividually.attr('checked', 'checked');
					}

				});
				</script>

				<style type="text/css">
					._backorders_field, 
					._low_stock_amount_field, 
					._stock_status_field,
					._manage_stock_field,
					._sold_individually_field {
						display: none !important;
					}
				</style>

			<?php
			
		  	endif;
			
		}
			
		
		/**
		 * Woocommerce remove product tags metabox
		 *
		 * @since 1.0.0
		 */
		public function woocommerce_product_tags_metabox() {
			
			$value = $this->values;
			
			if( isset($value['product_tabs']) == 'on' ) {
				remove_meta_box( 'tagsdiv-product_tag', 'product', 'side' );
			}
			
		}
		
		
		/** 
		 * Exclude dummy pages from site manager
		 *
		 * @since 1.0.0
		 */
		function exclude_pages_from_site_manager( $query ) {
			if ( ! is_admin() ) {
				return $query;
			}

			global $pagenow, $post_type;

			$page_ids = array();
			$pages = isset($this->values['excluded_pages']) ? explode(',', $this->values['excluded_pages']) : '';

			if ( ! current_user_can( 'administrator' ) && $pagenow == 'edit.php' && $post_type == 'page' && is_array($pages) ) {
				foreach($pages as $page) {
					if( ! is_numeric($page) ) {
						$obj = get_page_by_path($page, OBJECT, 'page');
						if( is_object($obj) ) {
							$page_ids[] = $obj->ID;
						}
					} else {
						$page_ids[] = $page;
					}
				}

				$query->query_vars['post__not_in'] = $page_ids;
			}
		} 
		 
	}
}