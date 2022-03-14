<?php
/**
 * Part of the raven Framework
 *
 * Class for meta fields
 * Author: Luke Clifton
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_admin_fields') ) {
	class Raven_admin_fields extends Raven_admin {

		protected static $instance = null;
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			
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
		 * Default settings
		 *
		 * @since 1.0.0
		 */
		private function defaults( $fieldname, $esc_func = 'esc_attr', $addon = array() ) {
			
			$defaults = array(
				'before'			=> '<p class="raven-field-group">',
				'after'      		=> '</p>',
				'table'	  	  		=> false,
				'esc-func'    		=> $esc_func,
				'label-class' 		=> '',
				'default'     		=> '',
				'field'		 		=> array(
					'type'			=> 'text', // Only used for input fields
					'ID'			=> '',
					'name'			=> '',
					'class'			=> 'regular-text',
					'value'			=> array( 'supplied' => false, 'fieldvalue' => $fieldname ),
					'data-attr'		=> array( 'name'  => '', 'value' => '' ),
					'placeholder'	=> ''
				),
			);
			
			return raven_wp_parse_args( $addon, $defaults );
			
		}
		
		/**
		 * Get the field value
		 *
		 * @since 1.0.0
		 */
		private function value( $fieldvalue, $supplied = false, $default = '' ) {
			global $post;
			
			$value = '';
			
			// Get the value
			if( $supplied == false ) {

				$value = get_post_meta( $post->ID, $fieldvalue, true );
				if( !$value ) {
					$value = $default;
				}

			}else{
				$value = $fieldvalue;
			}
			
			return $value;
			
		}
		
		/**
		 * Input fields
		 *
		 * @since 1.0.0
		 */
		public function input( $fieldname, $label = '', $params = array() ) {

			// Get the defaults
			$defaults = $this->defaults( $fieldname );
			$params = raven_wp_parse_args( $params, $defaults );

			// Get the value
			$value = $this->value( 
				$params['field']['value']['fieldvalue'], // $fieldvalue
				$params['field']['value']['supplied'], // $supplied
				$params['default'] // $default
			);

			// Data attributes
			$data_name 	= $params['field']['data-attr']['name'];
			$data_value	= $params['field']['data-attr']['value'];
			
			// Placeholder
			$placeholder = $params['field']['placeholder'];

			// Get field information
			$field_id 	= $params['field']['ID'] ? esc_attr( $params['field']['ID'] ) : esc_attr( $fieldname );
			$field_name = $params['field']['name'] ? esc_attr( $params['field']['name'] ) : esc_attr( $fieldname );
			$field_data = $data_name ? 'data-'.esc_attr( $data_name ).'="'.esc_attr( $data_value ).'" ' : '';
			$field_ph 	= $placeholder ? 'placeholder="'.$placeholder.'" ' : '';

			// Build the input field
			$input = '
				<input 
					type="' . esc_attr( $params['field']['type'] ) . '"
					id="' . $field_id . '"
					name="' . $field_name . '"
					class="' . esc_attr( $params['field']['class'] ) . '"
					value="' . esc_attr( $value ) . '"'.
					$field_data. // Data attributes
					$field_ph.'
				/>
			';

			// Build the label
			$label = !empty($label) ? '<label for="'.$field_id.'" class="'.esc_attr( $params['label-class'] ).'">'.wp_kses( $label, raven_customizer_get_allowed_tags() ).'</label>' : '';

			// if isn't table layout
			if( $params['table'] == false ){

				$output = $params['before'];
				$output .= $label;
				$output .= $input;
				$output .= $params['after'];

			// if is table 
			}else{ 

				$output = '<th>'.$label.'</th>';
				$output .= '<td>'.$input.'</td>';
				
			}

			echo $output;
		}
		
		/**
		 * Textarea fields
		 *
		 * @since 1.0.0
		 */
		public function textarea( $fieldname, $label = '', $params = array() ) {

			// Get the defaults
			$defaults = $this->defaults( $fieldname );
			$params = raven_wp_parse_args( $params, $defaults );

			// Get the value
			$value = $this->value( 
				$params['field']['value']['fieldvalue'], // $fieldvalue
				$params['field']['value']['supplied'], // $supplied
				$params['default'] // $default
			);
			
			// Data attributes
			$data_name 	= $params['field']['data-attr']['name'];
			$data_value	= $params['field']['data-attr']['value'];
			
			// Placeholder
			$placeholder = $params['field']['placeholder'];
			
			// Get field information
			$field_id 	= $params['field']['ID'] ? esc_attr( $params['field']['ID'] ) : esc_attr( $fieldname );
			$field_name = $params['field']['name'] ? esc_attr( $params['field']['name'] ) : esc_attr( $fieldname );
			$field_data = $data_name ? 'data-'.esc_attr( $data_name ).'="'.esc_attr( $data_value ).'" ' : '';
			$field_ph 	= $placeholder ? 'placeholder="'.$placeholder.'" ' : '';

			// Build the textarea field
			$textarea = '
				<textarea 
					rows="6"
					id="' . $field_id . '" 
					name="' . $field_name . '" 
					class="' . esc_attr( $params['field']['class'] ) . '" '.
					$field_data. // Data attributes
					$field_ph.'
				>'.esc_html( $value ).'</textarea>
			';

			// Build the label
			$label = !empty($label) ? '<label for="'.$field_id.'" class="'.esc_attr( $params['label-class'] ).'">'.wp_kses( $label, raven_customizer_get_allowed_tags() ).'</label>' : '';

			// if isn't table layout
			if( $params['table'] == false ){

				$output = $params['before'];
				$output .= $label;
				$output .= $textarea;
				$output .= $params['after'];

			// if is table 
			}else{ 

				$output = '<th>'.$label.'</th>';
				$output .= '<td>'.$textarea.'</td>';

			}

			echo $output;
		}
		
		/**
		 * Select fields
		 *
		 * @since 1.0.0
		 */
		public function select( $fieldname, $options, $label, $params = array() ) {

			$options = (array) $options;

			// Get the defaults
			$defaults = $this->defaults( $fieldname );
			$params = raven_wp_parse_args( $params, $defaults );

			// Get the value
			$value = $this->value( 
				$params['field']['value']['fieldvalue'], // $fieldvalue
				$params['field']['value']['supplied'], // $supplied
				$params['default'] // $default
			);

			// Data attributes
			$data_name 	= $params['field']['data-attr']['name'];
			$data_value	= $params['field']['data-attr']['value'];

			// Placeholder
			$placeholder = $params['field']['placeholder'];

			// Get field information
			$field_id 	= $params['field']['ID'] ? esc_attr( $params['field']['ID'] ) : esc_attr( $fieldname );
			$field_name = $params['field']['name'] ? esc_attr( $params['field']['name'] ) : esc_attr( $fieldname );
			$field_data = $data_name ? 'data-'.esc_attr( $data_name ).'="'.esc_attr( $data_value ).'" ' : '';
			$field_ph 	= $placeholder ? 'placeholder="'.$placeholder.'" ' : '';

			// Build the input field
			$select_start = '
				<select 
					id="' . $field_id . '" 
					name="' . $field_name . '" 
					class="' . esc_attr( $params['field']['class'] ) . '" '.
					$field_data. // Data attributes
					$field_ph.'
				>
			';

			$select_end = '</select>';
	
			// Build the label
			$label = !empty($label) ? '<label for="'.$field_id.'" class="'.esc_attr( $params['label-class'] ).'">'.wp_kses( $label, raven_customizer_get_allowed_tags() ).'</label>' : '';

			// if isn't table layout
			if( $params['table'] == false ){

				$output = $params['before'];
				$output .= $label;
				$output .= $select_start;
				foreach ( $options as $opt_val => $opt_label ) :
					$output .= '<option value="'.esc_attr( $opt_val ).'" '.selected( $value, $opt_val, false ).'>'.esc_html( $opt_label ).'</option>';
				endforeach;
				$output .= $select_end;
				$output .= $params['after'];

			// if is table 
			}else{ 

				$output = '<th>'.$label.'</th>';
				$output .= '<td>';
				$output .= $select_start;
				foreach ( $options as $opt_val => $opt_label ) :
					$output .= '<option value="'.esc_attr( $opt_val ).'" '.selected( $value, $opt_val, false ).'>'.esc_html( $opt_label ).'</option>';
				endforeach;
				$output .= $select_end;
				$output .= '</td>';

			}

			echo $output;
		}
		
		/**
		 * Radio fields
		 *
		 * $fieldname is the actual name="" attribute common to all radios in the group.
		 * $optionname is the id of the radio, so that the label can be associated with it.
		 *
		 * @since 1.0.0
		 */
		public function radio( $fieldname, $optionname, $optionval, $label, $params = array() ) {

			// Get the defaults
			$defaults = $this->defaults( $fieldname, '', array(
				'before' => '<p class="raven-field-group raven-field-radio">',
				'field'  => array( 'class' => '' ) 
			) );
			$params = raven_wp_parse_args( $params, $defaults );

			// Get the value
			$value = $this->value( 
				$params['field']['value']['fieldvalue'], // $fieldvalue
				$params['field']['value']['supplied'], // $supplied
				$params['default'] // $default
			);

			// Data attributes
			$data_name 	= $params['field']['data-attr']['name'];
			$data_value	= $params['field']['data-attr']['value'];

			// Placeholder
			$placeholder = $params['field']['placeholder'];

			// Get field information
			$field_id 	= $params['field']['ID'] ? esc_attr( $params['field']['ID'] ) : esc_attr( $optionname );
			$field_name = $params['field']['name'] ? esc_attr( $params['field']['name'] ) : esc_attr( $fieldname );
			$field_data = $data_name ? 'data-'.esc_attr( $data_name ).'="'.esc_attr( $data_value ).'" ' : '';
			$field_ph 	= $placeholder ? 'placeholder="'.$placeholder.'" ' : '';

			// Build the input field
			$input = '
				<input 
					type="radio"
					id="' . $field_id . '"
					name="' . $field_name . '"
					class="radio ' . esc_attr( $params['field']['class'] ) . '"
					value="' . esc_attr( $value ) . '"'.
					$field_data. // Data attributes
					$field_ph.
					checked( $value, $optionval, false ).'
				/>
			';

			// Build the label
			$label = !empty($label) ? '<label for="'.$field_id.'" class="'.esc_attr( $params['label-class'] ).'">'.wp_kses( $label, raven_customizer_get_allowed_tags() ).'</label>' : '';

			// if isn't table layout
			if( $params['table'] == false ){

				$output = $params['before'];
				$output .= $input;
				$output .= $label;
				$output .= $params['after'];

			// if is table 
			}else{ 

				$output = '<th>'.$label.'</th>';
				$output .= '<td>'.$input.'</td>';

			}

			echo $output;
		}
		
		/**
		 * Checkbox fields
		 *
		 * @since 1.0.0
		 */
		public function checkbox( $fieldname, $value, $label, $params = array() ) {

			// Get the defaults
			$defaults = $this->defaults( $fieldname, '', array(
				'toggle-switch' => false
			) );
			$params = raven_wp_parse_args( $params, $defaults );

			// Get the value
			$checked = $this->value( 
				$params['field']['value']['fieldvalue'], // $fieldvalue
				$params['field']['value']['supplied'], // $supplied
				$params['default'] // $default
			);

			// Data attributes
			$data_name 	= $params['field']['data-attr']['name'];
			$data_value	= $params['field']['data-attr']['value'];

			// Placeholder
			$placeholder = $params['field']['placeholder'];

			// Get field information
			$field_id 	= $params['field']['ID'] ? esc_attr( $params['field']['ID'] ) : esc_attr( $fieldname );
			$field_name = $params['field']['name'] ? esc_attr( $params['field']['name'] ) : esc_attr( $fieldname );
			$field_data = $data_name ? 'data-'.esc_attr( $data_name ).'="'.esc_attr( $data_value ).'" ' : '';
			$field_ph 	= $placeholder ? 'placeholder="'.$placeholder.'" ' : '';

			// Build the input field
			$input = '
				<input 
					type="checkbox"
					id="' . $field_id . '"
					name="' . $field_name . '"
					class="check ' . esc_attr( $params['field']['class'] ) . '"
					value="' . esc_attr( $value ) . '"'.
					$field_data. // Data attributes
					$field_ph.
					checked( $checked, $value, false ).'
				/>
			';

			// If select field is a toggle switch
			$toggle_label = '';
			if( $params['toggle-switch'] == true ) {

				$params['before'] 	= '<div class="raven-toggle">';
				$toggle_label 		= '<label class="raven-toggle-switch" for="'.esc_attr( $fieldname ).'"><i></i></label>';
				$params['after'] 	= '</div>';
			}

			// The label
			$label = '<label for="' . esc_attr( $fieldname ) . '">' . wp_kses( $label, raven_customizer_get_allowed_tags() ) . '</label>';

			if( $params['table'] != true) :

				$output = $params['before'];
				$output .= $input;
				$output .= $toggle_label;
				$output .= $label;
				$output .= $params['after'];

			else :

				$output = '<th>' . $label . '</th>';
				$output .= '<td>';
				$output .= $params['before'];
				$output .= $input;
				$output .= $toggle_label;
				$output .= $params['after'];
				$output .= '</td>';

			endif;

			echo $output;
		}
		
		/**
		 * Datepicker field. Uses Jquery UI
		 *
		 * @since 1.0.0
		 */
		public function datepicker( $fieldname, $label, $value = '', $params = array() ) {
			global $post;

			$defaults = array(
			);
			$params = wp_parse_args( $params, $defaults );

			if( !$value ) {
				$value = get_post_meta( $post->ID, $fieldname, true );
			}
			
			$snippet = '
				(function($) {
					$(document).ready(function() {
						$( "#'.$fieldname.'_calendar" ).datepicker( {
							dateFormat: "yy-mm-dd",
							altField: "#'.$fieldname.'",
							defaultDate: "'.$value.'"
						} );
					} );
				} )( jQuery );
			';
			
			// Add the jquery ui datepicker script if it's not been enqueued already
			if( ! wp_script_is( 'jquery-ui-datepicker', 'enqueued' ) ) {
				wp_enqueue_script('jquery-ui-datepicker', false, array('jquery') );	
				
				wp_add_inline_script( 'jquery-ui-datepicker', $snippet );
			}else{
				// Workaround for conflict with ACF
				echo '<script>
					(function($) {
					$(document).ready(function() {
						$( "#'.$fieldname.'_calendar" ).datepicker( {
							dateFormat: "yy-mm-dd",
							altField: "#'.$fieldname.'",
							defaultDate: "'.$value.'"
						} );
					} );
					} )( jQuery );
				</script>';
			}
				
			wp_enqueue_style( 'raven_admin_jqueryui_stylesheet' );	
			
			?>

			<label><?php echo $label; ?></label>
			<div id="<?php echo $fieldname; ?>_calendar" class="raven-datepicker-skin-wordpress"></div>

			<?php 
			$this->input( $fieldname, '', array( 
				'field' => array(
					'type' => 'hidden'
				) ) 
			);

		}
		
		/**
		 * Timepicker field
		 *
		 * @since 1.0.0
		 */
		public function timepicker( $fieldname, $label, $value = '', $params = array() ) {
			global $post;

			$defaults = array(
			);
			$params = wp_parse_args( $params, $defaults );

			// Add the jquery ui stylesheet if it's not been enqueued already
			if( !wp_style_is( 'raven_admin_jqueryui_stylesheet', 'enqueued' ) ) {
				wp_enqueue_style('raven_admin_jqueryui_stylesheet');	
			}

			if( !$value ) {
				$value = get_post_meta( $post->ID, $fieldname, true );
			}

			$hour = substr( $value, 0, 2);
			$minute = substr( $value, 3, 5);
			?>

			<label><?php echo $label; ?></label>
			<div class="raven-timepicker raven-timepicker-skin-wordpress">

			<?php
			$this->select( $fieldname.'_hour', raven_admin_number_loop( 24, 1 ), 'Hour', array(
				'before'	=> '<p class="raven-field-group raven-field-dropdown">',
				'field' 	=> array(
					'class' => '',
					'value' => array( 'supplied' => true, 'fieldvalue' => $hour )
				) )
			);
			$this->select( $fieldname.'_minute', raven_admin_number_loop( 60, 5 ), 'Minute', array( 
				'before'	=> '<p class="raven-field-group raven-field-dropdown">',
				'field' 	=> array(
					'class' => '',
					'value' => array( 'supplied' => true, 'fieldvalue' => $minute )
				) )
			);

			$this->input( $fieldname, '', array( 
				'default' 	=> '00:00', 
				'field' 	=> array(
					'type' 	=> 'hidden' 
				) )
			);

			?>
			<script>
				(function($) {
					$(document).ready(function() {
				
					var hour = $('#<?php echo $fieldname; ?>_hour'),
						minute = $('#<?php echo $fieldname; ?>_minute'),
						inputField = $('#<?php echo $fieldname; ?>'); 

					hour.on('change', function(e) {
						var inputVal = inputField.val();
							inputVal = inputVal.replace(/^.{2}/g, $(this).val());

						inputField.val(inputVal);
					});

					minute.on('change', function(e) {
						var inputVal = inputField.val();
							inputVal = inputVal.substr(0, inputVal.length-2);

						inputField.val(inputVal + $(this).val());
					});

					} );
				} )( jQuery );
			</script>
			<?php

			echo '</div>';
		}
		
		/**
		 * Google Maps field
		 *
		 * @since 1.0.0
		 */
		public function map( $field_name ) {
			global $post;

			// Map Settings
			$maps = new raven_options_mapsapi();

			// Calculates longitude and latitude via postcode
			echo $maps->get_map_coords();

			$value = get_post_meta( $post->ID, $field_name, true );

			?>

			<script>
				jQuery(document).ready(function($) {
					var toggleSwitch 	= $('#raven_map_toggle'),
						container	 	= $('#raven_map_container');

					toggleSlide(toggleSwitch);

					toggleSwitch.on('change', function() {

						toggleSlide(toggleSwitch);

					});

					function toggleSlide(toggleSwitch) {
						if( toggleSwitch.prop('checked') ){
							container.slideDown();
						}else{
							container.slideUp();
						}
					}

				});
			</script>

			<?php 
			$this->checkbox( 'raven_map_toggle', 'on', esc_html__( ' Add a map?', RAVEN_DOMAIN ), array(
				'field' 		 => array(
					'name' 	 	 => $field_name.'[toggle]',
					'value'		 => array( 'supplied' => true, 'fieldvalue' => isset($value['toggle']) ? $value['toggle'] : '' )
				),
				'toggle-switch' => true
			) );

			$class = isset( $value['toggle']) == 'on' ? 'open' : '';
			
			echo '<div id="raven_map_container" class="'.$class.'">';
 
				if( !$maps->key_warning() ) : 
				
					// Postcode
					$this->input( 'raven_map_postcode', __( 'Post Code:', RAVEN_DOMAIN ), array(
						'field' 	=> array(
							'name' 	=> $field_name.'[postcode]',
							'value'	=> array( 'supplied' => true, 'fieldvalue' => isset($value['postcode']) ? esc_attr($value['postcode']) : '' )
						) ) 
					);
			
					// Latitude
					$this->input( 'raven_map_latitude', '', array(
						'field' 	=> array(
							'type'	=> 'hidden',
							'name' 	=> $field_name.'[latitude]',
							'value'	=> array( 'supplied' => true, 'fieldvalue' => isset($value['latitude']) ? esc_attr($value['latitude']) : '' )
						) ) 
					);
			
					// Longitude
					$this->input( 'raven_map_longitude', '', array(
						'field' 	=> array(
							'type'	=> 'hidden',
							'name' 	=> $field_name.'[longitude]',
							'value'	=> array( 'supplied' => true, 'fieldvalue' => isset($value['longitude']) ? esc_attr($value['longitude']) : '' )
						) ) 
					);
				
					// Display a message if the API key field is empty

					if( isset($value['latitude']) && isset($value['longitude']) ) {

						echo $maps->output_map( $value['latitude'], $value['longitude'] );

					}

				endif;

			echo '</div>';

		}
		
		/**
		 * Image Upload field
		 *
		 * @since 1.0.0
		 */
		public function image( $fieldname, $params = array() ) {
			global $post;

			// Get the defaults
			$defaults = $this->defaults( $fieldname, '', array(
				'before'  			=> '<p class="raven-field-group raven-field-image">',
				'class'				=> '',
				'button_text'		=> 'Add Image',
				'image_size'		=> 'raven_thumbnail_medium',
			) );
			$params = raven_wp_parse_args( $params, $defaults );
			
			// Get the value
			$value = $this->value( 
				$params['field']['value']['fieldvalue'], // $fieldvalue
				$params['field']['value']['supplied'], // $supplied
				$params['default'] // $default
			);

			// Get field information
			$field_name = $params['field']['name'] ? esc_attr( $params['field']['name'] ) : esc_attr( $fieldname );
			
			if( !wp_script_is('single-image-control.js', 'enqueued') ) {
				
				if( ! wp_script_is('gallery-image-control.js', 'enqueued') ) {
					wp_enqueue_media();
				}
				
				wp_enqueue_script( 'raven-admin-image-upload', RAVEN_ADMIN_ASSETS . 'js/single-image-control.js', array( 'jquery' ), true, RAVEN_VERSION );

				// Get image template
				$image_template = raven_single_image_template();

				wp_localize_script(
					'raven-admin-image-upload',
					'raven_image_control_args',
					array(
						'mediaItemTemplate'	=> $image_template,
						'post_id'			=> get_the_ID()
					)
				);
			}

			if( $post ) {
				$image_ids = $post->ID;
			}else{
				$image_ids = $value;
			}
			// Get the image data
			$image = raven_get_images( $image_ids, $fieldname, array( 
				'size' 			=> $params['image_size'], 
				'image_array' 	=> $value 
			) );

			?>

			<div class="raven-metabox-wrapper raven-metabox-image-wrapper <?php echo $params['class']; ?>">

				<div class="raven-container">

					<div class="attachments raven-image-single-thumb">
						<span class="raven-overlay-image-button <?php echo $image ? 'raven-hidden' : ''; ?>"><span class="dashicons dashicons-upload"><?php echo $params['button_text']; ?></span></span>

						<?php
						if( $image ) {
							$image = array_pop($image);

							$html = str_replace( ' __IMAGE_ID__', $image['ID'], $image_template );

							$html = str_replace( '__IMAGE_URL__', $image['src'][0], $html );
							$html = str_replace( '__IMAGE_ALT__', $image['alt'], $html );

							echo $html;

						}
						?>

					</div>

				</div>

				<button type="button" class="button raven-image-button raven-button <?php echo $image ? 'raven-hidden' : ''; ?>"><?php echo __( $params['button_text'], RAVEN_DOMAIN ); ?></button>

				<input class="raven-image-id" name="<?php echo $field_name; ?>" type="hidden" value="<?php echo isset( $image['ID'] ) ? esc_attr( $image['ID'] ) : ''; ?>" >

			</div>

			<?php

		}
		
		/**
		 * Gallery upload field
		 *
		 * @since 1.0.0
		 */
		public function gallery($name = 'images', $get_ids = array()) {
			global $post;
				
			if( $post ) {

				// Get the ids for hidden field
				$get_ids = get_post_meta( $post->ID, $name, true );

				// Get cover image id
				$cover_id = get_post_meta( $post->ID, 'raven_gallery_cover_image', true );
				$images = raven_get_images( $post->ID, $name, array( 'size' => 'medium' ));
				
			}
			?>

			<div class="raven-metabox-wrapper raven-gallery-metabox">

				<div class="raven-container raven-galleries-list">
					<span class="raven-overlay-gallery-button <?php echo $images ? 'raven-hidden' : ''; ?>"><span class="dashicons dashicons-upload">Upload Images</span></span>

					<ul class="attachments ui-sortable ui-sortable-disabled">
						<?php
						if( $images ) {

							foreach( $images as $image ) {

								$cover_class = '';
								$image_string = '<img width="150" height="150" src="' . $image['src'][0] . '" class="attachment-thumbnail size-thumbnail" alt="' . $image['alt'] . '" />';

								$html = str_replace( '__IMAGE_ID__', $image['ID'], raven_gallery_single_image_template() );

								if( $cover_id == $image['ID'] ){
									 $cover_class = 'raven-gallery-cover';
								}

								$html = str_replace( '__IMAGE_CLASS__', $cover_class, $html );
								$html = str_replace( '__IMAGE__', $image_string, $html );


								echo $html;
							}

						}
						?>
					</ul>
				</div>

				<button type="button" id="raven_insert_gallery_button" class="button raven-button"><?php echo __( 'Select Images', RAVEN_DOMAIN ); ?></button>

				<input class="raven-gallery-image-ids" name="<?php echo $name; ?>" type="hidden" value="<?php echo esc_attr( $get_ids ); ?>" >
				<input class="raven-gallery-cover-id" name="raven_gallery_cover_image" type="hidden" value="<?php echo esc_attr( $cover_id ); ?>" >

			</div>

			<?php
		}
	}
}