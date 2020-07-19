<?php
/**
 * The template for displaying data list pages
 * 
 * Template Name: Data List Template
 *
 * This is the template that displays data list pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package eagleeye2019
 */
?>

<?php get_header(); 

$container = get_theme_mod( 'understrap_container_type' );

?>
<div class="wrapper" id="page-wrapper">
	<div class="<?php echo esc_attr( $container ); ?>" id="content">
		<div class="row">
			<div id="primary" class="content-area w-100">
				<main id="main" class="site-main">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'loop-templates/content', 'page' ); ?>
					<?php endwhile; // end of the loop. ?>
					<?php $category_products = get_field('category_products');
					if( $category_products ): ?>
						<ul class="list-group list-group-flush">
							<?php foreach( $category_products as $p ): // variable must NOT be called $post (IMPORTANT) ?>
								<li class="list-group-item">
									<?php if( get_field('date_display') == 'No' ) { ?>
										<p><?php echo get_the_date(); ?></p>
									<?php } ?>
									<h2 class="h3 entry-content">
										<a href="<?php echo get_permalink( $p->ID ); ?>"><?php echo get_the_title( $p->ID ); ?></a>
									</h2>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</main>
			</div>
		</div>
	</div>			
</div>

<?php get_footer(); ?>