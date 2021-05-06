<?php
/**
 * The template for displaying a list of Posts
 * 
 * Template Name: Post Listings
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package eagleeye2019
 */

get_header();

$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper" id="page-wrapper">
	<div class="<?php echo esc_attr( $container ); ?>" id="content">
		<div class="row">

			<div id="primary" class="col-md content-area">
				<main id="main" class="site-main">
					<?php get_template_part( 'loop-templates/content', 'page' ); ?>


					<?php 
					// the query
					$wpb_all_query = new WP_Query(array('post_type'=>'post', 'post_status'=>'publish', 'posts_per_page'=>10)); ?>
					 
					<?php if ( $wpb_all_query->have_posts() ) : ?>
					 
						<ul class="list-unstyled">
						    <!-- the loop -->
						    <?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); ?>
						        <li class="mb-5">
						        	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						        	<p><?php understrap_posted_on(); ?></p>
						        	<p><?php the_excerpt(); ?></p>
						        </li>
						    <?php endwhile; ?>
						    <!-- end of the loop -->
						</ul>
					 
					    <?php wp_reset_postdata(); ?>
					 
					<?php else : ?>
					    <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
					<?php endif; ?>
				</main>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>