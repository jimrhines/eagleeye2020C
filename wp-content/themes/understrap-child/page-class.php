<?php
/**
 * The template for displaying class detail pages
 * 
 * Template Name: Class Detail Template
 *
 * This is the template that displays class detail pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package eagleeye2019
 */
?>

<?php get_header(); 

$container = get_theme_mod( 'understrap_container_type' );

?>
<div class="wrapper" id="full-width-page-wrapper">
	<div class="<?php echo esc_attr( $container ); ?>" id="content">
		<div class="row">
			<div id="primary" class="content-area w-100">
				<main id="main" class="site-main">
					<?php while ( have_posts() ) : the_post(); ?>
						<div class="row">
							<div id="class-narrative" class="col-md-8">
								<h1><?php the_title_attribute(); ?></h1>
								<?php get_template_part( 'loop-templates/content-blank', 'nofeaturedimg' ); ?>
							</div>
							<div class="col-md-4"></div>
						</div>
					<?php endwhile; // End of the loop.?>
				</main>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>