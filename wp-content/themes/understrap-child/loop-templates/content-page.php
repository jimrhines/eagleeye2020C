<?php
/**
 * Partial template for content in page.php
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<?php if( get_field('featured_image_display') == 'No' ) {
	echo get_the_post_thumbnail( $post->ID, 'large' );
} ?>

<div class="row">
	<div id="general-content" class="col-md-8">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php the_content(); ?>
	</div>
	<div class="col-md-4"></div>
</div>