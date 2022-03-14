<?php
/**
 * Contact details controller
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_contact_controller') ) {
	
	class Raven_contact_controller {
		
		public static $ID = 'raven_contact';
		
		public static $title	= 'Contact Options';
		
		public static $name 	= 'contact-options';
		
		public static $fields = array(
			'contact'	=> array(
				'office_phone' 		=> array( 'name' => 'Home Phone', 		'field' => 'input', 'icon' => 'fa-phone' ), 
				'mobile_phone' 		=> array( 'name' => 'Mobile Phone',  	'field' => 'input', 'icon' => 'fa-mobile' ),
				'opening_times'		=> array( 'name' => 'Avaliable Times', 	'field' => 'input', 'icon' => 'fa-clock'),
				'email_address' 	=> array( 'name' => 'Email Address', 	'field' => 'input', 'icon' => 'fa-envelope' ),
				'email_address_2' 	=> array( 'name' => 'Email Address 2', 	'field' => 'input', 'icon' => 'fa-envelope' ),
				'billing_address' 	=> array( 'name' => 'Address', 		 	'field' => 'textarea', 'icon' => 'fa-address-book' ),
				'vat_number'		=> array( 'name' => 'VAT Number', 		'field' => 'input', 'icon' => 'fa-building' ),
				'company_number'	=> array( 'name' => 'Company Number',	'field' => 'input', 'icon' => 'fa-building' ),
				'location'			=> array( 'name' => 'Locations Covered', 'field' => 'textarea', 'incon' => 'fa-map-marker-alt' ),
			),
			'social-networks'	=> array(
				'facebook'		=> array( 'name' => 'Facebook', 	'field' => 'input', 'icon' => 'fa-facebook' ),
				'instagram'		=> array( 'name' => 'Instagram',	'field' => 'input', 'icon' => 'fa-instagram' ),
				'twitter'		=> array( 'name' => 'Twitter', 		'field' => 'input', 'icon' => 'fa-twitter' ),
				'pinterest'		=> array( 'name' => 'Pinterest',	'field' => 'input', 'icon' => 'fa-pinterest-p' ),
				'linkedin'		=> array( 'name' => 'Linkedin', 	'field' => 'input', 'icon' => 'fa-linkedin' ),
				'youtube'		=> array( 'name' => 'YouTube', 		'field' => 'input', 'icon' => 'fa-youtube' ),
				'whatsapp'		=> array( 'name' => 'Whatsapp',		'field' => 'input', 'icon' => 'fa-whatsapp-square' ),
				'reddit'		=> array( 'name' => 'Reddit',		'field'	=> 'input', 'icon' => 'fa-reddit' ),
				'vine'			=> array( 'name' => 'Vine',			'field'	=> 'input', 'icon' => 'fa-vine' ),
				'deviantart'	=> array( 'name' => 'Devientart',	'field'	=> 'input', 'icon' => 'fa-deviantart' ),
				'flickr'		=> array( 'name' => 'Flickr',		'field'	=> 'input', 'icon' => 'fa-flickr' ),
			),
		);
		
		public $values;

		protected static $instance = null;

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {	
			$this->values = ( false !== get_option(self::$ID) ) ? get_option(self::$ID) : array();

			$this->register_admin_options();		
		}

		/**
		 * Register Admin options
		 * 
		 * @since 1.0.0
		 */
		protected function register_admin_options() {
			if( is_admin() ){
				new Raven_admin_register_option('themes.php', self::$ID, self::$title, self::$name, self::$fields);
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
		 * Social Media fields
		 *
		 * @since 1.0.0
		 */
		public function social_media_links() {
			$fields = self::$fields['social-networks'];
			$values = $this->values;
			$name 	= get_bloginfo('name');
			
			if( $fields ) {
				$output = '<ul class="social-icons">';
				
				foreach( $fields as $key => $field ){	
					if( array_key_exists($key, $values) && !empty($values[$key]) ){
						$output .= sprintf('<li class="%1$s"><a href="%2$s" title="%3$s"><i class="fab %4$s"></i>%5$s</a></li>', 
							$key, 
							$values[$key], 
							$name, 
							$field['icon'], 
							$field['name'] 
						);
					}
				}
				
				$output .= '</ul>';
				
				echo $output;
			}
			
		}
		
		/**
		 * Social Share
		 *
		 * @since 1.0.0
		 */
		public function social_share() {
			$post_title = get_the_title();
			$post_link	= urlencode( get_permalink() );

			?>
			<div id="social_share">
				<h5 class="share-title">Share This Item</h5>
				<ul>
					<li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_link; ?>" target="_blank" title="<?php echo $post_title; ?>" onclick="return !window.open(this.href, 'Facebook', 'width=500,height=500')"><i class="fab fa-facebook"></i> Facebook</a></li>

					<li><a href="http://twitter.com/home?status=<?php echo $post_title.':+'.$post_link; ?>" target="_blank" title="<?php echo $post_title; ?>" onclick="return !window.open(this.href, 'Twitter', 'width=500,height=500')"><i class="fab fa-twitter"></i> Twitter</a></li>

					<li><a href="http://pinterest.com/pin/create/link/?url=<?php echo $post_link; ?>" target="_blank" title="<?php echo $post_title; ?>" onclick="return !window.open(this.href, 'Pinterest', 'width=500,height=500')"><i class="fab fa-pinterest"></i> Pinterest</a></li>

					<li><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $post_link; ?>" target="_blank" title="<?php echo $post_title; ?>" onclick="return !window.open(this.href, 'Linkedin', 'width=500,height=500')"><i class="fab fa-linkedin"></i> Linkedin</a></li>

					<li><a href="http://tumblr.com/widgets/share/tool?canonicalUrl=<?php echo $post_link; ?>" target="_blank" title="<?php echo $post_title; ?>" onclick="return !window.open(this.href, 'Tumblr', 'width=500,height=500')"><i class="fab fa-tumblr"></i> Tumblr</a></li> 
				</ul>
			</div>
			<?php
		}
		
		/**
		 * Contact details fields
		 *
		 * @since 1.0.0
		 */
		public function contact_field( $name, $raw = false ) {
			$values = $this->values;
			
			if( $raw == true ) {
				return $values[$name];
			}
			
			if( array_key_exists($name, $values) && !empty($values[$name]) ) {
				if( $name != 'billing_address' ) {
					echo sprintf('<span class="%1$s">%2$s</span>', $name, $values[$name] ); 
				}else{
					echo sprintf('<address class="%1$s">%2$s</address>', $name, nl2br($values[$name]) );
				}
			}
			
		}

	}
}