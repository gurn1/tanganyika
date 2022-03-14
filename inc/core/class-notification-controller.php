<?php
/**
 * This page controls the notices throughout the site. 
 *
 * @since 3.1.0
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


if( ! class_exists( 'Raven_notification_controller' ) ) {
	
	class Raven_notification_controller  {	
		
		public $success;
		public $errors;
		public $values;

		public $action = '';
		
		protected static $instance = null;
		
		/**
		 * Class Constructor
		 *
		 */
		public function __construct() {
			$this->action = isset($_GET['action']) ? $_GET['action'] : '';			
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
		 * Global Notices for header
		 *
		 * @since 1.0.0
		 */
		public function global_notice($data = false, $return = false) {
			if( is_array($data) ) {
				$this->action = $data['notice'];
			}
			
			if( ! $this->action ) {
				return;
			}

			$messages = array();
			$header = '';
			$text = '';
			$class  = '';
			$output = array();

			// action messages
			switch($this->action) {
				case "sent":
					$class	= 'success';
					$icon	= '<i class="fas fa-check"></i>';
					$header = 'Thank you!';
					$text = 'Your message was sent successfully.';
					break;
				case "noaccess":
					$header = 'Error';
					$icon	= '<i class="fas fa-times"></i>';
					$text = 'No access!';
					break;
				case "loggedin":
					$header = 'Error';
					$icon	= '<i class="fas fa-times"></i>';
					$text = 'You must be logged in.';
					break;
				case "wrongcaptcha":
					$header = 'Error';
					$icon	= '<i class="fas fa-times"></i>';
					$text = 'Failed reCAPTCHA verify check.';
					break;
				case "permission":
					$header = 'Error';
					$icon	= '<i class="fas fa-times"></i>';
					$text = 'It appears you don\'t have permission to do that.';
			};
			
			if($text) {
				$messages[] = array( 
					'id' 		=> $this->action, 
					'icon' 		=> $icon, 
					'header'	=> $header, 
					'text' 		=> $text, 
					'class' 	=> $class
				);
				
				foreach( $messages as $message ) {
					$template =  sprintf(
						'<div id="global_notice_%1$s" class="global-notice alert %2$s container large">
							<span class="icon">%3$s</span>
							<h4 class="notice-header">%4$s</h4>
							<span class="message">%5$s</span>
						</div>',
						$message['id'],
						$message['class'],
						$message['icon'],
						$message['header'],
						$message['text'],
					);
	
					if( $return == true ) {
						$output[] = $template; 
					} else {
						echo $template;
					}				
				}
				
				return $output;	
			}
		
		}
						
	}
	
}
