<?php
/**
 * The template for displaying video list pages
 * 
 * Template Name: Video List Template
 *
 * This is the template that displays video list pages.
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
				<main id="main" class="site-main video-list">
					<?php while ( have_posts() ) : the_post(); 
						get_template_part( 'loop-templates/content', 'page' );
					endwhile; // end of the loop. ?>

					<?php $listProducts = get_field('list_products');
					if( $listProducts ): ?>
						<ul class="list-group list-group-flush">
							<?php foreach( $listProducts as $p ): // variable must NOT be called $post (IMPORTANT) ?>
								<li class="list-group-item">
									<?php if( have_rows('product_videos', $p->ID) ): ?>
										<h2><?php echo get_the_title( $p->ID ); ?></h2>
										<div id="product-videos" class="d-print-none">
											<div class="container">
												<div class="row">
													<?php while( have_rows('product_videos', $p->ID) ): the_row();
														$video_url = get_sub_field('video_url');
														$video_caption = get_sub_field('video_caption');
													?>
														<div class="col-lg-4">
															<div class="embed-container">
																<?php echo $video_url; ?>
															</div>
															<p><strong><?php echo $video_caption; ?></strong></p>
														</div>
													<?php endwhile; ?>
												</div>
											</div>
										</div>
									<?php endif; ?>
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