<?php
/**
 * Template part Home slider
 *
 * @since 1.0.0
 */
$style_position = ! empty($args['hero-content']['style']['position']) ? 'align-'.$args['hero-content']['style']['position'] : '';
$style_color 	= ! empty($args['hero-content']['style']['text-color']) ? 'color: '.$args['hero-content']['style']['text-color'].';' : '';

$title 		= ! empty($args['hero-content']['title']) ? $args['hero-content']['title'] : '';
$excerpt 	= ! empty($args['hero-content']['excerpt']) ? $args['hero-content']['excerpt'] : '';
$cta_url 	= ! empty($args['hero-content']['cta']['url']) ? $args['hero-content']['cta']['url'] : '';
?>

<li style="background: url(<?php echo $args['src'][0]; ?>) no-repeat;" class="hero-slide <?php echo $style_position; ?>">
	<?php if( $title || $excerpt ) : ?>
		<section class="hero-content container large">
			<?php echo sprintf( '<h1 style="%s">%s</h1>', $style_color, $title); ?>
			<?php echo sprintf( '<p style="%s">%s</p>', $style_color, $excerpt); ?>

			<?php if( $cta_url ) : ?>
				<div class="call-to-action">
					<a class="button large" href="<?php echo $cta_url; ?>"><?php echo $args['hero-content']['cta']['text'] ? $args['hero-content']['cta']['text'] : 'Start Shopping'; ?></a>
				</div>
			<?php endif; ?>
		</section>
	<?php endif; ?>
</li>