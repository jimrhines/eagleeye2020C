<?php
/**
 * The template for testing CLS
 * 
 * Template Name: CLS Testing Template
 *
 * This is the template for testing CLS.
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
			<div id="primary" class="col-md content-area">
				<main id="main" class="site-main">
					<h1>CLS Testing Alpha</h1>
				</main>
			</div>
		</div>
	</div>
</div>

<?php //get_footer(); ?>

<div id="newsletter-signup" class="d-print-none">
	<p>something</p>
</div>

<?php wp_footer(); ?>