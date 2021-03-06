<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$columns = wp_get_sidebars_widgets() == true ? 'four-col' : 'five-col';
$cookie_name = 'raven_product_layout_switcher';
$cookie = isset( $_COOKIE[$cookie_name] ) ? $_COOKIE[$cookie_name] : 'grid';

if( is_front_page() ) {
	$cookie = 'grid';
}
?>
<ul class="products <?php echo $columns . ' ' . $cookie; ?>">
