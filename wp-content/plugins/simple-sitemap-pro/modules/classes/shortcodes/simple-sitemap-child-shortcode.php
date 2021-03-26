<?php

namespace WPGO_Plugins\Simple_Sitemap;

/*
 *      Class for the [simple-sitemap-child] shortcode
*/

class WPGO_Simple_Sitemap_Child_Shortcode {

	 /* Main class constructor. */
	public function __construct() {

    return;

		add_shortcode( 'simple-sitemap-child', array( 'WPGO_Plugins\Simple_Sitemap\WPGO_Simple_Sitemap_Child_Shortcode', 'render_sitemap_child' ) );
		add_shortcode( 'ssc', array( 'WPGO_Plugins\Simple_Sitemap\WPGO_Simple_Sitemap_Child_Shortcode', 'render_sitemap_child' ) );
	}

	/* Shortcode function. */
	public function render_sitemap_child($attr) {

		/* Get attributes from the shortcode. */
		$args = shortcode_atts( array(
			'child_of' => '0',
			'title_li' => '',
			'nofollow' => 'false',
			'post_type' => 'page',
			'show_excerpt' => 'false',
			'page_excerpt_length' => '25',
		), $attr );

		$args['echo'] = 0; // don't echo out output immediately as we need to optionally add a nofollow attribute
		$args['walker'] = new WPGO_Simple_Sitemap_Child_Shortcode_Custom_Walker();
		$args['walker']->ssp_excerpt = $args['show_excerpt'];
		$args['walker']->ssp_excerpt_length = $args['page_excerpt_length'];

		if ( $args[ 'title_li' ] == '@' ) {
			if ( ! empty( $args[ 'child_of' ] ) ) {
				$args[ 'title_li' ] = "<a href=" . esc_url( get_permalink( $args[ 'child_of' ] ) ) . ">" . get_the_title( $args[ 'child_of' ] ) . "</a>";
			} else {
				$args[ 'title_li' ] = '';
			}
		}

		if ( $args['nofollow'] == 'true' ) {
			$list = wp_list_pages( $args );
			$list = stripslashes(WPGO_Shortcode_Utility::wp_rel_nofollow( $list ));
		} else {
			$list = wp_list_pages( $args );
		}

		return "<ul class='ss-top-level'>" . $list . "</ul>";
	}
}