<?php
/**
 * Post Type - Page
 * This file makes changes to the existing post type
 *
 * @since 1.0.0
 */

// Exit if accessed directly
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_admin_register_posttype_page') ) {
	class Raven_admin_register_posttype_page {
		
		private $name = '';
		private $ID = '';
		private $taxonomy_id = '';
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct(  ) {
			$this->ID = 'page'; 
			
			// Register other scripts 
			add_action( 'add_meta_boxes', array( $this, 'meta_boxes' ) );

			// Save meta fields
			add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
				
		}
		
		/**
		 * Meta box creation
		 *
		 * @since version 1.0.0
		 */
		public function meta_boxes() {	
			add_meta_box( 'hero_slider', __( 'Hero Slider', RAVEN_DOMAIN ), array($this, 'hero_slider'), $this->ID, 'normal', 'high' );
		}
		
		/**
		 * Meta box content - Gallery Images
		 *
		 * @since 1.0.0
		 */		
		public function hero_slider() {
			global $post;

			$helper_fields = Raven_admin_fields::instance();
			
			raven_prepare_metabox( 'page' );

			$helper_fields->gallery('raven_gallery_images');
		}
	
		/**
		 * Save Post
		 *
		 * @since 1.0.0
		 */
		public function save_post( $post_id, $post ) {
			// Prevent infinite loop
			if ( ! wp_is_post_revision( $post_id ) ){
				
                // Update the gallery fields
			    raven_metabox_gallery_save( $post_id );
				
			}
		
		}
		
	}

}