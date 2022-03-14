<?php
/**
 * Sending forms controller 
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_forms_controller') ) {
	class Raven_forms_controller {
		static $ID 		= 'raven_forms';
		static $title	= 'Form Options';
		static $name 	= 'form-options';
		
		static $form_name	= 'raven_contact_form'; 
		
		static $fields = array(
			'recaptcha'		=> array(
				'site_key'		=> array( 'name' => 'Site Key', 	'field' => 'input' ),
				'secret_key'	=> array( 'name' => 'Secret Key', 	'field' => 'input' )
			)
		);
		
		public $values;
		
		protected static $instance = null;

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			$this->values = get_option(self::$ID);
			
			// Change email names from Wordpress
			add_filter( 'wp_mail_from_name', array( $this, 'change_email_name' ) );
			
			// Change outgoing email address
			add_filter( 'wp_mail_from', array( $this, 'change_email_address' ) );
			
			// Process the form
			add_action( 'wp', array( $this, 'submit_form' ) );

			// Ajax submission - Primary form
			add_action( 'wp_ajax_nopriv_submit_primary_form', array( $this, 'submit_primary_form' ) );
			add_action( 'wp_ajax_submit_primary_form', array( $this, 'submit_primary_form' ) );
			
			if( is_admin() ) {
				$this->register_admin_options();
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
		 * Register Admin options
		 * 
		 * @since 1.0.0
		 */
		protected function register_admin_options() {
			if( is_admin() ) {
				new Raven_admin_register_option('themes.php', self::$ID, self::$title, self::$name, self::$fields);
			}
		}
		
		/** 
		 * Change email names from Wordpress
		 *
		 * @since 1.0.0
		 */
		public function change_email_name( $email ){
			return get_bloginfo('name');
		}
	
		/**
		 * Change outgoing email address
		 *
		 * @since 1.0.0
		 */
		public function change_email_address( $original_email_address ) {
			return get_bloginfo('admin_email');
		}
		
		/**
		 * Inline CSS
		 *
		 * @since 1.0.0
		 */
		public function inline_css($content) {
			ob_start();
			wc_get_template( 'emails/email-styles.php' );
			$css = ob_get_clean();

			// apply CSS styles inline for picky email clients.
			try {
				$emogrifier = new Emogrifier( $content, $css );
				$content    = $emogrifier->emogrify();
			} catch ( Exception $e ) {
				$logger = wc_get_logger();
				$logger->error( $e->getMessage(), array( 'source' => 'emogrifier' ) );
			}
			
			return $content;
		}

		/**
		 * Send via Ajax
		 *
		 * @since 1.0.0
		 */
		public function submit_primary_form() {	
			// Get nonce
			$nonce 		= isset($_POST['security']) ? $_POST['security'] : '';
			$form		= $_POST['form_data'];
			
			parse_str( $form, $form_data);
			
			$recaptcha	= isset($form_data['g-recaptcha-response']) ? $form_data['g-recaptcha-response'] : '';
			//$form_data = isset($form_data[self::$form_name]) ? $form_data[self::$form_name] : array();
			//$form_data = $form_data + array( 'g-recaptcha-response' => $recaptcha);
			
			// Process form data
			$submit = $this->process_form($nonce, $form_data);
			
			$output = array( 
				'html' 		=> raven_framework()->notices()->global_notice($submit, true),
				'response'	=> $submit['notice'] == 'sent' ? 'sent' : 'error'
			);
			
			echo json_encode($output);
			exit;
		}

		/**
		 * Send via post
		 * Fallback for browsers with javascript disabled
		 *
		 * @since 1.0.0
		 */
		public function submit_form() {
			$action = isset($_POST['action']) ? $_POST['action'] : '';
			
			if( 'POST' == $_SERVER['REQUEST_METHOD'] && $action == self::$form_name ) {
				// Get nonce
				$nonce 	= $_REQUEST['_wpnonce'];
				
				// Form data 
				$form_data	= isset($_POST[self::$form_name]) ? $_POST[self::$form_name] : array();
				var_dump($form_data);
				var_dump($_POST);
				// Redirect url
				$url = get_permalink();
				
				// Process form data
				$submit = $this->process_form($nonce, $form_data);
				
				$url = add_query_arg( 'action', $submit['notice'] );
				
				// Redirect with message
				wp_safe_redirect( $url );
				
			}
		}
		
		/**
		 * Submit form to admin
		 *
		 * @since 1.0.0
		 */
		public function process_form($nonce, $form_data) {

			if( ! wp_verify_nonce( $nonce, self::$form_name ) ) {
				die( 'Failed security check' );
			}

			if( intval( $this->recaptcha_response($form_data['g-recaptcha-response']) == 1 ) ) {
				$output = array();

				//if( class_exists( 'Raven_contact_controller' ) ) {
				//	$to 	= get_option(Raven_contact_controller::$ID);
				//	$to		= isset($to['email_address']) ? $to['email_address'] : get_bloginfo('admin_email');
				//} else {
					$to		= get_bloginfo('admin_email');
				//}

				if( is_array($form_data) ) {
					foreach( $form_data as $key => $field ) {
						$output[$key] = sanitize_text_field($field);
					}
				}

				// Headers
				$headers 	= 'Content-Type: text/html; charset=UTF-8';
				$headers	.= 'Reply-To: ' . $email;
				$email_heading = 'Contact form';

				// Form data
				$name 		= isset( $output['raven_name'] ) ? 			$output['raven_name'] 			: '';
				$email		= isset( $output['raven_email'] ) ? 		$output['raven_email'] 			: '';
				$phone		= isset( $output['raven_phone'] ) ?	 		$output['raven_phone'] 			: '';
				$subject	= isset( $output['raven_subject'] ) ? 		$output['raven_subject'] 		: 'Contact Form Enquiry';
				$order_no	= isset( $output['raven_order_number'] ) ? 	$output['raven_order_number']	: '';
				$message	= isset( $output['raven_message'] ) ?	 	$output['raven_message'] 		: '';

				ob_start();

				// Email Header
				include RAVEN_TEMPLATE_PART . "emails/email-header.php";

				// Email Body
				include RAVEN_TEMPLATE_PART . "emails/email-primary-form.php";

				// Email Footer
				include RAVEN_TEMPLATE_PART . "emails/email-footer.php";

				$email_body = ob_get_contents();

				ob_end_clean();

				//$email_body = $this->inline_css($email_body);

				wp_mail( $to, $subject, $email_body, $headers );

				$return = array( 'notice' => 'sent' );		
			} else {	
				$return = array( 'notice' => 'wrongcaptcha' );
			}
			return $return;
		}

		/**
		 * Recaptcha response
		 *
		 * @since 1.0.0
		 */
		public function recaptcha_response($captcha) {
			
			if( $this->values['site_key'] && $this->values['secret_key'] ) {
				
				$data = array(
					'secret' 	=> $this->values['secret_key'],
					'response' 	=> $captcha
				);

				// Google recaptcha
				$verify = curl_init();
				curl_setopt( $verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify" );
				curl_setopt( $verify, CURLOPT_POST, true );
				curl_setopt( $verify, CURLOPT_POSTFIELDS, http_build_query( $data ) );
				curl_setopt( $verify, CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $verify, CURLOPT_RETURNTRANSFER, true );
				$response = curl_exec( $verify );

				$responseKeys = json_decode( $response,true );

				return $responseKeys['success'];
				
			} else {
				return 1;
			}

		}
		
		/**
		 * Primary form
		 *
		 * @since 1.0.0
		 */
		public function primary_form() {
			$site_key = isset($this->values['site_key']) ? $this->values['site_key'] : '';
			$secret_key	= isset($this->values['secret_key']) ? true : false;
	
			if( $site_key && $secret_key ) {
				
				// Recaptcha
				if( ! wp_style_is( 'raven-recaptcha', 'enqueued' ) ) {
					wp_enqueue_script( 'raven-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), true );
				}
				
			}

			get_template_part( 'template-parts/forms/form', 'primary', array('site_key' => $site_key, 'form_name' => self::$form_name));
		}
	
	}
	
}