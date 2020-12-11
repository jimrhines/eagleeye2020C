<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$container = get_theme_mod( 'understrap_container_type' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-5DMKHFN');</script>
	<!-- End Google Tag Manager -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
	<!-- Start SharpSpring Tracking -->
	<script>
		var _ss = _ss || [];
		_ss.push(['_setDomain', 'https://koi-3QNMNV32OO.marketingautomation.services/net']);
		_ss.push(['_setAccount', 'KOI-49SV1JOW94']);
		_ss.push(['_trackPageView']);
		(function() {
		    var ss = document.createElement('script');
		    ss.type = 'text/javascript';
		    ss.async = true;
		    ss.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'koi-3QNMNV32OO.marketingautomation.services/client/ss.js?ver=2.4.0';
		    var scr = document.getElementsByTagName('script')[0];
		    scr.parentNode.insertBefore(ss, scr);
		})();
	</script>
	<!-- End SharpSpring Tracking -->
</head>

<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DMKHFN" height="0" width="0" style="display:none; visibility:hidden;"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php do_action( 'wp_body_open' ); ?>
<div class="site" id="page">
	<!-- ******************* The Navbar Area ******************* -->
	<div id="wrapper-navbar" itemscope itemtype="http://schema.org/WebSite">

		<a class="skip-link sr-only sr-only-focusable" href="#content"><?php esc_html_e( 'Skip to content', 'understrap' ); ?></a>

		<div class="d-none d-print-block">
			<div class="d-flex flex-row">
				<div class="col-4">
					<img src="<?php echo esc_url( home_url( '/' ) ); ?>wp-content/themes/understrap-child/img/eeps-logo.svg" alt="<?php bloginfo( 'name' ); ?>" />
				</div>
				<div class="col-8">
					<?php the_field('corporate_phone_number', 'option'); ?>
				</div>
			</div>
		</div>

		<?php if ( 'container' == $container ) : ?>
			<div class="container">
		<?php endif; ?>
			<nav class="navbar navbar-expand-lg navbar-light">
				<a class="navbar-brand" rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" itemprop="url">
					<img src="/wp-content/themes/understrap-child/img/eeps-logo.svg" width="300" height="45" alt="<?php bloginfo( 'name' ); ?>" />
				</a>

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarTogglerDemo02">
					<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
						<li class="nav-item megamenu">
							<a class="nav-link" href="<?php echo get_permalink(174); ?>">Products</a>
							<div class="megamenu-content">
						    	<div class="row">
						    		<div class="col-md-2 mb-3">
					    				<?php wp_nav_menu( array( 'theme_location' => 'products-col-one', 'container_class' => 'products-col-one' ) ); ?>
									</div>
						    		<div class="col-md-2 mb-3">
						    			<?php wp_nav_menu( array( 'theme_location' => 'products-col-two', 'container_class' => 'products-col-two' ) ); ?>
									</div>
									<div class="col-md-2 mb-3">
										<?php wp_nav_menu( array( 'theme_location' => 'products-col-three', 'container_class' => 'products-col-three' ) ); ?>
									</div>
									<div class="col-md-2 mb-3">
										<?php wp_nav_menu( array( 'theme_location' => 'products-col-four', 'container_class' => 'products-col-four' ) ); ?>
									</div>
									<div class="col-md-2 mb-3">
										<?php wp_nav_menu( array( 'theme_location' => 'products-col-five', 'container_class' => 'products-col-five' ) ); ?>
									</div>
									<div class="col-md-2 mb-3">
										<?php wp_nav_menu( array( 'theme_location' => 'products-col-six', 'container_class' => 'products-col-six' ) ); ?>
									</div>
								</div>
							</div>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo get_permalink(1474); ?>">Industries</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo get_permalink(297); ?>">Training</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo get_permalink(2257); ?>">News &amp; Resources</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo get_permalink(295); ?>">Support</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo get_permalink(485); ?>">About Us</a>
						</li>
					</ul>
					<?php wp_nav_menu(
						array(
							'theme_location'  => 'primary',
							'container_class' => 'collapse navbar-collapse',
							'container_id'    => 'navbarNavDropdown',
							'menu_class'      => 'navbar-nav ml-auto',
							'fallback_cb'     => '',
							'menu_id'         => 'main-menu',
							'depth'           => 2,
							'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
						)
					); ?>
					<div class="my-2 my-lg-0 pl-lg-1">
						<a href="#search">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M23.822 20.88l-6.353-6.354c.93-1.465 1.467-3.2 1.467-5.059.001-5.219-4.247-9.467-9.468-9.467s-9.468 4.248-9.468 9.468c0 5.221 4.247 9.469 9.468 9.469 1.768 0 3.421-.487 4.839-1.333l6.396 6.396 3.119-3.12zm-20.294-11.412c0-3.273 2.665-5.938 5.939-5.938 3.275 0 5.94 2.664 5.94 5.938 0 3.275-2.665 5.939-5.94 5.939-3.274 0-5.939-2.664-5.939-5.939z"/></svg>
						</a>
					</div>
				</div>
			</nav>

			<div id="search">
				<div class="row">
					<div class="col-md-10">
			    		<?php get_search_form(); ?>
			    	</div>
		    		<div id="search-cancel-container" class="col-md-2">
		    			<a href="" id="search-cancel">Cancel</a>
			    	</div>
			    </div>
			</div>

		</div><!-- #wrapper-navbar end -->

	<?php if ( 'container' == $container ) : ?>
		</div><!-- .container -->
	<?php endif; ?>

	<?php //Enable Yoast Breadcrumbs on all pages except the home page
	if(!is_front_page()) :?>
		<div id="breadcrumbs-container" class="container-fluid">
			<div class="container">
				<?php
					if ( function_exists('yoast_breadcrumb') ) {
					  yoast_breadcrumb( '<p id="breadcrumbs" class="d-print-none">','</p>' );
					}
				?>
			</div>
		</div>
	<?php endif;?>
	<?php if(is_page_template('page-division.php')) :?>
		<div class="landing-hero d-flex flex-column align-items-baseline" style="background-image: url('<?php the_field('division_hero_image'); ?>');">
			<div class="landing-hero-headline mt-auto">
				<h1><?php the_title_attribute(); ?></h1>
			</div>
		</div>
	<?php endif;?>