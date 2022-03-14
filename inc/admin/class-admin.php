<?php
/**
 * The Raven Framework
 * Author: Luke Clifton
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_admin') ) {
	class Raven_admin {

        protected static $instance = null;

        // fields
        static $fields = null;

        /**
         *  Construct
         * 
         * @since 1.0.0
         */
        public function __construct() {
            $this->define_constants();

            if( is_admin() ) {
                $this->includes();
                $this->init();
            }
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
         * Define constants
         * 
         * @since 1.0.0
         */
        public function define_constants() {
            define( 'RAVEN_ADMIN_ASSETS', RAVEN_URL . 'inc/admin/assets/' );
        }

        /**
         * Includes 
         * 
         * @since 1.0.0
         */
        public function includes() {
            // Register fields
            require_once RAVEN_ADMIN . 'class-admin-fields.php';
            // Register options
            // post types
            require_once RAVEN_ADMIN . 'post-types/class-admin-register-posttype-page.php';
            require_once RAVEN_ADMIN . 'post-types/class-admin-register-posttype-product.php';
            // taxonomies
            require_once RAVEN_ADMIN . 'taxonomies/class-admin-register-taxonomy-product.php';
            // cleaners
            require_once RAVEN_FRAMEWORK . 'cleaners/class-admin-cleaner-controller.php';
            require_once RAVEN_FRAMEWORK . 'cleaners/class-admin-simplify-controller.php';
            require_once RAVEN_ADMIN . 'class-admin-ease-of-use-controller.php';
            // theme initilisation
            require_once RAVEN_FRAMEWORK . 'theme-setup/class-tgm-plugin-activation.php';
            require_once RAVEN_FRAMEWORK . 'theme-setup/class-theme-activation-controller.php';
            require_once RAVEN_FRAMEWORK . 'theme-setup/class-theme-setup-shipping-controller.php';
            require_once RAVEN_FRAMEWORK . 'theme-setup/required-plugins.php';
            // helpers
            require_once RAVEN_ADMIN . 'helpers/helper-post-meta.php';
            require_once RAVEN_ADMIN . 'helpers/helper-layout.php';
            require_once RAVEN_ADMIN . 'helpers/helper-sanitization.php';
            require_once RAVEN_ADMIN . 'ajax/ajax-images.php';
      
        }

        /**
         * Init
         * 
         * @since 1.0.0
         */
        public function init() {
            new Raven_admin_register_posttype_page();
            new Raven_admin_register_posttype_product();
            new Raven_admin_register_taxonomy_product();
            // cleaners
            new Raven_admin_cleaner_controller();
            new Raven_simplify_admin_controller();
            new Raven_admin_ease_of_use_controller();
            // theme init
            new Raven_theme_activation_controller();
            new Raven_theme_setup_shipping_controller();

            // Register scripts and styles
            add_action( 'admin_enqueue_scripts', array($this, 'register_styles') );
            add_action( 'admin_enqueue_scripts', array($this, 'register_scripts') );
        }

        /**
         * Theme Setup.
         *
         * @return void
         */
        public function theme_setup() {
        }

        /**
         * Register and Enqueue Styles.
         *
         * @since 1.0.0
         */
        public function register_styles($page) {
            wp_register_style( 'raven_admin_stylesheet', RAVEN_ADMIN_ASSETS . 'css/raven.css', false, '1.0.0' );    
            wp_enqueue_style( 'raven_admin_stylesheet' );
            
            // The Raven JqueryUI stylesheet
            wp_register_style( 'raven_admin_jqueryui_stylesheet', RAVEN_ADMIN_ASSETS . 'css/raven-jqueryui.css', false, '1.0.0' );
            
        }
	
        /**
         * Register and Enqueue Scripts.
         *
         * @since 1.0.0
         */
        public function register_scripts($page) {
            // Post Meta specific functions
            //wp_enqueue_script( 'raven-post-meta', RAVEN_ADMIN_ASSETS . 'js/post-meta.js', array( 'jquery' ), false, '1.0.0' );
            
            if( in_array( $page, array( 'post.php', 'edit.php', 'post-new.php' ), true ) ) {

                if( ! wp_script_is( 'wp-color-picker', 'enqueued' ) ) {
                    wp_enqueue_script( 'wp-color-picker' );
                }
                wp_enqueue_media();
                wp_enqueue_script( 'raven-admin-galleries', RAVEN_ADMIN_ASSETS . 'js/gallery-image-control.js', array( 'jquery' ), true, '1.0.0' );
                wp_localize_script(
                    'raven-admin-galleries',
                    'raven_gallery_control_args',
                    array(
                        'mediaItemTemplate'	=> raven_gallery_single_image_template(),
                        'textOpenTitle'		=> __( 'Upload Images To Your Gallery', RAVEN_DOMAIN ),
                        'textSelectImages'	=> __( 'Select images', RAVEN_DOMAIN ),
                        'textUseImages'		=> __( 'Use these images', RAVEN_DOMAIN ),
                        'editTitle'			=> __( 'Edit attachment', RAVEN_DOMAIN ),
                        'buttonEditFile'	=> __( 'Save attachment', RAVEN_DOMAIN ),
                        'post_id'			=> get_the_ID(),
                        'ajaxurl'           => admin_url( 'admin-ajax.php', 'relative' )
                    )
                );
                    
            }
        }

        /**
         * Get form instance
         * 
         * @since 1.0.0
         */
        public function fields() {
            return Raven_admin_fields::instance();
        }

	}
	
    new Raven_admin();
}