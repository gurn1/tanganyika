<?php
/**
 * Displays top bar information
 *
 * @package WordPress
 * @subpackage Tanganyika
 * @since 1.0.0
 */

?>
	
<div class="contact-information">
	<?php raven_framework()->contact()->social_media_links(); ?>
	 | 
	<?php raven_framework()->contact()->contact_field('office_phone'); ?>
	<?php raven_framework()->contact()->contact_field('mobile_phone'); ?>
	<?php raven_framework()->contact()->contact_field('email_address'); ?>
</div>

<div class="account-control">
	<?php raven_theme_get_account_link(); ?>
</div>