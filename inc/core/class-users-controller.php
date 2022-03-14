<?php
/**
 * User roles
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_users_controller') ) {
	
	class Raven_users_controller {
		
		public $options;

		protected static $instance = null;
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			// Register user roles
			add_action('init', array($this, 'users') );
			
			// Admin capabilities
			add_action( 'admin_init', array( $this, 'admin_capabilities') );
			
			// Change default name on comments fields
			add_filter( 'get_comment_author', array( $this, 'comments_name' ), 10, 2 );
			
			// Validate extra register fields
			//add_action( 'woocommerce_register_post', array( $this, 'registration_error_validation' ), 10, 3 );
			
			// Save the extra register fields.
			add_action( 'woocommerce_created_customer', array( $this, 'save_extra_register_fields' ) );

			// Check password renawal
			add_action( 'wp_ajax_password_renewal_checker', array( $this, 'password_renewal_checker' ) );
			add_action( 'wp_ajax_nopriv_password_renewal_checker', array( $this, 'password_renewal_checker' ) );
			
			// Check to see if user has updated their password
			add_action( 'after_password_reset', array( $this, 'add_meta_password_updated' ), 10 );
			add_action( 'woocommerce_customer_reset_password', array( $this, 'add_meta_password_updated' ), 10 );
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
	
		public function users() {
			
			// Site Owner
			if( ! get_role('site_management') ){

				$args = array ( 
					'administrator' => false, 
					'assign_product_terms' => true, 
					'assign_shop_coupon_terms' => true, 
					'assign_shop_order_terms' => true, 
					'assign_shop_webhook_terms' => true, 
					'create_pages' => true, 
					'create_posts' => true, 
					'create_users' => true, 
					'delete_others_pages' => true, 
					'delete_others_posts' => true, 
					'delete_others_products' => true, 
					'delete_others_shop_coupons' => true,
					'delete_others_shop_orders' => true, 
					'delete_others_shop_webhooks' => true, 
					'delete_pages' => true, 
					'delete_posts' => true, 
					'delete_private_pages' => true, 
					'delete_private_posts' => true, 
					'delete_private_products' => true, 
					'delete_private_shop_coupons' => true, 
					'delete_private_shop_orders' => true, 
					'delete_private_shop_webhooks' => true, 
					'delete_product' => true, 
					'delete_product_terms' => true, 
					'delete_products' => true, 
					'delete_published_pages' => true, 
					'delete_published_posts' => true, 
					'delete_published_products' => true, 
					'delete_published_shop_coupons' => true, 
					'delete_published_shop_orders' => true, 
					'delete_published_shop_webhooks' => true, 
					'delete_shop_coupon' => true, 
					'delete_shop_coupon_terms' => true, 
					'delete_shop_coupons' => true, 
					'delete_shop_order' => true, 
					'delete_shop_order_terms' => true, 
					'delete_shop_orders' => true, 
					'delete_shop_webhook' => true,
					'delete_shop_webhook_terms' => true, 
					'delete_shop_webhooks' => true, 
					'delete_users' => true, 
					'edit_dashboard' => true, 
					'edit_others_pages' => true, 
					'edit_others_posts' => true, 
					'edit_others_products' => true, 
					'edit_others_shop_coupons' => true, 
					'edit_others_shop_orders' => true, 
					'edit_others_shop_webhooks' => true, 
					'edit_pages' => true, 
					'edit_posts' => true, 
					'edit_private_pages' => true, 
					'edit_private_posts' => true, 
					'edit_private_products' => true, 
					'edit_private_shop_coupons' => true, 
					'edit_private_shop_orders' => true, 
					'edit_private_shop_webhooks' => true, 
					'edit_product' => true, 
					'edit_product_terms' => true, 
					'edit_products' => true, 
					'edit_published_pages' => true, 
					'edit_published_posts' => true, 
					'edit_published_products' => true, 
					'edit_published_shop_coupons' => true, 
					'edit_published_shop_orders' => true, 
					'edit_published_shop_webhooks' => true, 
					'edit_shop_coupon' => true, 
					'edit_shop_coupon_terms' => true, 
					'edit_shop_coupons' => true, 
					'edit_shop_order' => true, 
					'edit_shop_order_terms' => true,
					'edit_shop_orders' => true, 
					'edit_shop_webhook' => true, 
					'edit_shop_webhook_terms' => true, 
					'edit_shop_webhooks' => true, 
					'edit_theme_options' => true, 
					'edit_users' => true, 
					'level_0' => true, 
					'level_1' => true,  
					'level_2' => true, 
					'level_3' => true, 
					'level_4' => true, 
					'level_5' => true, 
					'level_6' => true, 
					'level_7' => true, 
					'level_8' => true, 
					'level_9' => true, 
					'level_10' => true,
					'list_users' => true, 
					'manage_categories' => true, 
					'manage_links' => true, 
					'manage_options' => true, 
					'manage_product_terms' => true, 
					'manage_shop_coupon_terms' => true, 
					'manage_shop_order_terms' => true,
					'manage_shop_webhook_terms' => true, 
					'manage_woocommerce' => true, 
					'moderate_comments' => true, 
					'promote_users' => true, 
					'publish_pages' => true, 
					'publish_posts' => true, 
					'publish_products' => true, 
					'publish_shop_coupons' => true, 
					'publish_shop_orders' => true, 
					'publish_shop_webhooks' => true, 
					'read' => true, 
					'read_private_pages' => true, 
					'read_private_posts' => true, 
					'read_private_products' => true, 
					'read_private_shop_coupons' => true, 
					'read_private_shop_orders' => true, 
					'read_private_shop_webhooks' => true, 
					'read_product' => true, 
					'read_shop_coupon' => true, 
					'read_shop_order' => true, 
					'read_shop_webhook' => true, 
					'remove_users' => true, 
					'unfiltered_html' => true, 
					'unfiltered_upload' => true, 
					'upload_files' => true, 
					'ure_create_capabilities' => true,
					'ure_create_roles' => true, 
					'ure_delete_capabilities' => true, 
					'ure_delete_roles' => true, 
					'ure_edit_roles' => true, 
					'ure_manage_options' => true, 
					'ure_reset_roles' => true, 
					'view_woocommerce_reports' => true, 
					'site_management' => true, 
					'export' => true, 
					'import' => true
				);

				add_role( 'site_management', 'Site Manager', $args );

			}
			
			// Remove unused roles
			if( get_role('subscriber') ) {
				remove_role('subscriber');
			}
			
			if( get_role('editor') ) {
				remove_role('editor');
			}
			
			if( get_role('contributor') ) {
				remove_role('contributor');
			}
			
			if( get_role('author') ) {
				remove_role('author');
			}
			
		}
		
		/**
		 * Assign site_management cap to administrators
		 *
		 * @since 1.0.0
		 */
		public function admin_capabilities() {
			$role = get_role( 'administrator' );
			$role->add_cap( 'site_management' );
		}
		
		/**
		 * Change default name on comments fields
		 *
		 * @since 1.0.0
		 */
		public function comments_name( $author, $comment_ID ) {
			$comment = get_comment( $comment_ID );
			$user = get_userdata( $comment->user_id );

			if ( empty( $comment->comment_author ) ) {
				if ( $comment->user_id && $user )
					$author = $user->first_name . ' ' . $user->last_name;
				else
					$author = __('Anonymous');
			} else {
				
				if( ! empty($user->first_name) ) {
					$author = $user->first_name . ' ' . $user->last_name;
				} else {
					$author =  $comment->comment_author;
				}
			}

			return $author;
		}
		
		/**
		 * Customer registration - validation check
		 *
		 * @since 1.0.0
		 */
		public function registration_error_validation($reg_errors, $sanitized_user_login, $user_email) {
			global $woocommerce;			
			extract( $_POST );
			
			$password = isset($_POST['account_password']) ? $_POST['account_password'] : $_POST['password'];
			$password2 = isset( $_POST['password2'] ) ? $_POST['password2'] : '';

			if ( strcmp( $password, $password2 ) !== 0 ) {
				return new WP_Error( 'registration-error', __( 'Passwords do not match', 'woocommerce' ) );
			}			

			return $reg_errors;		
		}
		
		/**
		 * Customer registration - save fields
		 *
		 * @since 1.0.0
		 */
		public function save_extra_register_fields( $customer_id ) {
			
			if ( isset( $_POST['billing_first_name'] ) ) {
				// WordPress default first name field.
				update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );

				// WooCommerce billing first name.
				update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
			}

			if ( isset( $_POST['billing_last_name'] ) ) {
				// WordPress default last name field.
				update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );

				// WooCommerce billing last name.
				update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
			}

		}

		/**
		 * Check to see if user has updated their password
		 * 
		 * @since 1.0.0
		 */
		public function password_renewal_checker() {
			$email_address = isset($_POST['emailAddress']) ? sanitize_email($_POST['emailAddress']) : '';
			$result = false;

			if($email_address) {
				$user = get_user_by('email', $email_address);

				if($user) {
					if(get_user_meta($user->ID, '_rpw_password_updated', true)) {
						$result = true;
					}
				}
			}

			echo $result;
			exit;
		}

		/**
		 * Add meta when password updated
		 * 
		 * @since 1.0.0 
		 */
		public function add_meta_password_updated($user) {
			update_user_meta($user->ID, '_rpw_password_updated', true);
		}
		
	}
	
}