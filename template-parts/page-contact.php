<?php
/**
 * Template name: Contact Page
 *
 * @since 1.0.0
 */

do_action( 'raven_process_form', 'raven_contact_form' );

get_header(); ?>

<div class="contact-page-bc"></div>

<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>

	<header class="page-header">
		<h1 class="page-title"><?php echo get_the_title(); ?></h1>
		<div class="notice-container">
			<?php raven_framework()->notices()->global_notice(); ?>
		</div>
		
		<div class="container small" style="min-height: 60px;">
			<p><?php echo the_content(); ?></p>
		</div>
	</header>

	<div id="contact_container">

		<section class="contact-form">
			<?php
			/**
			 * Get contact form
			 *
			 * @since 1.0.0
			 */
			raven_framework()->forms()->primary_form();
			?>
		</section>

		<section class="contact-other">
			<div class="contact-social contact-group">
				<?php raven_framework()->contact()->social_media_links(); ?>
			</div>
			
			<div class="contact-phone contact-group">
				<header>
					<span class="icon"><i class="fas fa-phone"></i></span>
					<h2>Phone Number</h2>
				</header>
				<div class="content">	
					<?php raven_framework()->contact()->contact_field('office_phone'); ?>
					<?php raven_framework()->contact()->contact_field('mobile_phone'); ?>
				</div>
			</div>
				
			<div class="contact-email contact-group">
				<header>
					<span class="icon"><i class="fas fa-envelope"></i></span>
					<h2>Email Address</h2>
				</header>
				<div class="content">
					<?php raven_framework()->contact()->contact_field('email_address'); ?>
				</div>
			</div>
			
			<?php if( isset(get_option('raven_contact')['billing_address']) ) : ?>
				<div class="contact-address contact-group">
					<header>
						<span class="icon"><i class="fas fa-building"></i></span>
						<h2>Address</h2>
					</header>
					<div class="content">
						<?php raven_framework()->contact()->contact_field('billing_address'); ?>
					</div>
				</div>
			<?php endif; ?>
			
		</section>

	</div>

<?php endwhile; endif; ?>

<?php
get_footer();