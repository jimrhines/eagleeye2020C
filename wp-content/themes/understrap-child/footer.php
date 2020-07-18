<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$container = get_theme_mod( 'understrap_container_type' );
?>

<?php get_template_part( 'sidebar-templates/sidebar', 'footerfull' ); ?>

<?php if(!is_page(array( 13, 1790 ))) :?>
	<div id="newsletter-signup" class="d-print-none">
		<?php if ( shortcode_exists( 'contact-form-7' ) ) { 
			echo do_shortcode('[contact-form-7 id="2195" title="Newsletter Signup"]'); 
		} ?>
		<p><small><?php bloginfo( 'name' ); ?> respects your privacy. We don&#39;t rent or sell your personal information to anyone. Ever. <a href="<?php echo get_permalink(3); ?>">Read our <?php echo get_the_title(3); ?></a>.</small></p>
	</div>
	<!-- Start SharpSpring Signup Form -->
	<script>
	    var __ss_noform = __ss_noform || [];
	    __ss_noform.push(['baseURI', 'https://app-3QNMNV32OO.marketingautomation.services/webforms/receivePostback/MzawMLEwMjK0AAA/']);
	    __ss_noform.push(['endpoint', 'ebb85961-277d-491d-a0cb-c9caf3c667b3']);
	</script>
	<script src="https://koi-3QNMNV32OO.marketingautomation.services/client/noform.js?ver=1.24"></script>
	<!-- End SharpSpring Signup Form -->
<?php endif;?>

<div class="wrapper" id="wrapper-footer">
	<footer class="site-footer" id="colophon">
		<div class="container">
			<div class="row d-print-none">
				<div id="eeps-footer-navigation" class="col-12 col-lg-9">
					<div class="row">
						<div class="col-6 col-md-3">
							<?php wp_nav_menu( array( 'theme_location' => 'footer-col-one' ) ); ?>
						</div>
						<div class="col-6 col-md-3">
							<?php wp_nav_menu( array( 'theme_location' => 'footer-col-two' ) ); ?>
						</div>
						<div class="col-6 col-md-3">
							<?php wp_nav_menu( array( 'theme_location' => 'footer-col-three' ) ); ?>
						</div>
						<div class="col-6 col-md-3">
							<?php wp_nav_menu( array( 'theme_location' => 'footer-col-four' ) ); ?>
						</div>
					</div>
					<a class="btn btn-primary" href="<?php echo get_permalink(13); ?>" role="button">Get a Quote &gt;</a>
				</div>
				<div id="eeps-channels" class="col-12 col-lg-3">
					<div class="row">
						<div class="col-6 col-lg-12">
							<h5>Connect with us</h5>
							<a href="tel:+<?php the_field('corporate_phone_number', 'option'); ?>"><?php the_field('corporate_phone_number', 'option'); ?></a>
							<ul id="eeps-social" itemscope itemtype="https://schema.org/Organization">
								<li>
									<a href="https://twitter.com/EagleEyePower" target="_blank" itemprop="sameAs" rel="me"><svg width="30" height="30" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Eagle Eye Power Solutions on Twitter</title><path d="M23.954 4.569a10 10 0 0 1-2.825.775 4.958 4.958 0 0 0 2.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 0 0-8.384 4.482C7.691 8.094 4.066 6.13 1.64 3.161a4.822 4.822 0 0 0-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 0 1-2.228-.616v.061a4.923 4.923 0 0 0 3.946 4.827 4.996 4.996 0 0 1-2.212.085 4.937 4.937 0 0 0 4.604 3.417 9.868 9.868 0 0 1-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 0 0 7.557 2.209c9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63a9.936 9.936 0 0 0 2.46-2.548l-.047-.02z"></path></svg></a>
								</li>
								<li>
									<a href="https://www.facebook.com/EagleEyePowerSolutions" target="_blank" itemprop="sameAs" rel="me"><svg width="30" height="30" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Eagle Eye Power Solutions on Facebook</title><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"></path></svg></a>
								</li>
								<li>
									<a href="https://www.youtube.com/user/EEpowersolutions" target="_blank" itemprop="sameAs" rel="me"><svg width="40" height="40" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Eagle Eye Power Solutions on YouTube</title><path d="M23.495 6.205a3.007 3.007 0 0 0-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 0 0 .527 6.205a31.247 31.247 0 0 0-.522 5.805 31.247 31.247 0 0 0 .522 5.783 3.007 3.007 0 0 0 2.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 0 0 2.088-2.088 31.247 31.247 0 0 0 .5-5.783 31.247 31.247 0 0 0-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"></path></svg></a>
								</li>
								<li>
									<a href="https://www.linkedin.com/company/eagle-eye-power-solutions-llc/" target="_blank" itemprop="sameAs" rel="me"><svg width="30" height="30" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Eagle Eye Power Solutions on LinkedIn</title><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"></path></svg></a>
								</li>	
							</ul>
						</div>
						<div id="eeps-iso" class="col-6 col-lg-12">
							<img src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/uploads/2020/06/eagle-eye-power-solutions-iso-logo.png" alt="ISO 9001: 2008 Certified - Eagle Eye Power Solutions" />
						</div>
					</div>
				</div>
			</div>
			<div class="row site-info">
				<div class="col-12 col-lg-6">
					<p><small>Copyright <?php echo date('Y') ;?> Eagle Eye Power Solutions, LLC. All Rights Reserved.</small></p>
				</div>
				<div id="legal" class="col-12 col-lg-6 d-print-none">
					<ul>
						<li><a href="<?php echo get_permalink(113); ?>"><small>Site Map</small></a></li>
						<li><a href="<?php echo get_permalink(206); ?>"><small>Legal Notice</small></a></li>
					</ul>
				</div>
			</div><!-- .site-info -->
		</div><!-- container end -->
	</footer><!-- #colophon -->
</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>
</body>

</html>