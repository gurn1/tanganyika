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

			//add_action( 'admin_init', array($this, 'update_current_instance'));

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
			if( class_exists( 'woocommerce' ) ) {
				if( WC_Shipping_Zones::get_zones() ) {
					return;
				}

				foreach( $this->shipping_zones() as $region => $countries ) {
					if( ! in_array($region, $this->current_zones()) ) {
						$zone_object = new WC_Shipping_Zone();
						$zone_object->set_zone_name( $region );

						foreach( $countries as $country ) {
							$zone_object->add_location( $country, 'country' );
						}

						$zone_id = $zone_object->save();

						if( class_exists( 'WC_Table_Rate_Shipping' ) ) {
							$instance_id = $zone_object->add_shipping_method('table_rate');
							
							$this->table_rate_shipping_data($instance_id);
						}

					}
				}
			}
		}

		/**
		 * Update current instance 
		 * 
		 * @since 1.0.0
		 */
		public function update_current_instance() {
			$current_instance = isset($_GET['instance_id']) ? $_GET['instance_id'] : 0;
			$update = isset($_GET['raven-update-instance']) ? $_GET['raven-update-instance'] : false;
			
			if( (isset($_GET['page']) && $_GET['page'] == 'wc-settings') && (isset($_GET['tab']) && ($_GET['tab'] == 'shipping') && $current_instance > 0) && $update == true ) {
				$this->table_rate_shipping_data($current_instance);
			}
		}

		/**
		 * Add table data for table rate shipping
		 * 
		 * @since 1.0.0
		 */
		public function table_rate_shipping_data($shipping_method_id) {
			if( class_exists( 'WC_Table_Rate_Shipping' ) && class_exists( 'woocommerce' ) ) {
				global $wpdb;
		
				if( $shipping_method_id == 0 ) {
					return;
				}
				
				$data = array(
					array( 'rate_condition' => 'Weight', 'rate_min' => 0, 'rate_max' =>  0.1 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 0.101, 'rate_max' =>  0.250 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 0.251, 'rate_max' =>  0.500 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 0.501, 'rate_max' =>  0.750 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 0.751, 'rate_max' =>  1 ),
					//
					array( 'rate_condition' => 'Weight', 'rate_min' => 1.001, 'rate_max' =>  1.250 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 1.251, 'rate_max' =>  1.500 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 1.501, 'rate_max' =>  1.750 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 1.751, 'rate_max' =>  2 ),
					//
					array( 'rate_condition' => 'Weight', 'rate_min' => 2.001, 'rate_max' =>  2.250 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 2.251, 'rate_max' =>  2.500 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 2.501, 'rate_max' =>  2.750 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 2.751, 'rate_max' =>  3 ),
					//
					array( 'rate_condition' => 'Weight', 'rate_min' => 3.001, 'rate_max' =>  3.250 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 3.251, 'rate_max' =>  3.500 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 3.501, 'rate_max' =>  3.750 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 3.751, 'rate_max' =>  4 ),
					//
					array( 'rate_condition' => 'Weight', 'rate_min' => 4.001, 'rate_max' =>  4.250 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 4.251, 'rate_max' =>  4.500 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 4.501, 'rate_max' =>  4.750 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 4.751, 'rate_max' =>  5 ),
					//
					array( 'rate_condition' => 'Weight', 'rate_min' => 5.001, 'rate_max' =>  5.500 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 5.501, 'rate_max' =>  6 ),
					//
					array( 'rate_condition' => 'Weight', 'rate_min' => 6.001, 'rate_max' =>  6.500 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 6.501, 'rate_max' =>  7 ),
					//
					array( 'rate_condition' => 'Weight', 'rate_min' => 7.001, 'rate_max' =>  7.500 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 7.501, 'rate_max' =>  8 ),
					//
					array( 'rate_condition' => 'Weight', 'rate_min' => 8.001, 'rate_max' =>  8.500 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 8.501, 'rate_max' =>  9 ),
					//
					array( 'rate_condition' => 'Weight', 'rate_min' => 9.001, 'rate_max' =>  9.500 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 9.501, 'rate_max' =>  10 ),
					//
					array( 'rate_condition' => 'Weight', 'rate_min' => 10.001, 'rate_max' =>  15 ),
					array( 'rate_condition' => 'Weight', 'rate_min' => 15.001, 'rate_max' =>  20 ),
				);

				$max_key = count($data) - 1;

				for( $i = 0; $i <= $max_key; $i++ ) {
					$rate_condition = $data[$i]['rate_condition'];
					$rate_min = $data[$i]['rate_min'];
					$rate_max = $data[$i]['rate_max'];

					$result = $wpdb->insert(
						$wpdb->prefix . 'woocommerce_shipping_table_rates',
						array(
							'rate_condition'            => sanitize_title( $rate_condition ),
							'rate_min'                  => $rate_min,
							'rate_max'                  => $rate_max,
							'rate_cost'                	=> 0,
							'rate_cost_per_item'        => 0,
							'rate_cost_per_weight_unit' => 0,
							'rate_cost_percent'         => 0,
							'rate_label'                => '',
							'rate_priority'             => 0,
							'rate_order'                => $i,
							'shipping_method_id'        => $shipping_method_id,
							'rate_abort'                => 0,
							'rate_abort_reason'         => 0,
						),
						array(
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s',
							'%d',
							'%d',
							'%d',
							'%s',
						)
					);
				}
			}


		}
		
	}
	
}