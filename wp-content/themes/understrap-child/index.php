<?php
/**
 * Template Name: Home Page
 *
 * Template for displaying the home page without sidebar even if a sidebar widget is published.
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="jumbotron jumbotron-fluid d-print-none" style="background: url(<?php the_field('jumbotron_image')?>) center center / cover no-repeat;">
	<div class="container">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'loop-templates/content', 'page' ); ?>
		<?php endwhile; // end of the loop. ?>
	</div>
</div>

<div class="wrapper" id="full-width-page-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content">

		<div class="row">

			<div id="primary" class="content-area w-100">

				<main id="main" class="site-main" role="main">
					
					<?php if( have_rows('eagle_eye_solution') ): ?>
						
						<div id="top-solutions" class="row text-center">
							<h3 class="h1"><?php the_field('top_solutions_headline')?></h3>
							<div class="row d-flex flex-row w-100">
								<?php while( have_rows('eagle_eye_solution') ): the_row(); 

									// vars
									$solution_image = get_sub_field('solution_image');
									$solution_name = get_sub_field('solution_name');
									$solution_link = get_sub_field('solution_link');
									?>

									<div class="solution col-sm-6 col-md-4 col-lg-2">
										<a href="<?php echo $solution_link; ?>"><img src="<?php echo $solution_image['url']; ?>" alt="<?php echo $solution_image['alt']; ?>" /></a>
										<h4 class="d-table w-100"><a href="<?php echo $solution_link; ?>" class="d-table-cell align-middle"><?php echo $solution_name; ?></a></h4>
										<a class="btn btn-primary d-print-none" href="<?php echo $solution_link; ?>">Learn More &gt;<span class="sr-only"> about <?php echo $solution_name; ?></span></a>
									</div>

								<?php endwhile; ?>
							</div>
						</div>
					<?php endif; ?>
				</main>
			</div><!-- #main -->
		</div><!-- #primary -->
	</div><!-- .row end -->
	<div class="container-fluid">
		<?php if( have_rows('eagle_eye_university_promo') ): ?>
		    <?php while( have_rows('eagle_eye_university_promo') ): the_row();
		        // Get sub field values
		        $eeu_background_image = get_sub_field('eeu_background_image');
		        $eeu_logo_image = get_sub_field('eeu_logo_image');
		        $eeu_positioning_headline = get_sub_field('eeu_positioning_headline');
		        $eeu_headline = get_sub_field('eeu_headline');
		        $eeu_copy = get_sub_field('eeu_copy');
		        $eeu_link = get_sub_field('eeu_link');

		        ?>
				<div id="eagle-eye-university" class="row">
					<div class="col" style="background: url(<?php echo $eeu_background_image ?>) top center / cover no-repeat;">
						<h3 id="positioning" class="h1"><?php echo $eeu_positioning_headline ?></h3>
					</div>
					<div id="cta" class="col text-center">
						<img src="<?php echo $eeu_logo_image ?>" alt="" />
						<h3 class="h1"><?php echo $eeu_headline; ?></h3>
						<p><?php echo $eeu_copy; ?></p>
						<a class="btn btn-primary btn-super btn-contrast d-print-none" href="<?php echo $eeu_link; ?>">Learn more &gt;<span class="sr-only"> about <?php echo $eeu_headline; ?></span></a>
					</div>
				</div>
	    	<?php endwhile; ?>
		<?php endif; ?>

		<?php if( have_rows('eagle_eye_commitment') ): ?>
		   	<?php while( have_rows('eagle_eye_commitment') ): the_row(); 
				// Get sub field values.
				$eagle_eye_commitment_background_image = get_sub_field('eagle_eye_commitment_background_image');
				$eagle_eye_commitment_headline = get_sub_field('eagle_eye_commitment_headline');
				$eagle_eye_commitment_link = get_sub_field('eagle_eye_commitment_link');
				?>
				<div id="eagle-eye-committment" class="row d-print-none" style="background: url(<?php echo $eagle_eye_commitment_background_image ?>) top center / cover no-repeat;;">
					<div class="col text-center">
						<a href="<?php echo $eagle_eye_commitment_link; ?>" target="_blank" rel="noopener">
							<h3 class="h1"><?php echo $eagle_eye_commitment_headline; ?></h3>
							<i class="fa fa-play-circle-o"></i>
						</a>
					</div>
				</div>
	    	<?php endwhile; ?>
		<?php endif; ?>
	</div><!-- #content -->
</div><!-- #full-width-page-wrapper -->

<?php get_footer(); ?>