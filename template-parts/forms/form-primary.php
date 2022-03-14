<?php
/**
 * Contact form
 *
 * @since 1.0.0
 */
 
?>
 
 <form id="raven_primary_contact_form" name="<?php echo $args['form_name']; ?>" method="post" enctype="multipart/form-data">
 	
	<!-- Name -->
	<div class="control-box">
		<label class="control-label">Name <abbr class="required" title="required">*</abbr></label>
		<div class="control-input">
			<input type="text" name="raven_name" required>
		</div>
	</div>
	
	<!-- Email -->
	<div class="control-box">
		<label class="control-label">Email Address <abbr class="required" title="required">*</abbr></label>
		<div class="control-input">
			<input type="email" name="raven_email" required>
		</div>
	</div>
	
	<!-- Phone -->
	<div class="control-box">
		<label class="control-label">Phone Number</label>
		<div class="control-input">
			<input type="tel" name="raven_phone">
		</div>
	</div>
	
	<!-- Subject -->
	<div class="control-box">
		<label class="control-label">Subject</label>
		<div class="control-input">
			<input type="text" name="raven_subject">
		</div>
	</div>
	
	<?php if( class_exists('woocommerce') ) : ?>
		<!-- Order Number -->
		<div class="control-box">
			<label class="control-label">Order Number</label>
			<div class="control-input">
				<input type="number" name="raven_order_number">
			</div>
		</div>
	<?php endif; ?>
	
	<!-- Message -->
	<div class="control-box">
		<label class="control-label">Message <abbr class="required" title="required">*</abbr></label>
		<div class="control-input">
			<textarea name="raven_message" required></textarea>
		</div>
	 </div>
	 
	 <?php if( $args['site_key'] ) : ?>
		<!-- Human check -->
		<div class="control-box">
			<label class="control-label">Verify <abbr class="required" title="required">*</abbr></label>
			<div class="g-recaptcha" data-sitekey="<?php echo $args['site_key']; ?>"></div>
		</div>
	 <?php endif; ?>
	 
	 <div class="submit-container">
		 <button type="submit" class="button large" id="submit">Send</button>
		 
		 <input name="action" type="hidden" value="<?php echo $args['form_name']; ?>">
		 <?php wp_nonce_field( $args['form_name'] ); ?>
		 
	 </div>
	
 </form>