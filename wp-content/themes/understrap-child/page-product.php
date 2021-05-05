<?php
/**
 * The template for displaying product detail pages
 * 
 * Template Name: Product Detail Template
 *
 * This is the template that displays product detail pages.
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
							<div class="col text-right mt-4 pr-4">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>request-a-quote/?product-name=<?php the_field('product_model_number'); ?>" class="btn btn-primary d-print-none" role="button">Get a Quote &#62;</a>
								<?php							
									$product_page_url = esc_url( home_url( '/' ) )."product/".basename(get_permalink());
									$product_page_slug = basename(get_permalink());									
									//echo $product_page_slug;

									$page = get_page_by_path($product_page_slug, OBJECT, 'product');

									if(!$page){
								        //echo "Does not exist.";
								    } else {
								        //echo "Exists";
								        echo "<a href=".$product_page_url." class='btn btn-secondary ml-3 d-print-none' role='button'>Buy Now &#62;</a>";
								    } 

								?>
							</div>
						</div>
						<div class="row">
							<?php if (has_post_thumbnail( $p->ID ) ) { ?>
								<div class="col-md-4">
									<?php 
									$images = get_field('product_images');
									$image_count = 0;
									?>
									<a href="#" data-toggle="modal" data-target="#productImageModal" onClick="javascript:goToSlide(0);">
										<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
									</a>
									
									<div id="product-thumbnails" class="row justify-content-center">
										<?php if( $images ): ?>
											<?php foreach( $images as $image ): 
												$image_count++;
											?>
												<div class="col-3">
													<a href="#" data-toggle="modal" data-target="#productImageModal" onClick="javascript:goToSlide(<?php echo $image_count ?>);">
														<img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
													</a>
												</div>
											<?php endforeach; ?>
										<?php endif; ?>
									</div>
									<?php $product_image_gallery_url = get_field('product_image_gallery_url');
									if( $product_image_gallery_url ): ?>
										<?php foreach( $product_image_gallery_url as $p ): // variable must NOT be called $post (IMPORTANT) ?>
											<div class="text-center">
												<a href="<?php echo get_permalink( $p->ID ); ?>">View More <?php the_title_attribute(); ?> Images</a>
											</div>
										<?php endforeach; ?>
									<?php endif; ?>

									<!-- Product Image Modal -->
									<div class="modal fade" id="productImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-xl" role="document">
									    	<div class="modal-content">
									      		<div class="modal-header">
											        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
											        </button>
									      		</div>
									      		<div class="modal-body">
													<div id="carouselExampleControls" class="carousel slide" data-ride="">
														<div class="carousel-inner">
														    <div class="carousel-item active">
														    	<?php echo get_the_post_thumbnail( $post->ID, 'full' ); ?>
														    </div>
														    <?php 
															if( $images ): ?>
															    <?php foreach( $images as $image ): ?>
																    <div class="carousel-item">
																		<img src="<?php echo $image['sizes']['1536x1536']; ?>" alt="<?php echo $image['alt']; ?>" />
																    </div>
																<?php endforeach; ?>
													  		<?php endif; ?>
													  	</div>
													  	<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
															<span class="carousel-control-prev-icon" aria-hidden="true"></span>
													    	<span class="sr-only">Previous</span>
													  	</a>
													  	<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
													    	<span class="carousel-control-next-icon" aria-hidden="true"></span>
													    	<span class="sr-only">Next</span>
														</a>
													</div>
									     		</div>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
							
							<div id="product-narrative" class="col-md-8">
								<h1><?php the_title_attribute(); ?></h1>
								<?php get_template_part( 'loop-templates/content-blank', 'nofeaturedimg' ); ?>
							</div>
						</div>
					<?php endwhile; // End of the loop.?>
				</main>
			</div>
		</div>
	</div>

	<div id="specifications-tabs" class="container-fluid">
		<div class="row">
			<ul id="myTab" class="nav nav-tabs justify-content-center d-print-none" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="specifications-tab" data-toggle="tab" href="#specifications" role="tab" aria-controls="specifications" aria-selected="true">Specifications</a>
				</li>
				<?php if( have_rows('product_downloads_catalogs') || have_rows('product_downloads_manuals') || have_rows('product_downloads_drawings') ): ?>
					<li class="nav-item">
						<a class="nav-link" id="support-downloads-tab" data-toggle="tab" href="#support-downloads" role="tab" aria-controls="support-downloads" aria-selected="false">Downloads</a>
					</li>
				<?php endif; ?>
				<?php $product_faqs = get_field('product_faqs');
				if( $product_faqs ): ?>
					<li class="nav-item">
						<a class="nav-link" id="support-faqs-tab" data-toggle="tab" href="#support-faqs" role="tab" aria-controls="support-faqs" aria-selected="false">FAQs</a>
					</li>
				<?php endif; ?>
				<?php $relevant_trainings = get_field('relevant_trainings');
					if( $relevant_trainings ): ?>
						<li class="nav-item">
							<a class="nav-link" id="training-tab" data-toggle="tab" href="#training" role="tab" aria-controls="training" aria-selected="false">Training &amp; Services</a>
						</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	<div class="container">
		<div class="row justify-content-md-center">
			<div id="myTabContent" class="tab-content">
				<div id="specifications" class="tab-pane fade show active col" role="tabpanel" aria-labelledby="specifications-tab">
					<div class="row">
						<div class="col-md-2">&#160;</div>
						<div class="col-md-8">
							<h3 class="sr-only">Specifications</h3>
							<?php the_field('product_specifications'); ?>
						</div>
						<div class="col-md-2">&#160;</div>
					</div>
				</div>
				<?php if( have_rows('product_downloads_catalogs') || have_rows('product_downloads_manuals') || have_rows('product_downloads_drawings') ): ?>
					<div id="support-downloads" class="tab-pane fade col" role="tabpanel" aria-labelledby="support-downloads-tab">
						<h3 class="sr-only">Downloads</h3>
						<div class="container-fluid">
							<div class="row">
								<div class="product-downloads col-md-4">
									<?php if( have_rows('product_downloads_catalogs') ): ?>
										<h4>Product Literature</h4>
										<ul>
											<?php while( have_rows('product_downloads_catalogs') ): the_row(); 
												// vars
												$file = get_sub_field('file');
												?>

												<li>
													<?php if( $file ): ?>
														<a href="<?php echo $file['url']; ?>">
													<?php endif; ?>

														<?php echo $file['title']; ?>

													<?php if( $file ): ?>
														</a>
													<?php endif; ?>

												</li>
											<?php endwhile; ?>
										</ul>
									<?php endif; ?>
								</div>
								<div class="product-downloads col-md-4">
									<?php if( have_rows('product_downloads_manuals') ): ?>
										<h4>Support Documents</h4>
										<ul>
											<?php while( have_rows('product_downloads_manuals') ): the_row(); 
												// vars
												$file = get_sub_field('file');
												?>

												<li>
													<?php if( $file ): ?>
														<a href="<?php echo $file['url']; ?>">
													<?php endif; ?>

														<?php echo $file['title']; ?>

													<?php if( $file ): ?>
														</a>
													<?php endif; ?>

												</li>
											<?php endwhile; ?>
										</ul>
									<?php endif; ?>
								</div>
								<div class="product-downloads col-md-4">
									<?php if( have_rows('product_downloads_drawings') ): ?>
										<h4>Technical Information</h4>
										<ul>
											<?php while( have_rows('product_downloads_drawings') ): the_row(); 
												// vars
												$file = get_sub_field('file');
												?>
												<li>
													<?php if( $file ): ?>
														<a href="<?php echo $file['url']; ?>"><?php echo $file['title']; ?></a>
													<?php endif; ?>
												</li>
											<?php endwhile; ?>
										</ul>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<?php if( $product_faqs ): ?>
					<div id="support-faqs" class="tab-pane fade col" role="tabpanel" aria-labelledby="support-faqs-tab">		
						<h3 class="sr-only">FAQs</h3>
						<?php the_field('product_faqs'); ?>
					</div>
				<?php endif; ?>
				<?php if( $relevant_trainings ): ?>
					<div id="training" class="tab-pane fade col" role="tabpanel" aria-labelledby="training-tab">
						<h3 class="sr-only">Product Training</h3>
						<p>If you would like product specific training, please contact Eagle Eye directly. We offer complimentary training over the phone. Quotes can be provided for on site product-training. For more information call <a href="tel:+1-877-805-3377">1-877-805-3377</a> or email us at <a href="mailto:info@eepowersolutions.com">info@eepowersolutions.com</a>. We provide timely support for all inquiries.</p>
						<h4>EEU Training Courses</h4>
						<p>Eagle Eye University (EEU) provides scheduled courses tailored to specific aspects of the critical power industry. All courses are also available at your location. The following courses are applicable to <?php the_title_attribute(); ?>.</p>
						<div class="row">
							<?php foreach( $relevant_trainings as $p ): // variable must NOT be called $post (IMPORTANT) ?>
							    <div class="d-md-flex justify-content-between mb-3 col-lg-4">
								    <div class="relevant-training">
								    	<a href="<?php echo get_permalink( $p->ID ); ?>">
								    		<?php echo get_the_post_thumbnail( $p->ID, 'large' ); ?>
								    	</a>
								    	<a href="<?php echo get_permalink( $p->ID ); ?>">
								    		<h5><?php echo get_the_title( $p->ID ); ?></h5>
								    	</a>
								    	<?php the_field('class_start_date', $p->ID); ?>
								    	<?php if( get_field('class_end_date', $p->ID) ): ?>
								    		- <?php the_field('class_end_date', $p->ID); ?>
								    	<?php endif; ?>
								    	<?php the_field('class_summary', $p->ID); ?>
								    </div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php if( have_rows('product_videos') ): ?>
		<div id="product-videos" class="d-print-none">
			<h2>Videos</h2>
			<div class="container">
				<div class="row">
					<?php while( have_rows('product_videos') ): the_row();
						$video_url = get_sub_field('video_url');
						?>
						<div class="col-lg-6">
							<div class="embed-container">
								<?php echo $video_url; ?>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php $related_products = get_field('related_products');
	if( $related_products ): ?>
		<div id="related-products" class="container-fluid">
			<h2>Similar Products</h2>
			<div class="row">
				<div class="container">
					<div class="row justify-content-md-center">
						<?php foreach( $related_products as $p ): // variable must NOT be called $post (IMPORTANT) ?>
							<div class="d-md-flex justify-content-between mb-3 col-md-4 col-lg-3">
							    <div class="related-product">
							    	<a href="<?php echo get_permalink( $p->ID ); ?>">
							    		<?php echo get_the_post_thumbnail( $p->ID, 'thumbnail' ); ?>
							    	</a>
							    	<a href="<?php echo get_permalink( $p->ID ); ?>">
							    		<h5 class="d-flex flex-column justify-content-center"><?php echo get_the_title( $p->ID ); ?></h5>
							    	</a>
							    	<a href="<?php echo get_permalink( $p->ID ); ?>">
							    		<div class="btn btn-primary d-print-none">Learn More &gt;<span class="sr-only"> about <?php echo get_the_title( $p->ID ); ?></span></div>
							    	</a>
							    </div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

</div>

<script type="application/ld+json">
	{
	    "@context": "http://schema.org",
	    "@type": "ItemList",
	    "url": "<?php echo get_permalink( get_queried_object_id() ); ?>",
	    "itemListElement": [
	        {
	            "@type": "Product",
	            "image": "<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>",
	            "url": "<?php echo get_permalink( get_queried_object_id() ); ?>",
	            "name": "<?php the_title_attribute(); ?>", 
	            "brand": "Eagle Eye Power Solutions",
	            "description": "<?php echo get_post_meta($post->ID, '_yoast_wpseo_metadesc', true); ?>"
	        }
	    ]
	}
</script>
<?php get_footer(); ?>