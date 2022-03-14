<?php
/**
 * Template part for displaying product categories
 *
 * @package WordPress
 * @subpackage Tanganyika
 * @since 1.0.0
 */

$shop_page = get_permalink(wc_get_page_id('shop'));
?>

<li id="home-category-<?php echo $args->term_id; ?>" class="category-item" style="background: url(<?php echo $args->background_image[0]; ?>) no-repeat center">
    <!--<header class="entry-header">-->
        <span class="entry-title-o"><?php echo $args->term_id; ?><a href="<?php echo get_term_link($args->term_id); ?>" aria-title="<?php echo $args->name; ?>"><?php echo $args->name; ?></a></span>
    <!--</header>-->

    <div class="entry-content">
        <?php
        /**
         * Get Child Categories
         * 
         * @since 1.0.0
         */
        raven_framework()->taxonomies()->display_child_categories($args->term_id);
        ?>
    </div>

    <!--<footer class="entry-footer">
        <a href="<?php// echo $shop_page; ?>?new-arrivals=true&category=<?php// echo $args->term_id; ?>" title="New arrivals for <?php// echo $args->name; ?>" aria-title="New arrivals for <?php// echo $args->name; ?>" class="button">New Arrivals</a>
        <a href="<?php// echo $shop_page; ?>?best-sellers=true&category=<?php// echo $args->term_id; ?>" title="Best sellers for <?php// echo $args->name; ?>" aria-title="Best sellers for <?php// echo $args->name; ?>" class="button">Best sellers</a>
    </footer>-->
</li>