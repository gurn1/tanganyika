<?php
/**
 * Part of the Raven Framework
 *
 * Layout helpers
 * Author: Luke Clifton
 * 
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) )
	die("Direct access is not permitted");


/**
 * Add a Slide Panel - Open
 *
 * @since 1.0.0
 */
function raven_helper_slide_box_open( $params = array() ) {
	$helper_fields = Raven_admin_fields::instance();
	
	$defaults = array(
		'text'		=> 'More Options',
		'text-less'	=> 'Less Options',
		'class'		=> '',
		'open'		=> false,
		'field'		=> array(
			'checkbox'	=> false,
			'name'		=> '',
			'value'		=> ''
		)
	);
	$params = wp_parse_args( $params, $defaults );
	?>
	
	<div class="raven-slide-panel-container">
		
		<?php 
		if( $params['field']['checkbox'] != true ) :
			echo '<a href="#" class="raven-slide-panel-button" data-more="'. $params['text'] .'" data-less="'. $params['text-less'] .'">'. $params['text'] .'</a>';
		else : 
			$helper_fields->checkbox( $params['field']['name'], 1, esc_html__( $params['text'], RAVEN_DOMAIN ), array( 
				'toggle-switch' => true, 
				'field' => array( 
					'class' => 'raven-slide-panel-button',
					'value'	=> array( 'supplied' => true, 'fieldvalue' => $params['field']['value'] ),
				) 
			) );	
		endif;	
		?>
		<div class="raven-slide-panel <?php echo $params['open'] == true || $params['field']['value'] == 1 ? 'open' : ''; ?> <?php echo $params['class']; ?>">
			
	<?php
}


/**
 * Add a Slide Panel - Close
 *
 * @since 1.0.0
 */
function raven_helper_slide_box_close() {
	?>
		</div>
	</div>	
	<?php
}


/**
 * Opening wrapper for tabs
 *
 * @since 1.0.0
 */
function raven_metabox_open_tab( $title, $icon = '' ) {
	?>
	<div class="raven-tab-section">
		<?php if ( ! empty( $title ) ) : ?>
			<h3 class="raven-section-title">
				<?php 
				echo $icon ? '<span class="raven-tab-icon dashicons '. $icon .'"></span>' : '';
				echo esc_html( $title ); 
				?>
			</h3>
		<?php endif; ?>
		<div class="raven-container">
	<?php
}


/**
 * Closing wrapper for tabs
 *
 * @since 1.0.0
 */
function raven_metabox_close_tab() {
	?>
		</div>
	</div>
	<?php
}


/**
 * Single image template for gallery layouts
 *
 * @since 1.0.0
 */
function raven_gallery_single_image_template() {
	return '
		<li class="raven-gallery-image __IMAGE_CLASS__" data-attachment_id="__IMAGE_ID__">
			<div class="raven-thumb-container">__IMAGE__</div>
			<div class="raven-image-actions">
				<a href="#" class="raven-image-options dashicons dashicons-admin-generic" title="'. __( 'Image Options', RAVEN_DOMAIN ) . '"></a>
				<a href="#" class="raven-image-edit dashicons dashicons-edit" title="' . __( 'Edit Image', RAVEN_DOMAIN ) . '"></a>
				<a href="#" class="raven-image-remove dashicons dashicons-no" title="' . __( 'Remove Image', RAVEN_DOMAIN ) . '"></a>
			</div>
			<a href="#" class="raven-gallery-image-cover dashicons dashicons-format-image" title="' . __( 'Set Cover Image', RAVEN_DOMAIN ) . '"></a>
		</li>';
}


/**
 * Basic image template
 *
 * @since 1.0.0
 */
function raven_single_image_template() {
	return '
		<figure id="raven_attachment_ __IMAGE_ID__" data-attachment_id=" __IMAGE_ID__" class="raven-image-metabox-preview">
			<div class="raven-image-actions">
				<a href="#" class="raven-image-edit dashicons dashicons-edit" title="' . __( 'Edit image', RAVEN_DOMAIN ) . '"></a>
				<a href="#" class="raven-single-image-remove dashicons dashicons-no" title="' . __( 'Remove image', RAVEN_DOMAIN ) . '"></a>
			</div>
			<img width="150" height="150" src="__IMAGE_URL__" class="attachment-thumbnail size-thumbnail" alt="__IMAGE_ALT__" />
		</figure>
	';
}


/**
 * Single image options popup for gallery post meta
 *
 * @since 1.0.0
 */
function raven_gallery_image_options_template() {
	global $post;

	$helper_fields = Raven_admin_fields::instance();
	
	$field_name = 'image-options';
	$attachment_id = isset($_POST['attachment_id']) ? $_POST['attachment_id'] : '';
	
	$values = get_post_meta( $_POST['post_id'], 'raven_image_options', true );
	$values = $values != '' ? $values[$attachment_id] : array();
	
	$gallery_type = get_post_meta( $_POST['post_id'], 'raven_gallery_type', true );

	?>
	<div class="raven-gallery-image-options-template">
		
		<button type="button" class="media-modal-close raven-gallery-image-options-close"><span class="media-modal-icon"><span class="screen-reader-text">Close Panel</span></span></button>

		<div class="container">
			
			<div class="media-frame-title">
				<h1>Image Options <span class="spinner"></span></h1>
			</div>
			
			<div class="frame-content">
				
				<div class="raven-gallery-image-options-content">
					<aside class="image-preview">
						<img src="
						<?php
						echo wp_get_attachment_image_src( $attachment_id, 'thumbnail')[0]; ?>
						">
					</aside>
					<div class="raven-gallery-image-options-panel-container">
						<form id="raven_gallery_image_options_form">
							
							<?php if( $gallery_type == 'standard' || $gallery_type == '' ) : ?>
							<!-- Thumbnail Panel -->
							<div class="raven-gallery-image-options-panel">
								
								<h4>Options</h4>
								
								<label class="setting" data-setting="position">
									<span class="name">Thumbnail Size</span>
									<span class="field">
										<?php 
										$options = array( 
											'' => 'Please Select',
											'item--normal' => 'Normal', 
											'item--hori-rect' => 'Horizontal Rectangle', 
											'item--vert-rect' => 'Vertical Rectangle', 
											'item--square' => 'Large Square' 
										);
	
										$helper_fields->select( 'thumb_size', $options, '', array(
											'field'	=> array(
												'name' 	=> $field_name.'['.$attachment_id.'][option][thumbnail-size]',
												'value'	=> array( 'supplied' => true, 'fieldvalue' => $values['option']['thumbnail-size'] ),
											),
											'before' => '',
											'after' => ''
										) ); ?>
									</span>
								</label>
								
								<label class="setting" data-setting="image-size">
									<span class="name">Image Size</span>
									<span class="field">
										<?php
										$helper_fields->select( 'image_size', raven_list_all_image_sizes(), '', array(
											'field'	=> array(
												'name' 	=> $field_name.'['.$attachment_id.'][option][image-size]',
												'value'	=> array( 'supplied' => true, 'fieldvalue' => $values['option']['image-size'] ),
											),
											'before' => '',
											'after' => ''
										) );
										?>
									</span>
								</label>

							</div>
							<?php endif; ?>
							
							<?php if( $gallery_type == 'slider' || $gallery_type == '' ) : ?>
							<!-- Hero Panel -->
							<div class="raven-gallery-image-options-panel">
								
								<h4>Hero Options</h4>
								
								<label class="setting" data-setting="title">
									<span class="name">Hero Title</span>
									<span class="field">
										<?php $helper_fields->input( 'hero_title', '', array( 
												'field' => array(
													'name' 	=> $field_name.'['.$attachment_id.'][hero_content][title]',
													'value'	=> array( 'supplied' => true, 'fieldvalue' => $values['hero_content']['title'] ),
													'class' => 'widefat', 
												),
												'before' 		=> '', 
												'after' 		=> '' 
											) ); ?>
									</span>
								</label>

								<label class="setting" data-setting="excerpt">
									<span class="name">Hero Excerpt</span>
									<span class="field">
										<?php $helper_fields->textarea( 'hero_excerpt', '', array(
												'field' => array(
													'name' 	=> $field_name.'['.$attachment_id.'][hero_content][excerpt]',
													'value'	=> array( 'supplied' => true, 'fieldvalue' => $values['hero_content']['excerpt'] ),
												),
												'before' 		=> '', 
												'after' 		=> '' 
											) ); ?>
									</span>
								</label>

								<div class="setting">
									<span class="name">Call to action</span>
									<span class="field">
										<?php $helper_fields->input( 'hero_cta_text', '', array( 
												'field' => array(
													'name' 	=> $field_name.'['.$attachment_id.'][hero_content][cta][buttontext]',
													'value'	=> array( 'supplied' => true, 'fieldvalue' => $values['hero_content']['cta']['buttontext'] ),
													'class'	=> 'widefat',
													'placeholder' 	=> 'Text' 
												),
												'before' 		=> '', 
												'after' 		=> '',
											) ); ?>
										<?php $helper_fields->input( 'hero_cta_url', '', array(
												'field' => array(
													'name' 	=> $field_name.'['.$attachment_id.'][hero_content][cta][buttonurl]',
													'value'	=> array( 'supplied' => true, 'fieldvalue' => $values['hero_content']['cta']['buttonurl'] ),
													'class' => 'widefat',
													'placeholder' 	=> 'URL'
												),
												'before' 		=> '', 
												'after' 		=> '',
											) ); ?>
									</span>
								</div>

								<div class="styles">
									<h4>Styling</h4>

									<label class="setting" data-setting="position">
										<span class="name">Position</span>
										<span class="field">
											<?php  
											$options = array(
												'' 			=> 'Please Select', 
												'center' 	=> 'Center', 
												'left' 		=> 'Left', 
												'right' 	=> 'Right'
											);
	
											$helper_fields->select( 'hero_position', $options, '', array(
												'field'	=> array(
													'name' 	=> $field_name.'['.$attachment_id.'][hero_content][style][position]',
													'value'	=> array( 'supplied' => true, 'fieldvalue' => $values['hero_content']['style']['position'] ),
												),
												'before'	=> '',
												'after' 	=> ''
											) ); ?>
										</span>
									</label>

									<label class="setting" data-setting="text-color">
										<span class="name">Text Colour</span>
										<span class="field">
											<?php $helper_fields->input( 'hero_gallery_text_color', '', array( 
													'field' => array(
														'name' 	=> $field_name.'['.$attachment_id.'][hero_content][style][textcolor]',
														'value'	=> array( 'supplied' => true, 'fieldvalue' => $values['hero_content']['style']['textcolor'] ),
														'class' => 'widefat', 
													),
													'before' 		=> '', 
													'after' 		=> '' 
												) ); ?>
										</span>
									</label>

								</div>
								
							</div>
							<?php endif; ?>
							
						</form>
					</div>
				</div>
			</div>

			<div class="raven-gallery-image-options-toolbar">
				<button type="button" class="button raven-gallery-image-options-submit media-button button-primary button-large media-button-select">Save attachment</button>
				<input type="hidden" name="raven_gallery_image_options_nonce" value="<?php echo wp_create_nonce('raven_gallery_image_options_nonce_value'); ?>">
			</div>
			
		</div>
		
	</div>
	
	<?php
	
}
add_action( 'wp_ajax_raven_gallery_image_options_template', 'raven_gallery_image_options_template' );


/**
 * If metabox fields are used in a table layout
 *
 * @since 1.0.0
 */
function raven_metabox_is_table( $table ) {
	
	$return = array('th-open' => '', 'th-close' => '', 'td-open' => '', 'td-close' => '');
	
	if( $table == true ){
		$return = array( 
			'th-open' 	=> '<th>', 
			'th-close'	=> '</th>', 
			'td-open' 	=> '<td>', 
			'td-close'	=> '</td>' 
		);
	}
	
	return $return;
}