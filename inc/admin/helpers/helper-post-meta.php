<?php
/**
 * Part of the Raven Framework
 *
 * Various wrapping functions for easier custom fields creation.
 * Author: Luke Clifton
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) )
	die("Direct access is not permitted");


/**
 * Add security field to metabox
 *
 * @since 1.0.0
 */
function raven_prepare_metabox( $post_type ) {
	wp_nonce_field( basename( __FILE__ ), $post_type . '_nonce' );
}

/**
 * Hero Section - Text fields
 *
 * @since 1.0.0
 */
function raven_metabox_tab_hero_text( $object, $box ) {
	$helper_fields = Raven_admin_fields::instance();
	$value = !empty( get_post_meta( $object->ID, 'header_hero', true ) ) ? get_post_meta( $object->ID, 'header_hero', true ) : array();

	raven_metabox_open_tab( esc_html__( 'Text', RAVEN_DOMAIN ), 'dashicons-editor-textcolor' );
	
		$helper_fields->input( 'raven_title', esc_html__( 'Page Title (overrides the normal title):', RAVEN_DOMAIN ), array(
			'field' => array(
				'name'	=> 'header-hero[text][title]',
				'value'	=> array( 'supplied' => true, 'fieldvalue' => isset($value['text']['title']) ? $value['text']['title'] : '' ),
			) )
		);
	
		$helper_fields->input( 'raven_subtitle', esc_html__( 'Page Strap Line:', RAVEN_DOMAIN ), array(
			'field' => array(
				'name'	=> 'header-hero[text][subtitle]',
				'value'	=> array( 'supplied' => true, 'fieldvalue' => isset($value['text']['subtitle']) ? $value['text']['subtitle'] : '' ),
			) )
		);
	
		do_action( "raven_metabox_hero_text_{$object->post_type}");

	raven_metabox_close_tab();
	
}

/**
 * Hero Section - Colour fields
 *
 * @since 1.0.0
 */
function raven_metabox_tab_hero_colour( $object, $box ) {
	$helper_fields = Raven_admin_fields::instance();
	$value = !empty( get_post_meta( $object->ID, 'header_hero', true ) ) ? get_post_meta( $object->ID, 'header_hero', true ) : array();
	
	raven_metabox_open_tab( esc_html__( 'Colours', RAVEN_DOMAIN ), 'dashicons-media-text' );
	
		$helper_fields->input( 'hero_background_color', esc_html__( 'Background Color:', RAVEN_DOMAIN ), array( 
			'field'	=> array(
				'name'		=> 'header-hero[color][background]',
				'value'		=> array( 'supplied' => true, 'fieldvalue' => isset($value['color']['background']) ? $value['color']['background'] : '' ),
				'class' => 'blockchain-color-picker widefat' 
			),
		) );
	
		$helper_fields->input( 'hero_text_color', esc_html__( 'Text Color:', RAVEN_DOMAIN ), array( 
			'field'	=> array(	
				'name'		=> 'header-hero[color][text]',
				'value'		=> array( 'supplied' => true, 'fieldvalue' => isset($value['color']['text']) ? $value['color']['text'] : '' ),
				'class' => 'blockchain-color-picker widefat',
			),
		) );
	
		$helper_fields->input( 'hero_overlay_color', esc_html__( 'Overlay Color:', RAVEN_DOMAIN ), array( 
			'field'	=> array(
				'name'		=> 'header-hero[color][overlay]',
				'value'		=> array( 'supplied' => true, 'fieldvalue' => isset($value['color']['overlay']) ? $value['color']['overlay'] : '' ),
				'class' => 'blockchain-alpha-color-picker widefat',
			),
		) );
	
		do_action( "raven_metabox_hero_colors_{$object->post_type}");

	raven_metabox_close_tab();
}

/**
 * Herso Section - Media fields
 *
 * @since 1.0.0
 */
function raven_metabox_tab_hero_media( $object, $box ) {
	$helper_fields = Raven_admin_fields::instance();
	$value = !empty( get_post_meta( $object->ID, 'header_hero', true ) ) ? get_post_meta( $object->ID, 'header_hero', true ) : array();
	
	raven_metabox_open_tab( esc_html__( 'Media', RAVEN_DOMAIN ), 'dashicons-format-image' );

		// Media Type
		$helper_fields->image( 'hero_image_id', array(
			'field' 		=> array(
				'name'		=> 'header-hero[media][image]',
				'value'		=> array( 'supplied' => true, 'fieldvalue' => isset($value['media']['image']) ? $value['media']['image'] : '' ),
			),
			'class' 		=> 'raven-hero-image-upload', 
			'image_size' 	=> 'large', 
			'button_text' 	=> 'Upload Media',
		) );
	
		// Media URL
		$helper_fields->input( 'hero_video_url', esc_html__( 'Or paste Video URL (YouTube or Vimeo)', RAVEN_DOMAIN ), array(
			'field' => array(
				'name'		=> 'header-hero[media][video-url]',
				'value'		=> array( 'supplied' => true, 'fieldvalue' => isset($value['media']['video-url']) ? $value['media']['video-url'] : '' ),
			),
			'esc_func' 		=> 'esc_url' 
		) );

		// Image Options
		raven_helper_slide_box_open();
		
		$helper_fields->select( 'hero_image_repeat', raven_get_image_repeat_choices(), esc_html__( 'Image repeat:', RAVEN_DOMAIN ), array( 
			'field'	=> array(
				'name'	=> 'header-hero[css][image-repeat]',
				'value'	=> array( 'supplied' => true, 'fieldvalue' => isset($value['css']['image-repeat']) ? $value['css']['image-repeat'] : '' ),
			),
			'default' 	=> 'no-repeat' 
		) );
	
		$helper_fields->select( 'hero_image_position_x', raven_get_image_position_x_choices(), esc_html__( 'Image horizontal position:', RAVEN_DOMAIN ), array( 
			'field'	=> array(
				'name'	=> 'header-hero[css][position-x]',
				'value'	=> array( 'supplied' => true, 'fieldvalue' => isset($value['css']['position-x']) ? $value['css']['position-x'] : '' ),
			),
			'default' 	=> 'center' 
		) );
	
		$helper_fields->select( 'hero_image_position_y', raven_get_image_position_y_choices(), esc_html__( 'Image vertical position:', RAVEN_DOMAIN ), array(
			'field'	=> array(
				'name'	=> 'header-hero[css][position-y]',
				'value'	=> array( 'supplied' => true, 'fieldvalue' => isset($value['css']['position-y']) ? $value['css']['position-y'] : '' ),
			),
			'default' 	=> 'center' 
		) );
	
		$helper_fields->select( 'hero_image_attachment', raven_get_image_attachment_choices(), esc_html__( 'Image attachment:', RAVEN_DOMAIN ), array( 
			'field'	=> array(
				'name'	=> 'header-hero[css][attachment]',
				'value'	=> array( 'supplied' => true, 'fieldvalue' => isset($value['css']['attachment']) ? $value['css']['attachment'] : '' ),
			),
			'default' 	=> 'scroll' 
		) );
	
		$helper_fields->checkbox( 'hero_image_cover', 1, esc_html__( 'Scale the image to cover its container.', RAVEN_DOMAIN ), array( 
			'field'	=> array(
				'name'	=> 'header-hero[css][cover]',
				'value'	=> array( 'supplied' => true, 'fieldvalue' => isset($value['css']['cover']) ? $value['css']['cover'] : '' ),
			),
			'default' 		=> 1, 
			'toggle-switch' => true 
		) );
	
		do_action( "raven_metabox_hero_image_options_{$object->post_type}");
	
		raven_helper_slide_box_close();

	raven_metabox_close_tab();
}

/**
 * Get all the registered image sizes along with their dimensions
 * @global array $_wp_additional_image_sizes
 *
 * @since 1.0.0
 */
function raven_get_all_image_sizes() {
    global $_wp_additional_image_sizes;

    $default_image_sizes = get_intermediate_image_sizes();

    foreach ( $default_image_sizes as $size ) {
        $image_sizes[ $size ][ 'width' ] = intval( get_option( "{$size}_size_w" ) );
        $image_sizes[ $size ][ 'height' ] = intval( get_option( "{$size}_size_h" ) );
        $image_sizes[ $size ][ 'crop' ] = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
    }

    if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
        $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
    }

    return $image_sizes;
}


/**
 * Get all the registered image sizes along with their dimensions
 *
 * @since 1.0.0
 */
function raven_list_all_image_sizes() {
    
	$options = raven_get_all_image_sizes();
	$array = array('' => '');
	
	$options =  $array + $options; 
	
	foreach( $options as $key => $option ) {
		if( $key == '' ) continue;
		
		$array[$key] = $key.' ('.$option['width'].'X'.$option['height'].')';  
	}
	
	return $array;
}

/**
 * Hero Title save metadata
 *
 * @since
 */
function raven_metabox_tab_hero_text_save( $post_id ) {
	$post = get_post( $post_id );
	$default = array( 
		'header_hero' => $_POST['header-hero'], 
	);
	
	$fields = apply_filters( "raven_metabox_hero_text_{$post->post_type}_save", $default );
	
	foreach( $fields as $key => $field ) {
		
		if( !empty( $field ) ) {
			update_post_meta( $post_id, $key, $field );
		}
		
	}
	
}

/**
 * Hero Colours save metadata
 *
 * @since 1.0.0
 */
function raven_metabox_tab_hero_colors_save( $post_id ) {
	$post = get_post( $post_id );
	$default = array(
		//'hero_background_color'	=> raven_sanitize_hex_color( $_POST['hero_background_color'] ),
		//'hero_text_color'		=> raven_sanitize_hex_color( $_POST['hero_text_color'] ),
		//'hero_overlay_color'	=> raven_sanitize_rgba_color( $_POST['hero_overlay_color'] )
	);
	
	$fields = apply_filters(  "raven_metabox_hero_color_{$post->post_type}_save", $default );
			
	foreach( $fields as $key => $field ) {
		update_post_meta( $post_id, $key, !isset( $_POST[$key] ) ? '' : $field );
	}
	
}

/**
 * Hero Image save metadata
 *
 * @since 1.0.0
 */
function raven_metadata_tab_hero_media_save( $post_id ) {
	$post = get_post( $post_id );
	$default = array(
		//'hero_image_id'			=> sanitize_text_field( $_POST['hero_image_id'] ),
		//'hero_video_url'			=> sanitize_text_field( $_POST['hero_video_url'] ),
		//'hero_image_repeat'		=> raven_sanitize_image_repeat( $_POST['hero_image_repeat'] ),
		//'hero_image_position_x'	=> raven_sanitize_image_position_x( $_POST['hero_image_position_x'] ),
		//'hero_image_position_y'	=> raven_sanitize_image_position_y( $_POST['hero_image_position_y'] ),
		//'hero_image_attachment'	=> sanitize_text_field( $_POST['hero_image_attachment'] ),
		//'hero_image_cover'		=> raven_sanitize_checkbox_ref( $_POST['hero_image_cover'] )
	);
	
	$fields = apply_filters(  "raven_metabox_hero_media_{$post->post_type}_save", $default );
			
	foreach( $fields as $key => $field ) {
		update_post_meta( $post_id, $key, !isset( $_POST[$key] ) ? '' : $field );
	}
	
}

/**
 * Gallery upload field - save 
 *
 * @since 1.0.0
 */
function raven_metabox_gallery_save( $post_id ) {
	if( isset($_POST['raven_gallery_images']) || isset($_POST['raven_gallery_cover_image']) ) {
		$default = array(
			'raven_gallery_images'		=> sanitize_text_field( $_POST['raven_gallery_images'] ), // update image ids field
			'raven_gallery_cover_image'	=> sanitize_text_field( $_POST['raven_gallery_cover_image'] ) // update cover image field
		);
		
		foreach( $default as $key => $field ) {
			$output[$key] = update_post_meta( $post_id, $key, !isset( $_POST[$key] ) ? '' : $field );
		}
		
		// Set Cover image to featured image field
		$post_cover_image = ! isset( $_POST['raven_gallery_cover_image'] ) ? '' : $default['raven_gallery_cover_image'];

		if( $output['raven_gallery_cover_image'] ) {
			set_post_thumbnail( $post_id, $post_cover_image );

			if( empty($_POST['raven_gallery_cover_image'] ) ) {
				delete_post_thumbnail( $post_id );
			}

		}
	}
}

/**
 * Get Images
 *
 * @since 1.0.0
 */
function raven_get_images( $post_id = '', $fieldname, $args = array() ) {
	// Set defaults
	$defaults = array(
		'size'			=> 'thumbnail',
		'title'			=> get_bloginfo('name'),
		'image_array'	=> ''
	);
	$args = array_merge( $defaults, $args );

	$hero_content = array();
	$image_info = array();

	if( !$args['image_array'] ) {
		$image_ids = get_post_meta( $post_id, $fieldname, true );
		$hero_content = ! empty(get_post_meta( $post_id, 'raven_image_options', true )) ? get_post_meta( $post_id, 'raven_image_options', true ) : array();

		$image_options = ! empty(get_post_meta( $post_id, 'raven_image_options', true )) ? get_post_meta( $post_id, 'raven_image_options', true ) : array();
	}else{
		$image_ids = $args['image_array'];
	}

	if( $image_ids ) {

		foreach( explode(',', $image_ids) as $image_id ) {

			$attachment = get_post($image_id);

			if( $attachment ) {

				// Get the image size based on the thumbnail size selected.
				$thumb_size = !empty($image_options[$attachment->ID]['thumbnail']['size']) ? $image_options[$attachment->ID]['thumbnail']['size'] : '';

				if( $thumb_size ) {
					switch( $thumb_size ) {
						case 'item--hori-rect':
							$image_size = 'large'; 
							break;
						case 'item--vert-rect':
							$image_size = 'large'; 
							break;
						case 'item--square':
							$image_size = 'raven_thumbnail_extra_large'; 
							break;
						default:
							$image_size = 'raven_thumbnail_medium'; 
					}
				}else{
					$image_size = $args['size'];
				}

				array_push($image_info, array(
					'ID'			=> $image_id,
					'alt' 			=> get_post_meta($attachment->ID, '_wp_attachment_image_alt', true) !== '' ? get_post_meta($attachment->ID, '_wp_attachment_image_alt', true) : $args['title'],
					'caption' 		=> $attachment->post_excerpt,
					'description' 	=> $attachment->post_content,
					'href' 			=> wp_get_attachment_image_src( $image_id, 'full' ),
					'src' 			=> wp_get_attachment_image_src( $image_id, $image_size ),
					'title' 		=> $attachment->post_title !== '' ? $attachment->post_title : $args['title'],
				) );

			}

		}

	}

	return $image_info;

}

/**
 * Save altered fields
 *
 * @since 1.0.0
 */
function raven_can_save_meta( $post_type ) {
	global $post;

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}

	if ( isset( $_POST['post_view'] ) && 'list' === $_POST['post_view'] ) { // Input var okay.
		return false;
	}

	if ( ! isset( $_POST['post_type'] ) || $post_type !== $_POST['post_type'] ) { // Input var okay.
		return false;
	}

	if ( ! isset( $_POST[ $post_type . '_nonce' ] ) || ! wp_verify_nonce( $_POST[ $post_type . '_nonce' ], basename( __FILE__ ) ) ) { // Input var okay.
		return false;
	}

	$post_type_obj = get_post_type_object( $post->post_type );
	if ( ! current_user_can( $post_type_obj->cap->edit_post, $post->ID ) ) {
		return false;
	}

	return true;
}