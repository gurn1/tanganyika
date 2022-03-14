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

if( ! class_exists('Raven_framework') ) {
	class Raven_framework {

        // Version
        public $version = '1.0.0';

        // Domain
        public $domain = 'raven';

        // Single instance
        protected static $_instance = null;

        // Forms instance
        public $forms = null;

        // notices instance
        public $notices = null;

        // contact instance
        public $contact = null;

        // Media instance
        public $media = null;

        // Posttype instance
        public $products = null;

        // Taxonomies instance
        public $taxonomies = null;

        // Users instance
        public $users = null;

        // Loops instance
        public $loops = null;

        // Admin instance
        public $admin = null;

        /**
         *  Construxt
         * 
         * @since 1.0.0
         */
        public function __construct() {
            $this->define_constants();
            $this->includes();
            $this->init();
        }

        /**
         * Define constants
         * 
         * @since 1.0.0
         */
        public function define_constants() {
            define( 'RAVEN_FRAMEWORK', get_template_directory() . '/inc/');
            define( 'RAVEN_ADMIN', RAVEN_FRAMEWORK . 'admin/');
            define( 'RAVEN_DIR', get_template_directory() . '/' );
            define( 'RAVEN_URL', get_template_directory_uri() . '/' );
            define( 'RAVEN_ASSETS', RAVEN_URL . 'assets/' );
            define( 'RAVEN_TEMPLATE_PART', RAVEN_DIR . 'template-parts/' );
            define( 'RAVEN_TEMPLATE', RAVEN_DIR . 'templates/' );
            define( 'RAVEN_DOMAIN', $this->domain );
            define( 'RAVEN_VERSION', '1.0.0' );
        }

        /**
         * Includes 
         * 
         * @since 1.0.0
         */
        public function includes() {
            require_once RAVEN_FRAMEWORK . 'class-autoloader.php';

            if( is_admin() ) {
                require_once RAVEN_ADMIN . 'class-admin.php';
            }

            $this->frontend_includes();

            // Customizer
            require_once RAVEN_FRAMEWORK . 'customizer/class-customizer-controller.php';
        }

        /**
         * Frontend includes
         * 
         * @since 1.0.0
         */
        public function frontend_includes() {
            // cleaners
            require_once RAVEN_FRAMEWORK . 'cleaners/class-frontend-cleaner-controller.php';
            // core
            require_once RAVEN_FRAMEWORK . 'core/class-contact-controller.php';
            require_once RAVEN_FRAMEWORK . 'core/class-cookie-banner-controller.php';
            require_once RAVEN_FRAMEWORK . 'core/class-forms-controller.php';
            require_once RAVEN_FRAMEWORK . 'core/class-loops-controller.php';
            require_once RAVEN_FRAMEWORK . 'core/class-media-controller.php';
            require_once RAVEN_FRAMEWORK . 'core/class-notification-controller.php';
            require_once RAVEN_FRAMEWORK . 'core/class-posttype-product-controller.php';
            require_once RAVEN_FRAMEWORK . 'core/class-shop-general-controller.php';
            require_once RAVEN_FRAMEWORK . 'core/class-shop-orders-controller.php';
            require_once RAVEN_FRAMEWORK . 'core/class-sku-search-controller.php';
            require_once RAVEN_FRAMEWORK . 'core/class-taxonomies-controller.php';
            require_once RAVEN_FRAMEWORK . 'core/class-users-controller.php';
            // walkers
            require_once RAVEN_FRAMEWORK . '/core/class-walker-comment-controller.php';
            // widgets
            require_once RAVEN_FRAMEWORK . 'widgets/class-widget-all-products-categories.php';
            // helpers
            require_once RAVEN_FRAMEWORK . 'helpers/helper-account.php';
            require_once RAVEN_FRAMEWORK . 'helpers/helper-global.php';
            require_once RAVEN_FRAMEWORK . 'helpers/helper-media.php';
            require_once RAVEN_FRAMEWORK . 'helpers/helper-template-tags.php';
        }

        /**
         * Main Instance.
         *
         * Ensures only one instance is loaded or can be loaded.
         *
         * @since 1.0.0
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Init
         * 
         * @since 1.0.0
         */
        public function init() {
            new Raven_frontend_cleaner_controller();
            // load forms 
            new Raven_forms_controller();
            // Contact data
            $this->contact();
            // Media
            $this->media();
            // Users
            $this->users();
            // Loops
            $this->loops();
            // SKU search
            new Raven_sku_search_controller();
            // Shop general
            new Raven_shop_general_controller();
            // Shop orders
            new Raven_shop_orders_controller();
            // Cookie banner
            new Raven_cookie_banner_controller(true);

            // Theme Setup
            add_action( 'after_setup_theme', array($this, 'theme_setup') );

            // Disables the block editor from managing widgets in the Gutenberg plugin.
            add_filter( 'gutenberg_use_widgets_block_editor', '__return_false', 100 );

            // Disables the block editor from managing widgets. renamed from wp_use_widgets_block_editor
            add_filter( 'use_widgets_block_editor', '__return_false' );

            // Register scripts and styles
            add_action( 'wp_enqueue_scripts', array($this, 'register_styles') );
            add_action( 'wp_enqueue_scripts', array($this, 'register_scripts') );

            // Register widget areas
            add_action( 'widgets_init', array($this, 'register_widgets_area') );

            // Register widgets
            add_action( 'widgets_init', array( $this, 'register_widgets' ) );
        }

    
        /**
         * Theme Setup.
         *
         * @return void
         */
        public function theme_setup() {
            load_theme_textdomain( RAVEN_DOMAIN );
    
            /** Register navigation areas */
            register_nav_menus(
                array(
                    'primary' => __( 'Primary', RAVEN_DOMAIN ),
                    'footer' => __( 'Footer', RAVEN_DOMAIN ),
                )
            );
                
            add_theme_support( 'post-thumbnails' );
            add_theme_support( 'title-tag' );
                
            add_theme_support(
                'html5',
                array(
                    'search-form',
                    'comment-form',
                    'comment-list',
                    'gallery',
                    'caption',
                    'script',
                    'style'
                )
            );
            
            /** Remove admin bar */
            show_admin_bar(false);
    
            /**
             * Add support for core custom logo.
             *
             * @link https://codex.wordpress.org/Theme_Logo
             */
            add_theme_support(
                'custom-logo',
                array(
                    'height'      => 190,
                    'width'       => 190,
                    'flex-width'  => true,
                    'flex-height' => true,
                )
            );
            
        }

        /**
         * Register and Enqueue Styles.
         *
         * @since 1.0.0
         */
        public function register_styles() {
            
            // Primary Stylesheet
            if( ! wp_style_is( 'raven-stylesheet', 'enqueued' ) ) {
                wp_enqueue_style( 'raven-stylesheet', get_stylesheet_uri(), array(), $this->version );
                wp_enqueue_style( 'raven_theme-print-style', RAVEN_ASSETS . 'css/print.css', array(), wp_get_theme()->get( 'Version' ), 'print' );
                wp_style_add_data( 'raven-stylesheet', 'rtl', 'replace' );
            }
            
            // Font Awesome
            if( ! wp_style_is( 'raven-font-awesome', 'enqueued' ) ) {
                wp_enqueue_style( 'raven-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css' );
            }
            
            // Google fonts
            if( ! wp_style_is( 'raven-google-fonts', 'enqueued' ) ) {
                wp_enqueue_style( 'raven-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:400,600,700' );
            }
            
            if( ! wp_style_is( 'raven-lightbox-css', 'enqueued' ) ) {
                wp_enqueue_style( 'raven-lightbox-css', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css', array(), null );
            }
                
            if( is_front_page() ) {
                wp_enqueue_style( 'raven-flexslider', RAVEN_ASSETS . 'vendor/flexslider/flexslider.css', array(), '' );
            }

            if( class_exists( 'woocommerce' ) ) {
                wp_enqueue_style( 'raven_theme-style-shop', RAVEN_URL . 'shop.css', array(), '1.0.0' );	
            }

        }
	
        /**
         * Register and Enqueue Scripts.
         *
         * @since 1.0.0
         */
        public function register_scripts() {       
            // jQuery script
            if( ! wp_script_is( 'raven-primary-jquery', 'enqueued' ) ) {
                wp_enqueue_script( 'raven-primary-jquery', RAVEN_ASSETS .'js/global-min.js', array(), $this->version, true );
                wp_script_add_data( 'raven-primary-jquery', 'async', true );
                
                // add ajax object data
                wp_localize_script( 'raven-primary-jquery', 'ravenObject', array(
                    'ajaxurl'   	=> admin_url( 'admin-ajax.php', 'relative' ),
                    'loaderURL' 	=> RAVEN_URL . 'assets/images/loader.png',
                    'lostpwordURL'	=> wp_lostpassword_url()
                ));
                
            }
            
            // Lightbox
            if( ! wp_script_is( 'raven-lightbox', 'enqueued' ) && ! class_exists( 'woocommerce' ) ) {
                wp_enqueue_script( 'raven-lightbox', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js', array(), true );
                wp_script_add_data( 'raven-lightbox', 'async', true );
            }
            
            // Flexslider
            if( is_front_page() ) {
                wp_enqueue_script( 'raven-flexslider', RAVEN_ASSETS . 'vendor/flexslider/jquery.flexslider-min.js', array(), '', true );
                
                wp_add_inline_script(
                    'raven-flexslider',
                    'jQuery(window).on("load", function() {
                    jQuery(".flexslider").flexslider({
                        animation: "slide"
                    });
                    });'
                );
            }

            // Navigation
            if ( has_nav_menu( 'menu-1' ) ) {
                wp_enqueue_script( 'raven_theme-priority-menu', RAVEN_ASSETS . 'js/priority-menu.js', array(), '1.1', true );
                wp_enqueue_script( 'raven_theme-touch-navigation', RAVEN_ASSETS . 'js/touch-keyboard-navigation.js', array(), '1.1', true );
            }

            // Comments
            if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
                wp_enqueue_script( 'comment-reply' );
            }
        
        }

        /**
         * Register widget areas
         *
         * @since 1.0.0
         */
        public function register_widgets_area() {         
            register_sidebar(
                array(
                    'name'          => __( 'Shop Sidebar', RAVEN_DOMAIN ),
                    'id'            => 'sidebar-product',
                    'description'   => __( 'Add widgets here to appear in your shop sidebar.', RAVEN_DOMAIN ),
                    'before_widget' => '<section id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<h3 class="widget-title">',
                    'after_title'   => '</h3>',
                )
            );

            register_sidebar(
                array(
                    'name'          => __( 'Footer', RAVEN_DOMAIN ),
                    'id'            => 'footer',
                    'description'   => __( 'Add widgets here to appear in your footer.', RAVEN_DOMAIN ),
                    'before_widget' => '<section id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</section>',
                    'before_title'  => '',
                    'after_title'   => '',
                )
            );
        }

        /**
         * Register Widgets.
         *
         * @since 1.0.0
         */
        public function register_widgets() {
            if( class_exists('WC_Widget') ) {
                register_widget( 'raven_widget_all_product_categories' );
            }
        }

        /**
         * Get form instance
         * 
         * @since 1.0.0
         */
        public function forms() {
            return Raven_forms_controller::instance();
        }

        /**
         * Get notification instance
         * 
         * @since 1.0.0
         */
        public function notices() {
            return Raven_notification_controller::instance();
        }

        /**
         * Get contact data instance
         * 
         * @since 1.0.0
         */
        public function contact() {
            return Raven_contact_controller::instance();
        }

        /**
         * Get media instance
         * 
         * @since 1.0.0
         */
        public function media() {
            return Raven_media_controller::instance();
        }

        /**
         * Get products instance
         * 
         * @since 1.0.0
         */
        public function products() {
            return Raven_posttype_product_controller::instance();
        }

         /**
         * Get taxonomies instance
         * 
         * @since 1.0.0
         */
        public function taxonomies() {
            return Raven_taxonomies_controller::instance();
        }

        /**
         * Get users instance
         * 
         * @since 1.0.0
         */
        public function users() {
            return Raven_users_controller::instance();
        }

        /**
         * Get loops instance
         * 
         * @since 1.0.0
         */
        public function loops() {
            return Raven_loops_controller::instance();
        }

        /**
         * Get admin instance
         * 
         * @since 1.0.0
         */
        public function admin() {
            return Raven_admin::instance();
        }
	}
	
}