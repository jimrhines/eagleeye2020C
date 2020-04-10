<?php
/**
 * The template for displaying product detail image gallery pages
 * 
 * Template Name: Product Detail Image Gallery Template
 *
 * This is the template that displays product detail image gallery page.
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
			<div id="primary" class="content-area">
				<main id="main" class="site-main">
					<?php while ( have_posts() ) : the_post(); ?>
						<div class="row">
							<?php if( have_rows('product_image_gallery') ): ?>
								<div id="gallery" class="col">
									<h1><?php the_title_attribute(); ?></h1>
									<div class="container">
										<div class="row">
											<?php 
											$images = get_field('product_image_gallery');
											if( $images ): 
												$image_count = -1;
											    foreach( $images as $image ):
													$image_count++;
												?>
											    	<div class="col-lg-3 col-md-4 col-xs-6 thumb">
												    	<a href="#" data-toggle="modal" data-target="#productImageModal" onClick="javascript:goToSlide(<?php echo $image_count ?>);">
												             <img src="<?php echo esc_url($image['sizes']['medium']); ?>" class="img-thumbnail" alt="<?php echo esc_attr($image['alt']); ?>" />
												        </a>
												    </div>
											    <?php endforeach; ?>
											<?php endif; ?>
											<!-- Product Image Gallery Modal -->
											<div class="modal fade" id="productImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-xl" role="document">
											    	<div class="modal-content">
											      		<div class="modal-header">
													        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
													        </button>
											      		</div>
											      		<div class="modal-body">
															<div id="galleryImageModal-carouselExampleControls" class="carousel slide" data-ride="">
																<div class="carousel-inner">
																   	<?php if( $images ): ?>
																		<?php foreach( $images as $image ): ?>
																		    <div class="carousel-item <?php if ($image === reset($images)) { print('active'); } ?>">
																				<img src="<?php echo esc_url($image['sizes']['large']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
																		    </div>
																	    <?php endforeach; ?>
															  		<?php endif; ?>
															  	</div>
															</div>
														  	<a class="carousel-control-prev" href="#galleryImageModal-carouselExampleControls" role="button" data-slide="prev">
																<span class="carousel-control-prev-icon" aria-hidden="true"></span>
														    	<span class="sr-only">Previous</span>
														  	</a>
														  	<a class="carousel-control-next" href="#galleryImageModal-carouselExampleControls" role="button" data-slide="next">
														    	<span class="carousel-control-next-icon" aria-hidden="true"></span>
														    	<span class="sr-only">Next</span>
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>
					<?php endwhile; // End of the loop.?>
				</main>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>