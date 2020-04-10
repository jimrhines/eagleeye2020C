<?php
/*
 *      Class for the [simple-sitemap-menu] shortcode
*/

class WPGO_Simple_Sitemap_Menu_Shortcode {


	 /* Main class constructor. */
	public function __construct() {

		add_shortcode( 'simple-sitemap-menu', array( &$this, 'render_menu_sitemap' ) );
		add_shortcode( 'ssm', array( &$this, 'render_menu_sitemap' ) );
	}

	public function render_menu_sitemap($attr) {

		/* Get attributes from the shortcode. */
		$args = shortcode_atts( array(
			'menu' => '',
			'container' => false,
			'menu_class' => 'simple-sitemap-nav-menu',
			'horizontal_separator' => ', ',
			'list_icon' => 'true',
			'container_class' => '',
			'label' => ''
		), $attr );

		$label = !empty($args['label'])	? '<h3>' . $args['label'] . '</h3>'	: '';

		// ******************
		// ** OUTPUT START **
		// ******************

		// Start output caching (so that existing content in the [simple-sitemap] post doesn't get shoved to the bottom of the post
		ob_start();

		$container_format_class = ($args['list_icon'] == "true") ? '' : ' hide-icon';
		$container_classes = 'simple-sitemap-container simple-sitemap-menu' . $container_format_class;
		echo '<div class="' . esc_attr($container_classes) . '">';
		echo $label;
		wp_nav_menu( array(
			'menu' => $args['menu'],
			'container' => $args['container'],
			'container_class' => $args['container_class'],
			'menu_class' => $args['menu_class']
		) );
		echo '</div>'; // .simple-sitemap-container

		// @todo check we still need this
		echo '<br style="clear: both;">'; // make sure content after the sitemap is rendered properly if taken out

		$sitemap = ob_get_contents();
		ob_end_clean();

		// ****************
		// ** OUTPUT END **
		// ****************

		return $sitemap;
	}

}