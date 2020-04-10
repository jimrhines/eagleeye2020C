<?php
/**
 * The template for displaying division detail pages
 * 
 * Template Name: Division Detail Template
 *
 * This is the template that displays division detail pages.
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
						<div class="container">
							<div class="row divisions-narrative">
								<div id="division-introduction" class="col-md-6">
									<?php get_template_part( 'loop-templates/content-blank', 'nofeaturedimg' ); ?>
								</div>
								<div class="col-md-6 d-print-none">
									<div class="embed-container">
										<?php the_field('division_video_url'); ?>
									</div>
								</div>
							</div>
						</div>
					<?php endwhile; // End of the loop.?>
				</main>
			</div>
		</div>
	</div>

	<?php $division_products = get_field('division_products');
	if( $division_products ): ?>
		<div id="division-products" class="container-fluid">
			<h2>Products</h2>
			<div class="row">
				<div class="container">
					<div class="row">
						<?php foreach( $division_products as $p ): // variable must NOT be called $post (IMPORTANT) ?>
							<div class="d-flex col-lg-4">
								<a href="<?php echo get_permalink( $p->ID ); ?>">
									<div class="row vh100">
										<div class="d-flex col-11 align-self-center">
											<h3><?php echo get_the_title( $p->ID ); ?></h3>
										</div>
										<div class="d-flex col-1 accent">&gt;</div>
									</div>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="container">
		<div class="divisions-insights row">
			<?php $division_whats_new = get_field('division_whats_new');
			if( $division_whats_new ): ?>
				<div id="division-whats-new" class="col-md-6">
					<h2>What&#39;s New</h2>
					<?php foreach( $division_whats_new as $p ): // variable must NOT be called $post (IMPORTANT) ?>
						<a href="<?php echo get_permalink( $p->ID ); ?>">
							<h3><?php echo get_the_title( $p->ID ); ?></h3>
						</a>
						<p><?php the_field('news_article_summary', $p->ID); ?></p>
					<?php endforeach; ?>
					<a href="<?php echo get_permalink(391); ?>" class="text-cta d-print-none">See all News</a> <span class="text-cta-accent d-print-none">&gt;</span>
				</div>
			<?php endif; ?>

			<?php $division_featured_case_studies = get_field('division_featured_case_studies');
			if( $division_featured_case_studies ): ?>
				<div id="division-featured-cases" class="col-md-6">
					<h2>Featured Technical Documents</h2>
					<?php foreach( $division_featured_case_studies as $p ): // variable must NOT be called $post (IMPORTANT) ?>
						<a href="<?php echo get_permalink( $p->ID ); ?>">
							<h3><?php echo get_the_title( $p->ID ); ?></h3>
						</a>
						<p><?php the_field('case_study_summary', $p->ID); ?></p>
					<?php endforeach; ?>
					<a href="<?php echo get_permalink(357); ?>" class="text-cta d-print-none">See all Technical Documents</a> <span class="text-cta-accent d-print-none">&gt;</span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>