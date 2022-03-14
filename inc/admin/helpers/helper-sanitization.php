<?php
/**
 * Sanitization functions.
 * 
 * @since 1.0.0
 */


/**
 * Sanitize a multidimensional array
 *
 * @uses htmlspecialchars
 *
 * @param (array)
 * @return (array) the sanitized array
 *
 * @since 1.0.0
 */
function raven_sanitize_array($data = array()) {
	if (!is_array($data) || !count($data)) {
		return array();
	}
	foreach ($data as $k => $v) {
		if (!is_array($v) && !is_object($v)) {
			$data[$k] = htmlspecialchars(trim($v));
		}
		if (is_array($v)) {
			$data[$k] = raven_sanitize_array($v);
		}
	}
	return $data;
}


/**
 * Sanitizes a checkbox value.
 *
 * @param int|string|bool $input Input value to sanitize.
 * @return int|string Returns 1 if $input evaluates to 1, an empty string otherwise.
 */
function raven_sanitize_checkbox( $input ) {
	if ( $input == 1 ) {
		return 1;
	}

	return '';
}


/**
 * Sanitizes a checkbox value. Value is passed by reference.
 *
 * Useful when sanitizing form checkboxes. Since browsers don't send any data when a checkbox
 * is not checked, raven_sanitize_checkbox() throws an error.
 * raven_sanitize_checkbox_ref() however evaluates &$input as null so no errors are thrown.
 *
 * @param int|string|bool &$input Input value to sanitize.
 * @return int|string Returns 1 if $input evaluates to 1, an empty string otherwise.
 */
function raven_sanitize_checkbox_ref( &$input ) {
	if ( $input == 1 ) {
		return 1;
	}

	return '';
}


/**
 * Sanitizes integer input while differentiating zero from empty string.
 *
 * @param int|string $input Input value to sanitize.
 * @return int|string Integer value, 0, or an empty string otherwise.
 */
function raven_customizer_sanitize_intval_or_empty( $input ) {
	if ( $input === false || $input === '' ) {
		return '';
	}

	if ( $input == 0 ) {
		return 0;
	}

	return intval( $input );
}


/**
 * Returns a sanitized hex color code.
 *
 * @param string $str The color string to be sanitized.
 * @param bool $return_hash Whether to return the color code prepended by a hash.
 * @param string $return_fail The value to return on failure.
 * @return string A valid hex color code on success, an empty string on failure.
 *
 * @since 1.0.0
 */
function raven_sanitize_hex_color( $str, $return_hash = true, $return_fail = '' ) {
	if( $str === false || empty( $str ) || $str == 'false' ) {
		return $return_fail;
	}

	// Allow keywords and predefined colors
	if ( in_array( $str, array( 'transparent', 'initial', 'inherit', 'black', 'silver', 'gray', 'grey', 'white', 'maroon', 'red', 'purple', 'fuchsia', 'green', 'lime', 'olive', 'yellow', 'navy', 'blue', 'teal', 'aqua', 'orange', 'aliceblue', 'antiquewhite', 'aquamarine', 'azure', 'beige', 'bisque', 'blanchedalmond', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgrey', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkslategrey', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dimgrey', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'greenyellow', 'grey', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgray', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightslategrey', 'lightsteelblue', 'lightyellow', 'limegreen', 'linen', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'oldlace', 'olivedrab', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'skyblue', 'slateblue', 'slategray', 'slategrey', 'snow', 'springgreen', 'steelblue', 'tan', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'whitesmoke', 'yellowgreen', 'rebeccapurple' ) ) ) {
		return $str;
	}

	// Include the hash if not there.
	// The regex below depends on in.
	if ( substr( $str, 0, 1 ) != '#' ) {
		$str = '#' . $str;
	}

	preg_match( '/(#)([0-9a-fA-F]{6})/', $str, $matches );

	if ( count( $matches ) == 3 ) {
		if ( $return_hash ) {
			return $matches[1] . $matches[2];
		} else {
			return $matches[2];
		}
	}

	return $return_fail;
}


/**
 * Returns a sanitized RGBA color code
 *
 * @since 1.0.0
 */
function raven_sanitize_rgba_color( $str, $return_hash = true, $return_fail = '' ) {
	if ( false === $str || empty( $str ) || 'false' === $str ) {
		return $return_fail;
	}

	// Allow keywords and predefined colors
	if ( in_array( $str, array( 'transparent', 'initial', 'inherit', 'black', 'silver', 'gray', 'grey', 'white', 'maroon', 'red', 'purple', 'fuchsia', 'green', 'lime', 'olive', 'yellow', 'navy', 'blue', 'teal', 'aqua', 'orange', 'aliceblue', 'antiquewhite', 'aquamarine', 'azure', 'beige', 'bisque', 'blanchedalmond', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgrey', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkslategrey', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dimgrey', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'greenyellow', 'grey', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgray', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightslategrey', 'lightsteelblue', 'lightyellow', 'limegreen', 'linen', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'oldlace', 'olivedrab', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'skyblue', 'slateblue', 'slategray', 'slategrey', 'snow', 'springgreen', 'steelblue', 'tan', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'whitesmoke', 'yellowgreen', 'rebeccapurple' ), true ) ) {
		return $str;
	}

	preg_match( '/rgba\(\s*(\d{1,3}\.?\d*\%?)\s*,\s*(\d{1,3}\.?\d*\%?)\s*,\s*(\d{1,3}\.?\d*\%?)\s*,\s*(\d{1}\.?\d*\%?)\s*\)/', $str, $rgba_matches );
	if ( ! empty( $rgba_matches ) && 5 === count( $rgba_matches ) ) {
		for ( $i = 1; $i < 4; $i++ ) {
			if ( strpos( $rgba_matches[ $i ], '%' ) !== false ) {
				$rgba_matches[ $i ] = raven_sanitize_0_100_percent( $rgba_matches[ $i ] );
			} else {
				$rgba_matches[ $i ] = raven_sanitize_0_255( $rgba_matches[ $i ] );
			}
		}
		$rgba_matches[4] = raven_sanitize_0_1_opacity( $rgba_matches[ $i ] );
		return sprintf( 'rgba(%s, %s, %s, %s)', $rgba_matches[1], $rgba_matches[2], $rgba_matches[3], $rgba_matches[4] );
	}

	// Not a color function either. Let's see if it's a hex color.

	// Include the hash if not there.
	// The regex below depends on in.
	if ( substr( $str, 0, 1 ) !== '#' ) {
		$str = '#' . $str;
	}

	preg_match( '/(#)([0-9a-fA-F]{6})/', $str, $matches );

	if ( 3 === count( $matches ) ) {
		if ( $return_hash ) {
			return $matches[1] . $matches[2];
		} else {
			return $matches[2];
		}
	}

	return $return_fail;
}


/**
 * Return a list of allowed tags and attributes for a given context.
 *
 * @param string $context The context for which to retrieve tags.
 *                        Currently available contexts: guide
 * @return array List of allowed tags and their allowed attributes.
 *
 * @since 1.0.0
 */
function raven_customizer_get_allowed_tags( $context = '' ) {
	$allowed = array(
		'a'       => array(
			'href'   => true,
			'title'  => true,
			'class'  => true,
			'target' => true,
		),
		'abbr'    => array( 'title' => true, ),
		'acronym' => array( 'title' => true, ),
		'b'       => array( 'class' => true, ),
		'br'      => array(),
		'code'    => array( 'class' => true, ),
		'em'      => array( 'class' => true, ),
		'i'       => array( 'class' => true, ),
		'img'     => array(
			'alt'    => true,
			'class'  => true,
			'src'    => true,
			'width'  => true,
			'height' => true,
		),
		'li'      => array( 'class' => true, ),
		'ol'      => array( 'class' => true, ),
		'p'       => array( 'class' => true, ),
		'pre'     => array( 'class' => true, ),
		'span'    => array( 'class' => true, ),
		'strong'  => array( 'class' => true, ),
		'ul'      => array( 'class' => true, ),
	);

	switch ( $context ) {
		case 'guide':
			unset( $allowed['p'] );
			break;
		default:
			break;
	}

	return apply_filters( 'raven_customizer_get_allowed_tags', $allowed, $context );
}


/**
 * Sanitize user-provided CSS code, as recommended in https://make.wordpress.org/themes/2015/02/10/custom-css-boxes-in-themes/
 *
 * @param string $string The CSS code to sanitize.
 * @return string
 */
function raven_customizer_sanitize_custom_css( $string ) {
	$string = wp_strip_all_tags( $string, false );

	return $string;
}


/**
 * Get choices for text align select field
 *
 * @since 1.0.0
 */
function raven_get_text_align_choices() {
	return apply_filters( 'raven_text_align_choices', array(
		'left'   => esc_html__( 'Left', RAVEN_DOMAIN ),
		'center' => esc_html__( 'Center', RAVEN_DOMAIN ),
		'right'  => esc_html__( 'Right', RAVEN_DOMAIN ),
	) );
}


/**
 * Get choices for text align select field
 *
 * @since 1.0.0
 */
function raven_get_image_repeat_choices() {
	return apply_filters( 'raven_image_repeat_choices', array(
		'no-repeat' => esc_html__( 'No repeat', RAVEN_DOMAIN ),
		'repeat'    => esc_html__( 'Tile', RAVEN_DOMAIN ),
		'repeat-x'  => esc_html__( 'Tile Horizontally', RAVEN_DOMAIN ),
		'repeat-y'  => esc_html__( 'Tile Vertically', RAVEN_DOMAIN ),
	) );
}


/**
 * Sanitize choices for text align select field
 *
 * @since 1.0.0
 */
function raven_sanitize_image_repeat( $value ) {
	$choices = raven_get_image_repeat_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return apply_filters( 'raven_sanitize_image_repeat_default', 'no-repeat' );
}


/**
 * Get choices for image position x select field
 *
 * @since 1.0.0
 */
function raven_get_image_position_x_choices() {
	return apply_filters( 'raven_image_position_x_choices', array(
		'left'   => esc_html__( 'Left', RAVEN_DOMAIN ),
		'center' => esc_html__( 'Center', RAVEN_DOMAIN ),
		'right'  => esc_html__( 'Right', RAVEN_DOMAIN ),
	) );
}


/**
 * Santize choices for image position x select field
 *
 * @since 1.0.0
 */
function raven_sanitize_image_position_x( $value ) {
	$choices = raven_get_image_position_x_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return apply_filters( 'raven_sanitize_image_position_x_default', 'center' );
}


/**
 * Get choices for image position y select field
 *
 * @since 1.0.0
 */
function raven_get_image_position_y_choices() {
	return apply_filters( 'raven_image_position_y_choices', array(
		'top'    => esc_html__( 'Top', RAVEN_DOMAIN ),
		'center' => esc_html__( 'Center', RAVEN_DOMAIN ),
		'bottom' => esc_html__( 'Bottom', RAVEN_DOMAIN ),
	) );
}


/**
 * Santizie choices for image position y select field
 *
 * @since 1.0.0
 */
function raven_sanitize_image_position_y( $value ) {
	$choices = raven_get_image_position_y_choices();
	if ( array_key_exists( $value, $choices ) ) {
		return $value;
	}

	return apply_filters( 'raven_sanitize_image_position_y_default', 'center' );
}


/**
 * Get choices for image attachment select field
 *
 * @since 1.0.0
 */
function raven_get_image_attachment_choices() {
	return apply_filters( 'raven_image_attachment_choices', array(
		'scroll' => esc_html__( 'Scroll', RAVEN_DOMAIN ),
		'fixed'  => esc_html__( 'Fixed', RAVEN_DOMAIN ),
	) );
}


/**
 * Sanitize percentage values
 *
 * @since 1.0.0
 */
function raven_sanitize_0_100_percent( $val ) {
	$val = str_replace( '%', '', $val );
	if ( floatval( $val ) > 100 ) {
		$val = 100;
	} elseif ( floatval( $val ) < 0 ) {
		$val = 0;
	}

	return floatval( $val ) . '%';
}


/**
 * Sanitize integar 0-255 values
 *
 * @since 1.0.0
 */
function raven_sanitize_0_255( $val ) {
	if ( intval( $val ) > 255 ) {
		$val = 255;
	} elseif ( intval( $val ) < 0 ) {
		$val = 0;
	}

	return intval( $val );
}


/**
 * Sanitize opacity values
 *
 * @since 1.0.0
 */
function raven_sanitize_0_1_opacity( $val ) {
	if ( floatval( $val ) > 1 ) {
		$val = 1;
	} elseif ( floatval( $val ) < 0 ) {
		$val = 0;
	}

	return floatval( $val );
}


/**
 * Number loop
 *
 * @since 1.0.0
 */
function raven_admin_number_loop( $val, $iteration ) {
	
	// Minutes
	for($i=0; $i<$val; $i+=$iteration) {
		
		$format = sprintf("%02d", $i);
		$loop[$format] = $format;
		
	}
	
	return $loop;
}