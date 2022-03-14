<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_mini_cart' ); ?>

<?php if ( ! WC()->cart->is_empty() ) : ?>

	<div class="woocommerce-mini-cart">

		<ul class="cart_list product_list_widget <?php echo esc_attr( $args['list_class'] ); ?>">
			<?php
				do_action( 'woocommerce_before_mini_cart_contents' );

				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
						$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail'), $cart_item, $cart_item_key );
						$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						$permalink_element_before = ! empty( $product_permalink ) ? '<a href="'.$product_permalink.'">': '';
						$permalink_element_after  = ! empty( $product_permalink ) ? '</a>' : ''
						?>
						<li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini-cart-item', $cart_item, $cart_item_key ) ); ?>">

							<div class="entry-content">
								<?php
									echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
										'<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										__( 'Remove this item', 'woocommerce' ),
										esc_attr( $product_id ),
										esc_attr( $cart_item_key ),
										esc_attr( $_product->get_sku() )
									), $cart_item_key );
								?>

								<?php echo $permalink_element_before ?>
									<?php echo sprintf('<span class="entry-title secondary">%s</span>', $product_name); ?>
								<?php echo $permalink_element_after; ?>

								<div class="item-amount">
									<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
									<!-- single_use_site -->
									<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
								</div>
							</div>
							<figure class="entry-thumbnail">
								<?php echo $permalink_element_before; ?>
									<?php echo $thumbnail; ?>
								<?php echo $permalink_element_after; ?>
							</figure>

						</li>
						<?php
					}
				}

				do_action( 'woocommerce_mini_cart_contents' );
			?>
		</ul>

		<div class="cart-control">
			<p class="woocommerce-mini-cart__total total"><strong><?php _e( 'Subtotal', RAVEN_DOMAIN ); ?>: <?php echo WC()->cart->get_cart_subtotal(); ?></strong></p>

			<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

			<div class="buttons">

				<?php echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="button highlight wc-forward">' . esc_html__( 'View cart', RAVEN_DOMAIN ) . '</a>'; ?>
				<?php echo '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="button green checkout wc-forward">' . esc_html__( 'Checkout', RAVEN_DOMAIN ) . '</a>'; ?>

			</div>

		</div>

	</div>

<?php else : ?>

	<p class="woocommerce-mini-cart__empty-message"><?php _e( 'No products in the cart.', 'woocommerce' ); ?></p>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
