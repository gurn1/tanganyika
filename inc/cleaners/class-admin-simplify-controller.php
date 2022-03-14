<?php
/**
 * Simplify the wordpress admin panel
 *
 * @since 1.0.0
 */
 
/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}


if( ! class_exists('Raven_simplify_admin_controller') ) {
	class Raven_simplify_admin_controller {
		
		public $options;
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			
			$this->id = 'raven_simplify_admin';
					
			// Simplify admin menu
			add_action( 'admin_menu', array( $this, 'simplify_admin_menu' ), 999 );

			// Remove nodes from the admin bar
			add_action( 'admin_bar_menu', array( $this, 'remove_admin_bar_nodes' ), 999 );

			// Remove dashboard widgets
			$widget_hooks = array( 'wp_network_dashboard_setup', 'wp_user_dashboard_setup', 'wp_dashboard_setup' );

			foreach( $widget_hooks as $widget_hook ){
				add_action( $widget_hook, array( $this, 'remove_dashboard_widgets' ), 20 );
			}

			// Update toggle button
			add_action( 'wp_ajax_update_toggle_option', array( $this, 'update_toggle_option' ) );
			
			// Register helper widget
			add_action('wp_dashboard_setup', array( $this, 'register_dashboard_widgets' ) );

			add_action('admin_footer', array( $this, 'admin_footer_functions' ) );
								
		}
		
		
		/**
		 * Get user option
		 *
		 * @since 1.0.0
		 */
		public function user_option() {
			global $current_user;
			
			return get_user_meta( $current_user->ID, $this->id, true );
		}
		
		
		/**
		 * Register the widgets
		 *
		 * @since 1.0.0
		 */
		public function register_dashboard_widgets() {
			global $wp_meta_boxes;

			wp_add_dashboard_widget('raven_support', 'Theme Support', array( $this, 'theme_support' ), 'side', 'high');
		}
		
		
		/**
		 * Simplify the admin panel 
		 *
		 * @since 1.0.0
		 */
		function simplify_admin_menu() {
			global $submenu;
			
			// Remove sections for everyone
			remove_submenu_page( 'woocommerce', 'wc-addons' );
			remove_submenu_page( 'edit.php?post_type=product', 'product_attributes' );
			remove_submenu_page( 'edit.php?post_type=product', 'edit-tags.php?taxonomy=product_tag&amp;post_type=product' );
				
			// Remove sections for site management role
			if( ! current_user_can('administrator') ) {
				// Appearance Menu
				unset($submenu['themes.php'][5]);
				unset($submenu['themes.php'][6]);

				remove_menu_page( 'edit.php?post_type=acf-field-group' );
				remove_menu_page( 'edit.php' );
				remove_menu_page( 'edit-comments.php' );
				
			}
			
			if( $this->user_option() == 1 ) {

				// Remove top level pages
				remove_menu_page( 'users.php' );
				remove_menu_page( 'plugins.php' );
				remove_menu_page( 'tools.php' );

				// Remove woocommerce items
				remove_submenu_page( 'woocommerce', 'wc-settings' );
				remove_submenu_page( 'woocommerce', 'wc-status' );
				
				// Remove settings options
				remove_submenu_page( 'options-general.php', 'options-writing.php' );
				remove_submenu_page( 'options-general.php', 'options-discussion.php' );
				remove_submenu_page( 'options-general.php', 'options-reading.php' );
				remove_submenu_page( 'options-general.php', 'options-media.php' );
				remove_submenu_page( 'options-general.php', 'options-permalink.php' );
				remove_submenu_page( 'options-general.php', 'privacy.php' );
				remove_submenu_page( 'options-general.php', 'cookie-compliance-cf' );
				
				remove_menu_page( 'edit.php?post_type=acf-field-group' );
			
			}
			
		}
			
		
		/**
		 * Remove nodes from admin bar
		 *
		 * @since 1.0.0
		 */
		function remove_admin_bar_nodes( $wp_admin_bar ) {

			$wp_admin_bar->remove_node( 'customize' );
			$wp_admin_bar->remove_node( 'comments' );
			$wp_admin_bar->remove_node( 'appearance' );
			
			if( $this->user_option() == 1 ) {
				$wp_admin_bar->remove_node( 'new-content' );
			}

		}
		
		
		/**
		 * Remove unwanted dashboard widgets
		 *
		 * @since 1.0.0
		 */
		function remove_dashboard_widgets() {
			
			// Remove Quick Press
			remove_meta_box( 'dashboard_quick_press', get_current_screen(), 'side' );

			// Remove Activity
			remove_meta_box( 'dashboard_activity', get_current_screen(), 'normal' );

			if( $this->user_option() == 1 ) {
				
				// Remove Blogroll
				remove_meta_box( 'dashboard_primary', get_current_screen(), 'side' );

				// Remove Yoast
				remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'normal' );

				// Remove woocommerce recent reviews
				remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal' );
				
			}

		}
		
		
		/**
		 * Update simplify toggle
		 * Called by ajax
		 *
		 * @since version 1.0.0
		 */
		public function update_toggle_option() {
			global $current_user;
			
			// Nonce
			$nonce = isset($_POST['security']) ? $_POST['security'] : '';
			$checkbox_toggle = isset($_POST['checkbox_toggle']) ? $_POST['checkbox_toggle'] : '';

			// Verify nonce
			if( wp_verify_nonce( $nonce, 'simplify_nonce') && is_user_logged_in() ){
				
				//update_option( 'raven_admin_simplify', $checkbox_toggle );
				update_user_meta( $current_user->ID, $this->id, $checkbox_toggle );
				
				//wp_safe_redirect( esc_url(site_url( '/wp-admin/' )) );

			}
		
			exit;
						
		}
 
		
		/**
		 * Coconut Four theme support
		 *
		 * @since 1.0.0
		 */
		public function theme_support() {
			global $current_user;
			
			$option	= $this->user_option();
			
			?>
			<script>
				( function ( $ ) {
					
					$(document).on('change', 'input[name=raven-simplify-toggle]', function(e) {
						e.preventDefault();
						
						if(this.checked) {
							var checkbox_toggle = $("input[name=raven-simplify-toggle]").val()
						}else{
							checkbox_toggle = 0
						}

						$.ajax({
							type: "POST",
							url: ajaxurl,
							data: {
								action: "update_toggle_option",
								security: $("input[name='raven_simplify_nonce']").val(),
								checkbox_toggle: checkbox_toggle,
							},
							success: function(data){

								location.reload()
								
							},
						});
						
						
					});
					
				} )( jQuery );	
			</script>
			
			<div id="raven_simplify_container">
				<div class="raven-toggle">
					<input id="raven_simplify_toggle" type="checkbox" name="raven-simplify-toggle" value="1" <?php checked($option, '1'); ?>>
					<label class="raven-toggle-switch" for="raven_simplify_toggle"><i></i></label>
					<label>Simplify the admin panel?</label>
				</div>

				<input type="hidden" name="raven_simplify_nonce" value="<?php echo wp_create_nonce('simplify_nonce'); ?>">
			</div>

			<?php
			
			wp_create_nonce('simplify_nonce');
		}

		/**
		 * Admin footer functions
		 * 
		 * @since 1.0.0
		 */
		function admin_footer_functions() {
			?>
			<style>
				#toplevel_page_woocommerce-marketing {
					display: none !important; 
				}
			
			</style>
			<?php
		}
		
	}
}