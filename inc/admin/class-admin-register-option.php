<?php
/**
 * Part of the Raven Framework
 *
 * Options page - Contact details
 * Author: Luke Clifton
 *
 * @since 1.0.0
 */
 
 /* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_admin_register_option') ) {
	class Raven_admin_register_option extends Raven_admin {
		
		/**
     	 * Holds the values to be used in the fields callbacks
		 *
	 	 */	
		public $ID;
		
		public $name;
		
		public $page;
		
		public $parent_slug;
		
		public $data;
		
		public $posttypes = array();
		
		public $options;
		
		/**
		 * Construct
		 *
		 */
		public function __construct($parent_slug, $option_id, $title, $name, $data ){
			// Create page
			add_action( 'admin_menu', array( $this, 'create_menu_link' ) );

			// Register the settings api
			add_action( 'admin_init', array( $this, 'page_init' ) );

			$this->ID 			= $option_id;
			$this->name			= $title;
			$this->page			= $name;
			$this->parent_slug	= $parent_slug;
			$this->data 		= $data;
			
			// Set class property
			$this->options = get_option( $this->ID );
		}

		/**
		 * Create the Menu page
		 *
		 */
		public function create_menu_link() {

			// add top level menu page
			add_submenu_page(
				$this->parent_slug,
				$this->name, 
				$this->name, 
				'manage_options', 
				$this->page, 
				array( $this, 'callback'), 
				null, 
				99 
			);
			
		}
			
		/**
		 * Register the page & page sections
		 *
		 * @since 1.0.0
		 */
		public function page_init() {
			// register a new setting
			register_setting( $this->ID, $this->ID );
			
			if( $this->data ) {
				foreach( $this->data as $name => $section ) {

					add_settings_section(
						$this->ID.'_'.$name, // ID
						ucwords(str_replace('-', ' ', $name)), // Title
						array( $this, 'section_header'), // Callback
						$this->page // Page
					);

					// create fields
					foreach( $section as $key => $field ) {
						$field_type 	= isset($field['type']) ? $field['type'] : '';
						$field_options 	= isset($field['options']) ? $field['options'] : '';
						$input_type = '';
						
						if( ! $field['field'] == ('input' || 'textarea') ) {
							$field_type = 'input';
						}
						
						if( ! $field_type ) {
							$input_type = 'text';
						}

						add_settings_field(
							$key, // ID
							$field['name'], // Title 
							array( $this, $field['field'] ), // Callback
							$this->page, // Page
							$this->ID.'_'.$name, // Section 
							array( 
								'id' 		=> $key, 
								'type' 		=> $input_type,
								'options'	=> $field_options
							)
						);

					}
				}
				
			}
				
		}	
	
		/** 
		 * Print the Section header
		 *
		 * @since version 1.0.0
		 */
		public function section_header() {
			
		}

		/**
		 * Input field
		 *
		 * @sicne 1.0.0
		 */
		public function input($args) {
			$name 	= $this->ID . '['.$args['id'].']';
			$value 	= isset($this->options[$args['id']]) ? esc_attr( $this->options[$args['id']]) : '';
			
			//echo sprintf( '<input class="regular-text" type="%1$s" id="%2$s" name="%3$s" value="%4$s">', $args['type'],  $args['id'], $name, $value );
			
			$this->fields()->input( $name, '', array(
				'field'	=> array(
					'value'	=> array( 'supplied' => true, 'fieldvalue' => $value ),
					'type'	=> $args['type']
				), 
			) );
	
		}
		
		/**
		 * Textarea field
		 *
		 * @sicne 1.0.0
		 */
		public function textarea($args) {
			$name 	= $this->ID . '['.$args['id'].']';
			$value 	= isset($this->options[$args['id']]) ? esc_attr( $this->options[$args['id']]) : '';
			
			$this->fields()->textarea( $name, '', array(
				'field'	=> array(
					'value'	=> array( 'supplied' => true, 'fieldvalue' => $value )
				),
			) );
			
	
		}
		
		/**
		 * Select
		 *
		 * @since 1.0.0
		 */
		public function select($args) {
			$name 	= $this->ID . '['.$args['id'].']';
			$value 	= isset($this->options[$args['id']]) ? esc_attr( $this->options[$args['id']]) : '';
			
			$this->fields()->select( $name, $args['options'], '', array(
				'field'	=> array(
					'value'	=> array( 'supplied' => true, 'fieldvalue' => $value )
				),
			) );
			
		}
	
		/**
		 * Radio
		 *
		 * @since 1.0.0
		 */
		public function radio($args) {
			$name 	= $this->ID . '['.$args['id'].']';
			$value 	= isset($this->options[$args['id']]) ? esc_attr( $this->options[$args['id']]) : '';
			
			$this->fields()->radio( $name, $field['options']['name'], $field['options']['value'], '', array(
				'field'	=> array(
					'value'	=> array( 'supplied' => true, 'fieldvalue' => $value )
				),
			) );
			
		}
		
		/**
		 * Checkbox field
		 *
		 * @since 1.0.0
		 */
		public function checkbox($args) {
			$name 	= $this->ID . '['.$args['id'].']';
			$value 	= isset($this->options[$args['id']]) ? esc_attr( $this->options[$args['id']]) : '';	
			
			$this->fields()->checkbox( $name, 1, '', array( 
				'toggle-switch' => true,
				'table'			=> false,
				'field' 		=> array(
					'value'		=> array( 'supplied' => true, 'fieldvalue' => $value )
				) 
			) );
		}
									
		/**
		 * Datepicker
		 *
		 * @since 1.0.0
		 */
		public function datepicker($args) {
			$name 	= $this->ID . '['.$args['id'].']';
			$value 	= isset($this->options[$args['id']]) ? esc_attr( $this->options[$args['id']]) : '';
			
			$this->fields()->datepicker( $name, '', $value );
			
		}
		
		/**
		 * Timepicker
		 *
		 * @since 1.0.0
		 */
		public function timepicker($args) {
			$name 	= $this->ID . '['.$args['id'].']';
			$value 	= isset($this->options[$args['id']]) ? esc_attr( $this->options[$args['id']]) : '';
			
			$this->fields()->timepicker( $name, '', $value );
			
		}
		
		/**
		 * Image
		 *
		 * @since 1.0.0
		 */
		public function image($args) {
			$name 	= $this->ID . '['.$args['id'].']';
			$value 	= isset($this->options[$args['id']]) ? esc_attr( $this->options[$args['id']]) : '';
			
			$this->fields()->image( $name, array(
				'field'	=> array(  
					'value'	=> array( 'supplied' => true, 'fieldvalue' => $value ),
				), 
			) );
			
		}
		
		/**
		 * Gallery
		 *
		 * @since 1.0.0
		 */
		public function gallery($args) {
			$this->fields()->gallery('raven_gallery_images');
		}

		/** 
		 * Get the settings option for the privacy link
		 *
		 * @since version 1.0.0
		 */
		public function page_link($args) {
			$name 	= $this->ID . '['.$args['id'].']';
			$value 	= isset($this->options[$args['id']]) ? esc_attr( $this->options[$args['id']]) : '';
			$get_pages = get_pages(array('sort_column' => 'menu_order', 'post_status' => 'publish'));
			$options = array();

			$options[''] = 'Please Select';

			foreach($get_pages as $page) {
				$option_value = get_permalink($page->ID);

				$options[$option_value] = $page->post_title;
			}

			$this->fields()->select( $name, $options, '', array(
				'field'	=> array(
					'value'	=> array( 'supplied' => true, 'fieldvalue' => $value )
				),
			) );
			
		}
		
		/**
		 * Creat the page content
		 *
		 * @since 1.0.0
		 */
		public function callback() {
			// check user capabilities
			//if ( ! current_user_can( 'site_management' ) ) {
			//	return;
			//}
			?>

			<div class="wrap">
				<h1><?php echo $this->name; ?></h1>
				
				<?php settings_errors(); ?>
				
				<form method="post" action="options.php">
				<?php
					// This prints out all hidden setting fields
					settings_fields( $this->ID );
			
					do_settings_sections( $this->page );
			
					submit_button();
				?>
				</form>
			</div>

			<?php	
		}
		
	}
	
}