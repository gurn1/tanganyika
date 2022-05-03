<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
	<?php echo woocommerce_show_product_loop_sale_flash(); ?>
	
	<figure class="entry-thumbnail">
		<?php	
		woocommerce_template_loop_product_link_open();
		echo woocommerce_get_product_thumbnail( 'shop_thumbnail' );
		woocommerce_template_loop_product_link_close();
		?>
	</figure>
	
	<div class="entry-container">
		<header class="entry-header">
			<span class="categories"><?php echo wc_get_product_category_list( $product->get_id(), ', ' ); ?></span>
			<h4 class="entry-title">
				<?php 			
				woocommerce_template_loop_product_link_open();
				echo get_the_title();
				woocommerce_template_loop_product_link_close();
				?>
			</h4>

			<div class="entry-excerpt">
				<?php raven_trauncate_product_content();?>
			</div>
		</header>
			
		<footer class="entry-footer">
			<?php 
			echo woocommerce_template_loop_rating();
			echo woocommerce_template_loop_price(); 
			?>
			
			<?php woocommerce_template_loop_add_to_cart(); ?>
		</footer>
		
	</div>
	
</li>
