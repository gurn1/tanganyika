<?php
/**
 * Helper functions for media
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) )
	die("Direct access is not permitted");


if( ! function_exists('raven_theme_hero_slider') ) :
	/**
	 * Hero slider
	 *
	 * @since 1.0.0
	 */
	function raven_theme_hero_slider( $field_name ) {
		global $post;

		$images = raven_get_gallery_image_data();

		if($images) {
			echo '<ul class="slides">';

			foreach($images as $image) {
				//require RAVEN_TEMPLATE_PART . 'sliders/hero-slider.php';
				get_template_part('template-parts/sliders/slider', 'hero', $image);
			}

			echo '</ul>';
		}
	}
endif;

if( ! function_exists('raven_get_gallery_image_data') ) :
	/**
	 * Hero slider
	 *
	 * @since 1.0.0
	 */
	function raven_get_gallery_image_data() {
		global $post;

		$default_title = get_bloginfo('name');

		$image_ids = get_post_meta($post->ID, 'raven_gallery_images', true);
		$image_options = get_post_meta($post->ID, 'raven_image_options', true);
		$image_output = array();

		if($image_ids) {

			foreach( explode(',', $image_ids) as $image_id ) {
				$attachment = get_post($image_id);

				if($attachment) {
					$image_size = !empty($image_options[$attachment->ID]['option']['image-size']) ? $image_options[$attachment->ID]['option']['image-size'] : '';

					array_push($image_output, array(
						'ID'			=> $image_id,
						'alt' 			=> get_post_meta($attachment->ID, '_wp_attachment_image_alt', true) !== '' ? get_post_meta($attachment->ID, '_wp_attachment_image_alt', true) : $default_title,
						'caption' 		=> $attachment->post_excerpt,
						'description' 	=> $attachment->post_content,
						'href' 			=> wp_get_attachment_image_src( $image_id, 'full' ),
						'src' 			=> wp_get_attachment_image_src( $image_id, $image_size ),
						'title' 		=> $attachment->post_title !== '' ? $attachment->post_title : $default_title,
						'hero-content' 	=> array(
							'title'				=> ! empty($image_options[$attachment->ID]['hero_content']['title']) ? $image_options[$attachment->ID]['hero_content']['title'] : '',
							'excerpt' 			=> ! empty($image_options[$attachment->ID]['hero_content']['excerpt']) ? $image_options[$attachment->ID]['hero_content']['excerpt'] : '',
							'cta'				=> array(
								'text'			=> ! empty($image_options[$attachment->ID]['hero_content']['cta']['buttontext']) ? $image_options[$attachment->ID]['hero_content']['cta']['buttontext'] : '',
								'url'			=> ! empty($image_options[$attachment->ID]['hero_content']['cta']['buttonurl']) ? $image_options[$attachment->ID]['hero_content']['cta']['buttonurl'] : ''
							),
							'style'				=> array(
								'position' 		=> ! empty($image_options[$attachment->ID]['hero_content']['style']['position']) ? $image_options[$attachment->ID]['hero_content']['style']['position'] : '',
								'text-color' 	=> ! empty($image_options[$attachment->ID]['hero_content']['style']['textcolor']) ? $image_options[$attachment->ID]['hero_content']['style']['textcolor'] : ''
							),
						),
						'thumbnail'		=> array(
							'size'				=> !empty($image_options[$attachment->ID]['option']['thumbnail-size']) ? $image_options[$attachment->ID]['option']['thumbnail-size'] : ''
						)
					));
				}
			}
		}

		return $image_output;

	}
endif;


if( ! function_exists('raven_theme_payment_icons') ) :
	/**
	 * Payment icons
	 *
	 * @since 1.0.0
	 */
	function raven_theme_payment_icons() {
		
		if( class_exists('woocommerce') ) :
		
			$options = array(
				'type'	=> '.svg',
				'color'	=> 'dark',
				'size'	=> '45px'
			);

			$icons = array(
				'visa',
				//'visa-electron',
				'mastercard',
				'maestro',
				//'cirrus',
				'american-express',
				'paypal',
				//'western-union',
				//'worldpay'
				//'skrill',
				//'amazon',
				//'direct-debit',
				//'discover',
				//'jcb',
				//'sage',
			);

			$url = RAVEN_ASSETS . 'images/payment-icons/' . $options['color'] . '/'; 

			$output = '<ul class="payment-icons">';

			foreach( $icons as $icon ) {
				$icon_url = $url . $icon . $options['type'];

				$output .= sprintf( '<li><img src="%1s" width="%2s" alt="%3s accepted"></li>', 
					$icon_url, 
					$options['size'], 
					ucfirst( str_replace('-', ' ', $icon) )
				); 	
			}


			$output .= '</ul>';

			echo $output;
		
		endif;
		
	}
endif;