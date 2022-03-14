<?php
/**
 * Cookie banner controller
 *
 * @since version 1.0.0
 */
 
if( ! class_exists('Raven_cookie_banner_controller') ) {
	class Raven_cookie_banner_controller {
		
		/**
		 * Option Name
		 *
		 * @since version 1.0.0
		 */
		static $cookie_ID = 'raven_cookie_compliance';

		public static $title	= 'Cookie Banner Settings';
		
		public static $name 	= 'raven-cookie-banner';
		
		public static $fields = array(
			'settings' => array(
				'header'		=> array( 'name' => 'Header', 'field' => 'input' ),
				'message'		=> array( 'name' => 'Message', 'field' => 'textarea' ),
				'dismiss'		=> array( 'name' => 'Dismiss Button', 'field' => 'input' ),
				'allow'			=> array( 'name' => 'Allow Button', 'field' => 'input' ),
				'deny'			=> array( 'name' => 'Deny Button', 'field' => 'input' ),
				'privacy_text'	=> array( 'name' => 'Privacy Policy Link Text', 'field' => 'input' ),
				'privacy_link'	=> array( 'name' => 'Privacy Policy Link', 'field' => 'page_link' ),
			)
		);
	
		/**
		 * Constructor
		 *
		 * @since version 1.0.0
		 */
		public function __construct($main = false) {
			
			if( $main == true ) {
				add_action('admin_init', array($this, 'activate') );

				// hook to the footer
				add_action('wp_footer', array($this, 'javascript_snippet'), 90 );

				// Enqueue scripts
				add_action('enqueue_scripts', array($this, 'enqueue_scripts') );

				// Enqueue stylessheets & scripts
				add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts') );

				$this->register_admin_options();
			}
			
		}
		
		/**
		 * Register Admin options
		 * 
		 * @since 1.0.0
		 */
		protected function register_admin_options() {
			if( is_admin() ){
				new Raven_admin_register_option('themes.php', self::$cookie_ID, self::$title, self::$name, self::$fields);
			}
		}

		/**
		 * Activate 
		 *
		 * @since version 1.0.0
		 */
		public function activate() {
			
			$option = get_option( self::$cookie_ID );
		
			if( ! $option ) {
				$defaults = array(
					'header'		=> '',
					'header_text'	=> sanitize_text_field('Cookies used on the website!'),
					'message'		=> sanitize_text_field('We use cookies to ensure that we give you the best experience on our website. If you continue without changing your settings, we\'ll assume that you are happy to receive all cookies on this website.'),
					'dismiss'		=> sanitize_text_field('Ok, Thanks!'),
					'allow'			=> sanitize_text_field('Allow cookies'),
					'deny'			=> sanitize_text_field('Decline'),
					'privacy_text'	=> sanitize_text_field('Learn more'),
					'privacy_link'	=> get_privacy_policy_url()
				);
				
				update_option(self::$cookie_ID, $defaults);
			}
			
		}
		
		
		/**
		 * Enqueue scripts & stylesheets
		 *
		 * @since 1.0.0
		 */
		public function enqueue_scripts() {

			wp_enqueue_script( 'raven_'.self::$cookie_ID, RAVEN_ASSETS . 'js/cookieconsent.js', array(), '1.0.0', true);
			wp_enqueue_style( 'raven_'.self::$cookie_ID, RAVEN_ASSETS . 'css/cookiecompliance.css', array(), '1.0.0' );
			
		}
		
		
		/**
		 * Javascript options
		 *
		 * @since version 1.0.0
		 */
		public function javascript_snippet() {
			
			$options = get_option( self::$cookie_ID );
			
			?>

			<script>
		
				window.cookieconsent.initialise({
					container: document.getElementById("page"),
					content: {
						  header: '<?php echo htmlspecialchars($options['header'], ENT_QUOTES); ?>',
						  message: '<?php echo htmlspecialchars($options['message'], ENT_QUOTES); ?>',
						  dismiss: '<?php echo htmlspecialchars($options['dismiss'], ENT_QUOTES); ?>',
						  allow: '<?php echo htmlspecialchars($options['allow'], ENT_QUOTES); ?>',
						  deny: '<?php echo htmlspecialchars($options['deny'], ENT_QUOTES); ?>',
						  link: '<?php echo htmlspecialchars($options['privacy_text'], ENT_QUOTES); ?> ',
						  href: '<?php echo htmlspecialchars($options['privacy_link'], ENT_QUOTES); ?>',
						  close: '&#x274c;',
					},
					law: {
					  regionalLaw: false,
					},
					location: true,
					elements: {
						header: '<span class="cc-header">{{header}}</span>&nbsp;',
						message: '<div id="cookieconsent:desc" class="cc-message"><p>{{message}}</p></div>',
						messagelink: '<div id="cookieconsent:desc" class="cc-message"><p>{{message}} <a aria-label="learn more about cookies" tabindex="0" class="cc-link" href="{{href}}" target="_blank">{{link}}</a></p></div>',
						dismiss: '<a aria-label="dismiss cookie message" tabindex="0" class="button cc-btn cc-dismiss">{{dismiss}}</a>',
						allow: '<a aria-label="allow cookies" tabindex="0" class="buton cc-btn cc-allow">{{allow}}</a>',
						deny: '<a aria-label="deny cookies" tabindex="0" class="button cc-btn cc-deny">{{deny}}</a>',
						link: '<a aria-label="learn more about cookies" tabindex="0" class="cc-link" href="{{href}}" target="_blank">{{link}}</a>',
						close: '<span aria-label="dismiss cookie message" tabindex="0" class="cc-close">{{close}}</span>',
					}
				});

			</script>

			<?php
		}
	
	}
}