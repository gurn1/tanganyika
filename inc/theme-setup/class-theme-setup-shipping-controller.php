<?php
/**
 * Shipping setup controller
 * runs on woocommerce activation
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_theme_setup_shipping_controller') ) {
	class Raven_theme_setup_shipping_controller {
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		function __construct() {

			add_action( 'admin_init', array($this, 'setup'), 10);
			// Woocommerce settings
			//add_action( 'woocommerce_activated_plugin', array( $this, 'setup') );
		}
		
		/**
		 * Shipping Zones
		 *
		 * @since 1.0.0
		 */
		public function shipping_zones() {
			return array(
				'United Kingdom'	=> array(
					'GB'
				),
				'Europe'			=> array(
					'UA', 'CH', 'SE', 'ES', 'SI', 'SK', 'RS', 'SM', 'RU', 'RO', 'PT', 'PL', 'NO', 'MK', 'NL', 'ME', 'MC', 'MD', 'MT', 'LU', 'LT', 'LI', 'LV', 'IT', 'IE', 'IS', 'HU', 'GL', 'GR', 'GI', 'DE', 'GE', 'FR', 'FI', 'FO', 'EE', 'DK', 'CZ', 'CY', 'HR', 'BG', 'BA', 'BE', 'AZ', 'AT', 'AD', 'AL', 'KZ', 'GE', 'CY', 'AZ', 'AM', 'VA', 'KG', 'TJ', 'UZ'
				),
				'North America'		=> array(
					'VI', 'VG', 'US', 'TC', 'TT', 'VC', 'MF', 'SX', 'LC', 'KN', 'PR', 'PA', 'NI', 'MS', 'MX', 'MQ', 'JM', 'HN', 'HT', 'GT', 'GP', 'GD', 'SV', 'DO', 'DM', 'CW', 'CU', 'CR', 'KY', 'CA', 'BZ', 'BB', 'AW', 'AI'
				),
				'South America'		=> array(
					'AR', 'BO', 'BR', 'CL', 'CO', 'EC', 'FK', 'GY', 'PY', 'PE', 'SR', 'UY', 'VE'
				),
				'Africa'			=> array(
					'DZ', 'AO', 'BJ', 'BW', 'BF', 'BI', 'CM', 'CV', 'CF', 'TD', 'KM', 'CG', 'CD', 'DJ', 'EG', 'GQ', 'ER', 'ET', 'GA', 'GM', 'GN', 'CI', 'KE', 'LS', 'LR', 'LY', 'MG', 'MW', 'ML', 'MR', 'MU', 'MA', 'MZ', 'NA', 'NE', 'NG', 'RW', 'ST', 'SN', 'SC', 'SL', 'SO', 'ZA', 'SS', 'SD', 'SZ', 'TZ', 'TG', 'TN', 'UG', 'ZM', 'ZW'
				),
				'Middle East'		=> array(
					'AF', 'BH', 'IR', 'IQ', 'IL', 'JO', 'KW', 'KG', 'LB', 'OM', 'PK', 'PS', 'QA', 'SA', 'SY', 'TJ', 'TM', 'AE', 'UZ'
				),
				'Far East'			=> array(
					'BN', 'KH', 'CN', 'HK', 'ID', 'JP', 'MY', 'MN', 'MM', 'KP', 'PH', 'SG', 'KR', 'TW', 'TH', 'VN'
				),
				'Australia Oceania'	=> array(
					'AS', 'AU', 'CK', 'FJ', 'PF', 'GU', 'KI', 'MH', 'FM', 'NR', 'NC', 'NZ', 'NU', 'NF', 'PG', 'PN', 'WS', 'SB', 'TV', 'VU', 'WF'
				)
			);	
		}
		
		/**
		 * Current zones
		 *
		 * @since 1.0.0
		 */
		public function current_zones() {
			$available_zones = WC_Shipping_Zones::get_zones();
			$available_zones_names = array();

			// Add each existing zone name into our array
			foreach ($available_zones as $zone ) {
				if( !in_array( $zone['zone_name'], $available_zones_names ) ) {
					$available_zones_names[] = $zone['zone_name'];
				}
			}
			
			return $available_zones_names;
		}
		
		/**
		 * Setup woocommerce shipping zones
		 *
		 * @since 1.0.0
		 */
		public function setup() {
			if( WC_Shipping_Zones::get_zones() ) {
				return;
			}

			if( class_exists( 'woocommerce' ) ) {
				foreach( $this->shipping_zones() as $region => $countries ) {
					if( ! in_array($region, $this->current_zones()) ) {
						$zone_object = new WC_Shipping_Zone();
						$zone_object->set_zone_name( $region );

						foreach( $countries as $country ) {
							$zone_object->add_location( $country, 'country' );
						}

						$zone_object->save();

						if( class_exists('WC_RoyalMail') ) {
							$zone_object->add_shipping_method('royal_mail');
						}	
					}
				}
			}
		} 
		
	}
	
}