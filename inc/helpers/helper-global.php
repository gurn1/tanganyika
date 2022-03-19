<?php
/**
 * Global helper functions
 *
 * @since 1.0.0
 */

/* Die if accessed directly */
if( ! defined( "ABSPATH" ) )
	die("Direct access is not permitted");


if ( ! function_exists( 'is_woocommerce_activated' ) ) :
	/**
	 * Detect if woocommerce is active
	 *
	 * @since 1.0.0
	 */
	function is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { 
			return true; 
		} else { 
			return false; 
		}
		
	}
endif;


if( ! function_exists('raven_theme_body_classes') ) :
	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	function raven_theme_body_classes( $classes ) {

		if ( is_singular() ) {
			// Adds `singular` to singular pages.
			$classes[] = 'singular';
		} else {
			// Adds `hfeed` to non singular pages.
			$classes[] = 'hfeed';
		}

		// Adds a class if image filters are enabled.
		if ( raven_theme_image_filters_enabled() ) {
			$classes[] = 'image-filters-enabled';
		}
		
		// Has sidebar
		if( wp_get_sidebars_widgets() ) {
		//	$classes[] = 'has-sidebar';
		}

		return $classes;
	}
	add_filter( 'body_class', 'raven_theme_body_classes' );
endif;


if( ! function_exists('raven_theme_post_classes') ) :
	/**
	 * Adds custom class to the array of posts classes.
	 */
	function raven_theme_post_classes( $classes, $class, $post_id ) {
		$classes[] = 'entry';

		return $classes;
	}
	add_filter( 'post_class', 'raven_theme_post_classes', 10, 3 );
endif;


if( ! function_exists('raven_theme_comment_form_defaults') ) :
	/**
	 * Changes comment form default fields.
	 */
	function raven_theme_comment_form_defaults( $defaults ) {
		$comment_field = $defaults['comment_field'];

		// Adjust height of comment form.
		$defaults['comment_field'] = preg_replace( '/rows="\d+"/', 'rows="5"', $comment_field );

		return $defaults;
	}
	add_filter( 'comment_form_defaults', 'raven_theme_comment_form_defaults' );
endif;


if( ! function_exists('raven_theme_get_the_archive_title') ) :
	/**
	 * Filters the default archive titles.
	 */
	function raven_theme_get_the_archive_title() {
		if ( is_category() ) {
			$title = __( 'Category Archives: ', 'raven_theme' ) . '<span class="page-description">' . single_term_title( '', false ) . '</span>';
		} elseif ( is_tag() ) {
			$title = __( 'Tag Archives: ', 'raven_theme' ) . '<span class="page-description">' . single_term_title( '', false ) . '</span>';
		} elseif ( is_author() ) {
			$title = __( 'Author Archives: ', 'raven_theme' ) . '<span class="page-description">' . get_the_author_meta( 'display_name' ) . '</span>';
		} elseif ( is_year() ) {
			$title = __( 'Yearly Archives: ', 'raven_theme' ) . '<span class="page-description">' . get_the_date( _x( 'Y', 'yearly archives date format', 'raven_theme' ) ) . '</span>';
		} elseif ( is_month() ) {
			$title = __( 'Monthly Archives: ', 'raven_theme' ) . '<span class="page-description">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'raven_theme' ) ) . '</span>';
		} elseif ( is_day() ) {
			$title = __( 'Daily Archives: ', 'raven_theme' ) . '<span class="page-description">' . get_the_date() . '</span>';
		} elseif ( is_post_type_archive() ) {
			$title = __( 'Post Type Archives: ', 'raven_theme' ) . '<span class="page-description">' . post_type_archive_title( '', false ) . '</span>';
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			/* translators: %s: Taxonomy singular name */
			$title = sprintf( esc_html__( '%s Archives:', 'raven_theme' ), $tax->labels->singular_name );
		} else {
			$title = __( 'Archives:', 'raven_theme' );
		}
		return $title;
	}
	add_filter( 'get_the_archive_title', 'raven_theme_get_the_archive_title' );
endif;


if( ! function_exists('raven_theme_can_show_post_thumbnail') ) :
	/**
	 * Determines if post thumbnail can be displayed.
	 */
	function raven_theme_can_show_post_thumbnail() {
		return apply_filters( 'raven_theme_can_show_post_thumbnail', ! post_password_required() && ! is_attachment() && has_post_thumbnail() );
	}
endif;


if( ! function_exists('raven_theme_image_filters_enabled') ) :
	/**
	 * Returns true if image filters are enabled on the theme options.
	 */
	function raven_theme_image_filters_enabled() {
		return 0 !== get_theme_mod( 'image_filter', 1 );
	}
endif;


if( ! function_exists('raven_theme_post_thumbnail_sizes_attr') ) :
	/**
	 * Add custom sizes attribute to responsive image functionality for post thumbnails.
	 *
	 * @origin Tanganyika 1.0
	 *
	 * @param array $attr  Attributes for the image markup.
	 * @return string Value for use in post thumbnail 'sizes' attribute.
	 */
	function raven_theme_post_thumbnail_sizes_attr( $attr ) {

		if ( is_admin() ) {
			return $attr;
		}

		if ( ! is_singular() ) {
			$attr['sizes'] = '(max-width: 34.9rem) calc(100vw - 2rem), (max-width: 53rem) calc(8 * (100vw / 12)), (min-width: 53rem) calc(6 * (100vw / 12)), 100vw';
		}

		return $attr;
	}
	add_filter( 'wp_get_attachment_image_attributes', 'raven_theme_post_thumbnail_sizes_attr', 10, 1 );
endif;


if( ! function_exists('raven_theme_get_avatar_size') ) :
	/**
	 * Returns the size for avatars used in the theme.
	 */
	function raven_theme_get_avatar_size() {
		return 60;
	}
endif;


if( ! function_exists('raven_theme_is_comment_by_post_author') ) :
	/**
	 * Returns true if comment is by author of the post.
	 *
	 * @see get_comment_class()
	 */
	function raven_theme_is_comment_by_post_author( $comment = null ) {
		if ( is_object( $comment ) && $comment->user_id > 0 ) {
			$user = get_userdata( $comment->user_id );
			$post = get_post( $comment->comment_post_ID );
			if ( ! empty( $user ) && ! empty( $post ) ) {
				return $comment->user_id === $post->post_author;
			}
		}
		return false;
	}
endif;


if( ! function_exists('raven_theme_get_discussion_data') ) :
	/**
	 * Returns information about the current post's discussion, with cache support.
	 */
	function raven_theme_get_discussion_data() {
		static $discussion, $post_id;

		$current_post_id = get_the_ID();
		if ( $current_post_id === $post_id ) {
			return $discussion; /* If we have discussion information for post ID, return cached object */
		} else {
			$post_id = $current_post_id;
		}

		$comments = get_comments(
			array(
				'post_id' => $current_post_id,
				'orderby' => 'comment_date_gmt',
				'order'   => get_option( 'comment_order', 'asc' ), /* Respect comment order from Settings » Discussion. */
				'status'  => 'approve',
				'number'  => 20, /* Only retrieve the last 20 comments, as the end goal is just 6 unique authors */
			)
		);

		$authors = array();
		foreach ( $comments as $comment ) {
			$authors[] = ( (int) $comment->user_id > 0 ) ? (int) $comment->user_id : $comment->comment_author_email;
		}

		$authors    = array_unique( $authors );
		$discussion = (object) array(
			'authors'   => array_slice( $authors, 0, 6 ),           /* Six unique authors commenting on the post. */
			'responses' => get_comments_number( $current_post_id ), /* Number of responses. */
		);

		return $discussion;
	}
endif;


if( ! function_exists('raven_theme_add_ellipses_to_nav') ) :
	/**
	 * Add an extra menu to our nav for our priority+ navigation to use
	 *
	 * @param object $nav_menu  Nav menu.
	 * @param object $args      Nav menu args.
	 * @return string More link for hidden menu items.
	 */
	function raven_theme_add_ellipses_to_nav( $nav_menu, $args ) {

		if ( 'menu-1' === $args->theme_location ) :

			$nav_menu .= '<div class="main-menu-more">';
			$nav_menu .= '<ul class="main-menu">';
			$nav_menu .= '<li class="menu-item menu-item-has-children">';
			$nav_menu .= '<button class="submenu-expand main-menu-more-toggle is-empty" tabindex="-1" aria-label="More" aria-haspopup="true" aria-expanded="false">';
			$nav_menu .= '<span class="screen-reader-text">' . esc_html__( 'More', 'raven_theme' ) . '</span>';
			$nav_menu .= '';
			$nav_menu .= '</button>';
			$nav_menu .= '<ul class="sub-menu hidden-links">';
			$nav_menu .= '<li id="menu-item--1" class="mobile-parent-nav-menu-item menu-item--1">';
			$nav_menu .= '<button class="menu-item-link-return">';
			$nav_menu .= '';
			$nav_menu .= esc_html__( 'Back', 'raven_theme' );
			$nav_menu .= '</button>';
			$nav_menu .= '</li>';
			$nav_menu .= '</ul>';
			$nav_menu .= '</li>';
			$nav_menu .= '</ul>';
			$nav_menu .= '</div>';

		endif;

		return $nav_menu;
	}
	add_filter( 'wp_nav_menu', 'raven_theme_add_ellipses_to_nav', 10, 2 );
endif;


if( ! function_exists('raven_theme_nav_menu_link_attributes') ) :
	/**
	 * WCAG 2.0 Attributes for Dropdown Menus
	 *
	 * Adjustments to menu attributes tot support WCAG 2.0 recommendations
	 * for flyout and dropdown menus.
	 *
	 * @ref https://www.w3.org/WAI/tutorials/menus/flyout/
	 */
	function raven_theme_nav_menu_link_attributes( $atts, $item, $args, $depth ) {

		// Add [aria-haspopup] and [aria-expanded] to menu items that have children
		$item_has_children = in_array( 'menu-item-has-children', $item->classes );
		if ( $item_has_children ) {
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
		}

		return $atts;
	}
	add_filter( 'nav_menu_link_attributes', 'raven_theme_nav_menu_link_attributes', 10, 4 );
endif;


if( ! function_exists('raven_theme_add_dropdown_icons') ) :
	/**
	 * Add a dropdown icon to top-level menu items.
	 *
	 * @param string $output Nav menu item start element.
	 * @param object $item   Nav menu item.
	 * @param int    $depth  Depth.
	 * @param object $args   Nav menu args.
	 * @return string Nav menu item start element.
	 * Add a dropdown icon to top-level menu items
	 */
	function raven_theme_add_dropdown_icons( $output, $item, $depth, $args ) {

		// Only add class to 'top level' items on the 'primary' menu.
		if ( ! isset( $args->theme_location ) || 'menu-1' !== $args->theme_location ) {
			return $output;
		}

		if ( in_array( 'mobile-parent-nav-menu-item', $item->classes, true ) && isset( $item->original_id ) ) {
			// Inject the keyboard_arrow_left SVG inside the parent nav menu item, and let the item link to the parent item.
			// @todo Only do this for nested submenus? If on a first-level submenu, then really the link could be "#" since the desire is to remove the target entirely.
			$link = sprintf(
				'<button class="menu-item-link-return" tabindex="-1">',
				''
			);

			// replace opening <a> with <button>
			$output = preg_replace(
				'/<a\s.*?>/',
				$link,
				$output,
				1 // Limit.
			);

			// replace closing </a> with </button>
			$output = preg_replace(
				'#</a>#i',
				'</button>',
				$output,
				1 // Limit.
			);

		} elseif ( in_array( 'menu-item-has-children', $item->classes, true ) ) {

			// Add SVG icon to parent items.
			$icon = '';

			$output .= sprintf(
				'<button class="submenu-expand" tabindex="-1">%s</button>',
				$icon
			);
		}

		return $output;
	}
	add_filter( 'walker_nav_menu_start_el', 'raven_theme_add_dropdown_icons', 10, 4 );
endif;


if( ! function_exists('raven_theme_add_mobile_parent_nav_menu_items') ) :
	/**
	 * Create a nav menu item to be displayed on mobile to navigate from submenu back to the parent.
	 *
	 * This duplicates each parent nav menu item and makes it the first child of itself.
	 *
	 * @param array  $sorted_menu_items Sorted nav menu items.
	 * @param object $args              Nav menu args.
	 * @return array Amended nav menu items.
	 */
	function raven_theme_add_mobile_parent_nav_menu_items( $sorted_menu_items, $args ) {
		static $pseudo_id = 0;
		if ( ! isset( $args->theme_location ) || 'menu-1' !== $args->theme_location ) {
			return $sorted_menu_items;
		}

		$amended_menu_items = array();
		foreach ( $sorted_menu_items as $nav_menu_item ) {
			$amended_menu_items[] = $nav_menu_item;
			if ( in_array( 'menu-item-has-children', $nav_menu_item->classes, true ) ) {
				$parent_menu_item                   = clone $nav_menu_item;
				$parent_menu_item->original_id      = $nav_menu_item->ID;
				$parent_menu_item->ID               = --$pseudo_id;
				$parent_menu_item->db_id            = $parent_menu_item->ID;
				$parent_menu_item->object_id        = $parent_menu_item->ID;
				$parent_menu_item->classes          = array( 'mobile-parent-nav-menu-item' );
				$parent_menu_item->menu_item_parent = $nav_menu_item->ID;

				$amended_menu_items[] = $parent_menu_item;
			}
		}

		return $amended_menu_items;
	}
	add_filter( 'wp_nav_menu_objects', 'raven_theme_add_mobile_parent_nav_menu_items', 10, 2 );
endif;


if( ! function_exists('raven_theme_hsl_hex') ) :
	/**
	 * Convert HSL to HEX colors
	 */
	function raven_theme_hsl_hex( $h, $s, $l, $to_hex = true ) {

		$h /= 360;
		$s /= 100;
		$l /= 100;

		$r = $l;
		$g = $l;
		$b = $l;
		$v = ( $l <= 0.5 ) ? ( $l * ( 1.0 + $s ) ) : ( $l + $s - $l * $s );
		if ( $v > 0 ) {
			$m;
			$sv;
			$sextant;
			$fract;
			$vsf;
			$mid1;
			$mid2;

			$m       = $l + $l - $v;
			$sv      = ( $v - $m ) / $v;
			$h      *= 6.0;
			$sextant = floor( $h );
			$fract   = $h - $sextant;
			$vsf     = $v * $sv * $fract;
			$mid1    = $m + $vsf;
			$mid2    = $v - $vsf;

			switch ( $sextant ) {
				case 0:
					$r = $v;
					$g = $mid1;
					$b = $m;
					break;
				case 1:
					$r = $mid2;
					$g = $v;
					$b = $m;
					break;
				case 2:
					$r = $m;
					$g = $v;
					$b = $mid1;
					break;
				case 3:
					$r = $m;
					$g = $mid2;
					$b = $v;
					break;
				case 4:
					$r = $mid1;
					$g = $m;
					$b = $v;
					break;
				case 5:
					$r = $v;
					$g = $m;
					$b = $mid2;
					break;
			}
		}
		$r = round( $r * 255, 0 );
		$g = round( $g * 255, 0 );
		$b = round( $b * 255, 0 );

		if ( $to_hex ) {

			$r = ( $r < 15 ) ? '0' . dechex( $r ) : dechex( $r );
			$g = ( $g < 15 ) ? '0' . dechex( $g ) : dechex( $g );
			$b = ( $b < 15 ) ? '0' . dechex( $b ) : dechex( $b );

			return "#$r$g$b";

		}

		return "rgb($r, $g, $b)";
	}
endif;


if( ! function_exists('raven_theme_get_social_media_sharing_links') ) :
	/**
	 * Get the social media links
	 *
	 * @since 1.0.0
	 */
	function raven_theme_get_social_media_sharing_links() {
		
		$contact = new raven_theme_contact_controller();
		
		echo $contact->social_share();
	}
endif;


if( ! function_exists('raven_theme_social_media_links') ) :
	/**
	 * Get the social media links
	 *
	 * @since 1.0.0
	 */
	function raven_theme_get_contact_field( $name, $raw = '' ) {
		
		$contact = new raven_theme_contact_controller();
		
		echo $contact->contact_field( $name, $raw );
	}
endif;


if( ! function_exists('raven_theme_header_basket') ) :
	/**
	 * Header basket 
	 *
	 * @since 1.0.0
	 */
	function raven_theme_header_basket() {

		if( is_woocommerce_activated() ) {
			
			$carturl	= wc_get_cart_url();
			$carttotal	= WC()->cart->get_cart_total();
			$cartcount  = WC()->cart->cart_contents_count ;
			//$cartitems  = sprintf( _n('%d Item', '%d Items', $cartcount, RAVEN_DOMAIN), $cartcount);			

			echo '<div class="header-basket">';

			echo sprintf(' <a href="%1$s" class="basket cart-contents wpmenucart-contents" title="Cart Contents"><i class="fa fa-shopping-basket basket-icon"></i> %2$s <span class="item-count">%3$s</span></a>', $carturl, $carttotal, $cartcount );

			echo '<div class="mini-cart">';
			woocommerce_mini_cart();
			echo '</div>';

			echo '</div>';
			
		}

	}
endif;


if( ! function_exists('raven_theme_content_class') ) :
	/**
	 * Content class
	 *
	 * @since 1.0.0
	 */
	function raven_theme_content_class() {
		
		$classes = '';
		
		if( is_front_page() || is_page_template('template-parts/page-fullwidth.php') || is_page('contact') || is_page('about') ) {
			$classes = 'fullwidth';
		}
		
		if( is_page('contact') ) {
			$classes .= ' contact-content';
		}
		
		echo $classes;
		
	}
endif;

if ( ! function_exists( 'raven_trauncate_product_content' ) ) :
	/**
	 * Get a trunacated version of the shop content
	 * Default 65 words
	 *
	 * @since 1.0.0
	 */
	function raven_trauncate_product_content($length = 65) {
		global $product;
		
		if($product) {
			if( $product->get_short_description() ) {
				$content = strip_tags( $product->get_short_description() );
			}else{
				$content = strip_tags( $product->get_description() );
			}
			
			echo raven_trauncate($content, $length);
		}
	}
endif;

if( ! function_exists('raven_trauncate') ) :
	/**
	 * Trauncate string
	 * default 50 words
	 *
	 * @since 1.0.0
	 */
	function raven_trauncate( $content, $length = 50 ) {
		
		$content = strip_tags( $content );
		$content = explode( " ", $content );
		
		return implode( " ",array_splice($content, 0, $length) );
		
	}
endif;

if( ! function_exists('raven_product_layout_switcher') ) {
	/**
	 * Product layour switcher
	 *
	 * @since 1.0.0
	 */
	function raven_product_layout_switcher() {
		$cookie_name = 'raven_product_layout_switcher';

		if( isset($_COOKIE[$cookie_name]) ) {
			$grid = $_COOKIE[$cookie_name] == 'grid' ? 'selected' : '';
			$list = $_COOKIE[$cookie_name] == 'list' ? 'selected' : '';
		} else {
			$grid = 'selected';
			$list = '';
		}
		
		echo sprintf('<span class="product-layout">
				<i id="grid_selector" class="fas product-selector fa-th %1$s" data-target="grid"></i>
				<i id="list_selector" class="fas product-selector fa-th-list %2$s" data-target="list"></i>
			</span>',
			$grid, $list
		);
	}
	add_action('woocommerce_before_shop_loop', 'raven_product_layout_switcher', 40);
}

if( ! function_exists('raven_get_sidebar') ) :
	/**
	 * Get the sidebar
	 *
	 * @since 1.0.0
	 */
	function raven_get_sidebar($sidebar) {
		require RAVEN_TEMPLATE_PART . 'content/content-sidebar.php';
	}
endif;

if( ! function_exists('raven_favicon') ) {
	/**
	 * Favicon meta
	 *
	 * @since 1.0.0
	 */
	function raven_favicon() {

		if( is_dir(RAVEN_ASSETS . 'images/favicon')) {
		?>

			<link rel="apple-touch-icon" sizes="57x57" href="<?php echo RAVEN_ASSETS; ?>images/favicon/apple-icon-57x57.png">
			<link rel="apple-touch-icon" sizes="60x60" href="<?php echo RAVEN_ASSETS; ?>images/favicon/apple-icon-60x60.png">
			<link rel="apple-touch-icon" sizes="72x72" href="<?php echo RAVEN_ASSETS; ?>images/favicon/apple-icon-72x72.png">
			<link rel="apple-touch-icon" sizes="76x76" href="<?php echo RAVEN_ASSETS; ?>images/favicon/apple-icon-76x76.png">
			<link rel="apple-touch-icon" sizes="114x114" href="<?php echo RAVEN_ASSETS; ?>images/favicon/apple-icon-114x114.png">
			<link rel="apple-touch-icon" sizes="120x120" href="<?php echo RAVEN_ASSETS; ?>images/favicon/apple-icon-120x120.png">
			<link rel="apple-touch-icon" sizes="144x144" href="<?php echo RAVEN_ASSETS; ?>images/favicon/apple-icon-144x144.png">
			<link rel="apple-touch-icon" sizes="152x152" href="<?php echo RAVEN_ASSETS; ?>images/favicon/apple-icon-152x152.png">
			<link rel="apple-touch-icon" sizes="180x180" href="<?php echo RAVEN_ASSETS; ?>images/favicon/apple-icon-180x180.png">
			<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo RAVEN_ASSETS; ?>images/favicon/android-icon-192x192.png">
			<link rel="icon" type="image/png" sizes="32x32" href="<?php echo RAVEN_ASSETS; ?>images/favicon/favicon-32x32.png">
			<link rel="icon" type="image/png" sizes="96x96" href="<?php echo RAVEN_ASSETS; ?>images/favicon/favicon-96x96.png">
			<link rel="icon" type="image/png" sizes="16x16" href="<?php echo RAVEN_ASSETS; ?>images/favicon/favicon-16x16.png">
			<link rel="manifest" href="<?php echo RAVEN_ASSETS; ?>images/favicon/manifest.json">
			<meta name="msapplication-TileColor" content="#ffffff">
			<meta name="msapplication-TileImage" content="<?php echo RAVEN_ASSETS; ?>images/favicon/ms-icon-144x144.png">
			<meta name="theme-color" content="#ffffff">

		<?php
		}
	}
}

if( ! function_exists('raven_sanitize') ) :
	/**
	 * Sanitize data
	 *
	 * @since 1.0.0
	 */
	function raven_sanitize($fields) {

		if( is_array($fields) ) {
			$sanitized = array();
			
			foreach($fields as $field) {
				
				if( is_array( $_POST[$field] ) ) {
					$sanitized[$field] = isset($_POST[$field]) ? array_map( 'esc_attr', $_POST[$field] ) : array();
				}else{
					$sanitized[$field] = isset($_POST[$field]) ? sanitize_text_field( $_POST[$field] ) : '';
				}
			}
		}else{
			$sanitized = isset($_POST[$field]) ? sanitize_text_field( $_POST[$field] ) : '';
		}
		
		return $sanitized;
	}
endif;

if( ! function_exists('raven_wp_parse_args') ) :
	/**
	 * Parse args recursive
	 *
	 * @since 1.0.0
	 */
	function raven_wp_parse_args( &$a, $b ) {
		$a = (array) $a;
		$b = (array) $b;
		$result = $b;
		
		foreach ( $a as $k => &$v ) {
			if ( is_array( $v ) && isset( $result[ $k ] ) ) {
				$result[ $k ] = raven_wp_parse_args( $v, $result[ $k ] );
			} else {
				$result[ $k ] = $v;
			}
		}
		
		return $result;
	}
endif;