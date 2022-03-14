<?php
/**
 * Shop controller - orders
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) ) {
	die("Direct access is not permitted");
}

if( ! class_exists('Raven_shop_orders_controller') ) {
	
	class Raven_shop_orders_controller {
		
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			
			// Update product status to reserved
			$update_item_status_to_reserved = array(
				'woocommerce_order_status_completed',
				'woocommerce_order_status_pending',
				'woocommerce_order_status_on-hold',
				'woocommerce_order_status_processing'
				
			);
			
			foreach( $update_item_status_to_reserved as $action ) {
				add_action( $action, array( $this, 'update_item_status_reserved' ), 1 );
			}
				
			// Remove reserved front product and republish item
			$update_item_status_to_publish = array(
				'woocommerce_order_status_failed',
				'woocommerce_order_status_refunded',
				'woocommerce_order_status_cancelled'
			);
			
			foreach( $update_item_status_to_publish as $action ) {
				add_action( $action, array( $this, 'update_item_status_publish' ), 1 );
			}
			
			// Update product status to sold after set days (cron)
			add_action( 'wp', array( $this, 'schedule_cron_task') );
			add_action( 'raven_update_item_status_sold', array( $this, 'update_item_status_sold' ) ); 

		}
		
		
		/**
		 * Schedule cron tasks to run once a day
		 *
		 * @since 1.0.0
		 */
		public function schedule_cron_task() {
			if ( ! wp_next_scheduled( 'raven_update_item_status_sold' ) ) {

				// Get tonight (which is tomorrow) at 1 minute passed twelve (00:01).
				$tonight_fair = new \DateTime();
				$tonight_fair->setTime( 22, 59, 59 );

				// schedule event
				wp_schedule_event( $tonight_fair->getTimestamp(), 'daily', 'raven_update_item_status_sold' );

			}
		}
		
		
		/**
		 * Get product id from order
		 *
		 * @since 1.0.0 
		 */
		public function get_product_ids($order_id) {
			
			$product_id = array();
			
			// Get order details
			$order = wc_get_order( $order_id );
			
			if( $order ) {
				
				// Get the items from order details
				$items = $order->get_items();

				foreach ( $items as $item ) {

					$product_id[]	= $item->get_product_id();

				}
				
			}
			
			return $product_id;
			
		}

		
		/**
		 * Update product role on newly purchased item
		 * updates to mons_reserved
		 *
		 * @since 1.0.0
		 */
		public function update_item_status_reserved( $order_id ) {
			
			foreach ( $this->get_product_ids($order_id) as $product_id ) {
				
				// get post status
				$status = get_post_status( $product_id );
				
				if( $status == 'publish' ) {
				
					wp_update_post( array(
						'ID'			=> $product_id,
						'post_status'	=> 'mons_reserved'
					) );
					
				}
				
			}
			
		}
		
		
		/**
		 * Remove reserved and republish item
		 * updates to publish
		 *
		 * @since 1.0.0
		 */
		public function update_item_status_publish( $order_id ) {
			
			foreach ( $this->get_product_ids($order_id) as $product_id ) {
				
				// get post status
				$status = get_post_status( $product_id );
				
				if( $status == ('raven_sold' || 'raven_reserved') ) {
				
					wp_update_post( array(
						'ID'			=> $product_id,
						'post_status'	=> 'publish'
					) );
					
				}
				
			}
			
		}
		
		
		/**
		 * Remove reserved and set item to sold
		 * updates to mons_sold
		 * Woocommerce order statuses: 'wc-pending', 'wc-processing', 'wc-on-hold', 'wc-completed', 'wc-cancelled', 'wc-refunded', 'wc-failed',
		 *
		 * @since 1.0.0
		 */
		public function update_item_status_sold() {
			
			$date_limit = date( 'Y-m-d', strtotime('-8 days') );
			$max_date	= date( 'Y-m-d', strtotime('-30 days') );
			
			$args = array(
				'posts_per_page'	=> -1,
				'post_status'		=> array( 'wc-processing', 'wc-complete', 'wc-on-hold' ),
				'post_type'			=> 'shop_order',
				'date_query'		=> array(
					array(
						'after'		=> $max_date,
						'before'	=> $date_limit,
						'inclusive' => true,
					)
				)
			);
			
			$orders = new WP_Query( $args );
	
			// Loop orders		
			if( $orders->have_posts() ) : while( $orders->have_posts() ) : $orders->the_post();
				global $post;
				
				$order_id = $post->ID;
					
				// Loop items
				foreach ( $this->get_product_ids($order_id) as $product_id ) {

					// get post status
					$status = get_post_status( $product_id );

					if( $status == 'raven_reserved' ) {
						
						wp_update_post( array(
							'ID'			=> $product_id,
							'post_status'	=> 'raven_sold'
						) );

					}

				}							 
											 
			endwhile; endif;
			
		}
	}
	
}
