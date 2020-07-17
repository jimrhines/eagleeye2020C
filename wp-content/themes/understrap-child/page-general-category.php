<?php
/**
 * The template for displaying general category pages
 * 
 * Template Name: General Category Template
 *
 * This is the template that displays general category pages.
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
					<header class="entry-header">	
						<h1 class="entry-title"><?php the_title_attribute(); ?></h1>
					</header>
					<?php $category_products = get_field('category_products');
					if( $category_products ): ?>
						<div class="container">
							<div id="category-general" class="row">
								<?php foreach( $category_products as $p ): // variable must NOT be called $post (IMPORTANT) ?>
									<div class="d-flex col-lg-4 w-100">
										<a href="<?php echo get_permalink( $p->ID ); ?>">

											<?php if (has_post_thumbnail( $p->ID ) ) {
												//echo get_the_post_thumbnail( $p->ID );
												$category_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $p->ID ), '' ) 
												?>
												<img src="<?php echo $category_thumbnail['0']; ?>" width="100%" alt="<?php echo get_the_title( $p->ID ); ?>" />
											<?php } ?>
											
											<h2 class="h3"><?php echo get_the_title( $p->ID ); ?></h2>
											<p>
												<?php the_field('product_description', $p->ID); ?>
												<?php the_field('class_summary', $p->ID); ?>
											</p>
										</a>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</main>
			</div>
		</div>
	</div>
					
	<div class="container-fluid">
		<div class="row secondary-cta d-print-none">
			<div class="col h2">
				<span>Need pricing?</span> <a class="btn btn-primary button-super" href="<?php echo get_permalink(13); ?>" role="button">Get a Quote &gt;</a>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>