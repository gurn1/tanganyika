<?php
/**
 * Part of the Raven Framework
 *
 * Ajax functions for images
 * Author: Luke Clifton
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) )
	die("Direct access is not permitted");

/**
 * Gallery image options panel get actions
 *
 * @since 1.0.0
 */
function raven_gallery_image_options_panel_get_actions() {
	
	$post_id = $_POST['post_id'];
	$attachment_id = $_POST['attachment_id'];
	
	$values = get_post_meta( $post_id, 'raven_image_options', true );
	
	if( $values ) {
		echo json_encode($values[$attachment_id]);
		
		exit;
	}
	
	return;
	
	exit;
	
}
add_action( 'wp_ajax_raven_gallery_image_options_panel_get_actions', 'raven_gallery_image_options_panel_get_actions' );


/**
 * Gallery image options panel save actions
 *
 * @since 1.0.0
 */
function raven_gallery_image_options_panel_save_actions() {
	
	// Nonce
	$nonce = isset($_POST['security']) ? $_POST['security'] : '';

	// Verify nonce
	if( wp_verify_nonce( $nonce, 'raven_gallery_image_options_nonce_value') ){

		$post_id = isset($_POST['post_id']) ? $_POST['post_id'] : '';
		
		if( $post_id ) {
			//$attachment_id = isset($_POST['attachment_id']) ? $_POST['attachment_id'] : '';
			$form = isset($_POST['form']) ? $_POST['form'] : '';

			// image-options -> ID -> hero-content -> array()
			parse_str( $form, $form);

			if( $form ) {
				$values = get_post_meta( $post_id, 'raven_image_options', true );
				
				if( $values ) {
					// Remove 'image-options' from array and merge
					$merge_array = array_pop($form) + $values;
					$new_value = $merge_array;
				} else {
					// Remove 'image-options' from array
					$new_value = array_pop($form);
				}
				
				update_post_meta( $post_id, 'raven_image_options', $new_value );
			}
		}
	}
	
	exit;
}
add_action( 'wp_ajax_raven_gallery_image_options_panel_save_actions', 'raven_gallery_image_options_panel_save_actions' );