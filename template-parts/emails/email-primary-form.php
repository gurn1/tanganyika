<?php
/**
 * Email primary form template
 *
 * @since 1.0.0
 */
 ?>

<!-- Name -->
<p><b>Name</b> <span><?php echo $name; ?></span></p>

<!-- Email -->
<p><b>Email Address</b> <span><?php echo $email; ?></span></p>

<!-- Phone Number -->
<p><b>Phone Number</b> <span><?php echo $phone; ?></span></p>

<?php if( class_exists('woocommerce') ) : ?>
	<!-- Order Number -->
	<p><b>Order Number</b> <span><?php echo $order_no; ?></span></p>
<?php endif; ?>

<!-- Message -->
<p><b>Message</b> <span><?php echo $message; ?></span></p>