<?php
/**
 * Theme activation controller
 * runs on activation
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_theme_activation_controller') ) {
	class Raven_theme_activation_controller {
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			
			// Create pages
			add_action( 'after_switch_theme', array( $this, 'create_pages' ), 40 );
			
			// Add Menus
			add_action( 'after_switch_theme', array( $this, 'create_menus' ), 50 );
			
			// Set options
			add_action( 'after_switch_theme', array( $this, 'set_options' ), 60 );
			
			// Woocommerce settings
			add_action( 'activate_woocommerce/woocommerce.php', array( $this, 'woocommerce_activation' ) );

		}
		
		/**
		 * Create pages
		 *
		 * @since 1.0.0
		 */
		public function create_pages() {
			
			$pages = array();
			
			// Home page 
			if( ! get_page_by_title( 'Home' ) ) {
				$pages['home']	= array( 
					'post_title' 	=> wp_strip_all_tags('Home'),
					'post_status'	=> 'publish',
					'post_author'	=> 1,
					'post_type'		=> 'page',
					'page_template'	=> 'template-parts/page-home.php',
					'menu_order'	=> 1
				);
			}
			
			// Shop
			if( ! get_page_by_title( 'Shop' ) ) {
				$pages['shop']	= array(
					'post_title'	=> wp_strip_all_tags('Shop'),
					'post_status'	=> 'publish',
					'post_author'	=> 1,
					'post_type'		=> 'page',
					'menu_order'	=> 2
				);
			}
			
			// About page
			if( ! get_page_by_title( 'About' ) ) {
				$pages['about']	= array(
					'post_title' 	=> wp_strip_all_tags('About'),
					'post_status'	=> 'publish',
					'post_author'	=> 1,
					'post_type'		=> 'page',
					'page_template'	=> 'template-parts/page-about.php',
					'menu_order'	=> 3
				);
			}
			
			// Contact page
			if( ! get_page_by_title( 'Contact' ) ) {
				$pages['contact']	= array(
					'post_title' 	=> wp_strip_all_tags('Contact'),
					'post_status'	=> 'publish',
					'post_author'	=> 1,
					'post_type'		=> 'page',
					'page_template'	=> 'template-parts/page-contact.php',
					'menu_order'	=> 9
				);
			}
			
			// Blog
			if( ! get_page_by_title( 'Blog' ) ) {
				$pages['blog']	= array(
					'post_title'	=> wp_strip_all_tags('Blog'),
					'post_status'	=> 'draft',
					'post_author'	=> 1,
					'post_type'		=> 'page',
					'menu_order'	=> 10
				);
			}
			
			// User Account
			if( ! get_page_by_title( 'My Account' ) ) {
				$pages['my-account']	= array( 
					'post_title' 	=> wp_strip_all_tags('My Account'),
					'post_content'	=> '[woocommerce_my_account]',
					'post_status'	=> 'publish',
					'post_author'	=> 1,
					'post_type'		=> 'page',
					'menu_order'	=> 11
				);
			}
			
			// Basket
			if( ! get_page_by_title( 'Basket' ) ) {
				$pages['basket']	= array( 
					'post_title' 	=> wp_strip_all_tags('Basket'),
					'post_content'	=> '[woocommerce_cart]',
					'post_status'	=> 'publish',
					'post_author'	=> 1,
					'post_type'		=> 'page',
					'menu_order'	=> 12
				);
			}
			
			// Checkout
			if( ! get_page_by_title( 'Checkout' ) ) {
				$pages['checkout']	= array( 
					'post_title' 	=> wp_strip_all_tags('Checkout'),
					'post_content'	=> '[woocommerce_checkout]',
					'post_status'	=> 'publish',
					'post_author'	=> 1,
					'post_type'		=> 'page',
					'menu_order'	=> 13
				);
			}
						
			// Terms and conditions
			if( ! get_page_by_title( 'Terms and Conditions' ) ) {
				$pages['tc']	= array( 
					'post_title' 	=> wp_strip_all_tags('Terms and Conditions'),
					'post_status'	=> 'publish',
					'post_author'	=> 1,
					'post_type'		=> 'page',
					'menu_order'	=> 14
				);
			}
			
			// Privacy
			if( ! get_page_by_title( 'Privacy Policy' ) ) {
				$pages['privacy']	= array( 
					'post_title' 	=> wp_strip_all_tags('Privacy Policy'),
					'post_status'	=> 'publish',
					'post_author'	=> 1,
					'post_type'		=> 'page',
					'menu_order'	=> 15
				);
			}
			
			
			if( $pages ) {
				foreach( $pages as $page ) {
					$page_id = wp_insert_post($page);
					
					if( isset($page['page_template']) ) {
						add_post_meta( $page_id, '_wp_page_template', sanitize_text_field($page['page_template']) );
					}
				}
			}
			
			// Remove sample page
			if( get_page_by_title( 'Sample Page' ) ) {
				$sample = get_page_by_title( 'Sample Page' );	
				wp_delete_post( $sample->ID, true ); // true to force delete 
			}
			
		}
		
		/**
		 * Create menus 
		 *
		 * @since 1.0.0
		 */
		public function create_menus() {
			
			// Get pages
			$args = array( 
				'post_type'			=> 'page',
				'posts_per_page'	=> -1,
				'post_status'		=> 'publish',
				'orderby'			=> 'menu_order',
				'order'				=> 'ASC'
			);

			$pages = get_posts( $args );
			
			// exluded pages on top level
			$top_excluded_pages = array(
				'my-account',
				'checkout',
				'basket',
				'terms-and-conditions',
				'privacy-policy',
			);
			
			// footer excluded
			$footer_excluded_pages = array(
				'checkout',
				'basket'
			);

			// Get the theme menu locations & loop them
			$menu_locations = get_registered_nav_menus();

			if( $menu_locations ) {

				foreach( $menu_locations as $key => $menu_name ) {

					$menu_exists 		= wp_get_nav_menu_object( $menu_name );
					$theme_locations 	= get_theme_mod('nav_menu_locations');

					if( !$menu_exists ) {

						// Create Menu
						$menu_id = wp_create_nav_menu($menu_name);

						$theme_locations[$key] = $menu_id;
						set_theme_mod( 'nav_menu_locations', $theme_locations ); // Add menu to theme location

						if( $pages ) {

							foreach( $pages as $page ) {
								
								// exclude pages from menus
								if( $key == 'primary' && in_array($page->post_name, $top_excluded_pages) ) {
									continue;
								}
								
								if( $key == 'footer' && in_array($page->post_name, $footer_excluded_pages) ) {
									continue;
								}
								
									
								// Create menu items
								$menu_item = wp_update_nav_menu_item($menu_id, 0, array(
									'menu-item-object' 		=> 'page',
									'menu-item-object-id'	=> $page->ID,
									'menu-item-type' 		=> 'post_type',
									'menu-item-title' 		=> $page->post_title,
									'menu-item-classes' 	=> $page->post_name,
									'menu-item-status' 		=> 'publish'
								));
												
							}
							
						}

					}

				}

			}
		
			flush_rewrite_rules();
			
		}
		
		/**
		 * Set options
		 *
		 * @since 1.0.0
		 */
		public function set_options() {
			global $wp_rewrite;
			
			/**
			 * General
			 */
			
			// Assign default user
			update_option( 'default_role', 'customer' );
			
			/**
			 * Reading 
			 */
			
			// Your homepage displays:
			update_option('show_on_front', 'page');

			// Static page home 
			if( get_page_by_title('Home') ) {		
				$page = get_page_by_title('Home');
				update_option( 'page_on_front', $page->ID );
			}
			
			// Static page blog
			if( get_page_by_title('Blog') ) {
				$page = get_page_by_title('Blog');
				update_option( 'page_for_posts', $page->ID );
			}
			
			/**
			 * Discussion
			 */
			
			// Allow link notifications from other blogs on new articles
			update_option( 'default_ping_status', 'closed' ); // unchecked
			
			// Allow people to post comments on new articles
			update_option( 'default_comment_status', 'open' ); // checked
			
			// Users must be registered and logged in to comment
			update_option( 'comment_registration', '1' ); // checked
			
			// Enable threaded (nested) comments
			update_option( 'thread_comments', '1' ); // checked
			update_option( 'thread_comments_depth', '2' ); // 2 levels
			
			// Comment must be manually approved
			update_option( 'comment_moderation', '1' ); // checked
			
			// Show avatars
			update_option( 'show_avatars', '' ); // unchecked
	
			/**
			 * Permalinks
			 */
			
			// Assign permalink
			$current_permalink = get_option('permalink_structure');
			
			if( $current_permalink != '/%postname%/' ) {
				$wp_rewrite->set_permalink_structure('/%postname%/');
    			$wp_rewrite->flush_rules();
			}
			
		}
		
		/**
		 * Woocommerce settings
		 *
		 * @since 1.0.0
		 */
		public function woocommerce_activation( ) {
			// force ssl on checkout
			update_option( 'woocommerce_force_ssl_checkout', 'yes' );

			/**
			 * Assign pages
			 */
			
			$pages = array( 
				'Basket' 				=> 'woocommerce_cart_page_id', 
				'Checkout'				=> 'woocommerce_checkout_page_id',
				'My Account' 			=> 'woocommerce_myaccount_page_id',
				'Terms and Conditions'	=> 'woocommerce_terms_page_id',
				'Privacy Policy'		=> 'wp_page_for_privacy_policy',
				'Shop'					=> 'woocommerce_shop_page_id'
			);

			foreach( $pages as $page => $hook ) {
				$object = get_page_by_title( $page );

				if( $object ) {
					update_option( $hook, $object->ID );
				} 

			}
			
			/**
			 * General
			 */
			
			// Enable coupons
			update_option( 'woocommerce_enable_coupons', 'no' ); // unchecked
			
			/**
			 * Product
			 */
			
			// Enable reviews
			update_option( 'woocommerce_enable_reviews', 'no' ); // unchecked
			
			// Enable stock management
			update_option( 'woocommerce_manage_stock', 'yes' ); // checked
			
			// Enable low stock notifications
			update_option( 'woocommerce_notify_low_stock', 'no' ); // unchecked
 			
			// Enable out of stock notifications
			update_option( 'woocommerce_notify_no_stock', 'no' ); // unchecked
			
			/**
			 * Shipping
			 */
			
			// Enable debug mode
			
			/**
			 * Accounts & Privacy
			 */
			
			// Allow customers to place orders without an account
			update_option( 'woocommerce_enable_guest_checkout', 'no' ); // unchecked
			
			// Allow customers to log into an existing account during checkout
			update_option( 'woocommerce_enable_checkout_login_reminder', 'yes' ); // checked
			
			// Allow customer to create am account during checkout
			update_option( 'woocommerce_enable_signup_and_login_from_checkout', 'yes' ); // checked
			
			// Allow customers to create an account on the “My Account” page 
			update_option( 'woocommerce_enable_myaccount_registration', 'yes' ); // checked
			
			// When creating an account, automatically generate an account username for the customer based on their name, surname or email
			update_option( 'woocommerce_registration_generate_username', 'yes' ); // checked
			
			// When creating an account, automatically generate an account password
			update_option( 'woocommerce_registration_generate_password', 'no' ); // unchecked
			
			/**
			 * Emails
			 */
			
			// Footer text
			update_option( 'woocommerce_email_footer_text', ' ' ); // leave blank
			
			// Base Colour - set base colour hex
			update_option( 'woocommerce_email_base_color', '#eeeeee' );
			
		} 
		
	}	
}