<?php
/**
 * Autoloader
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_autoloader') ) {
	
	class Raven_autoloader {
        /**
         * Single instance
         * 
         * @since 1.0.0
         */
        private static $_instance = null;

        /**
         * Private construct
         * 
         * @since 1.0.0
         */
        private function __construct() {
            spl_autoload_register( array($this, 'load') );
        }

        /**
         * Single instance
         * 
         * @since 1.0.0
         */
        public static function instance() {
            if( ! self::$_instance ) {
                self::$_instance = new Raven_autoloader();
            }

            return self::$_instance;
        }

        /**
         * Class loader
         * 
         * @since 1.0.0
         */
        public function load($class) {
            $file = $this->get_file_name_from_class($class);

            $sources = array(
                RAVEN_FRAMEWORK . 'admin/',
                RAVEN_FRAMEWORK . 'core/',
                RAVEN_FRAMEWORK . 'cleanup/',
                RAVEN_FRAMEWORK . 'theme-setup/',
                RAVEN_FRAMEWORK . 'widgets/',
            );

            foreach($sources as $source) {
                $file = $source . $file;
                if( is_file($file) ) {
                    include_once $file;
                }
            }
        }

        /**
         * Get file name from class name
         * 
         * @since 1.0.0
         */
        public function get_file_name_from_class($class) {
            // remove framework name
            $class = str_replace('Raven_', '', $class);
        
            return 'class-' . str_replace( '_', '-', $class) . '.php';
        }

    }

    Raven_autoloader::instance();
}