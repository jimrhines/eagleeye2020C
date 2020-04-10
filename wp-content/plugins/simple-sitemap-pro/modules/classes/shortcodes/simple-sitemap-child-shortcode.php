<?php
/*
 *      Class for the [simple-sitemap-child] shortcode
*/

class WPGO_Simple_Sitemap_Child_Shortcode {


	 /* Main class constructor. */
	public function __construct() {

		add_shortcode( 'simple-sitemap-child', array( &$this, 'render_sitemap_child' ) );
		add_shortcode( 'ssc', array( &$this, 'render_sitemap_child' ) );
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

class WPGO_Simple_Sitemap_Child_Shortcode_Custom_Walker extends Walker_Page {
	/**
	 * Outputs the beginning of the current element in the tree.
	 *
	 * @see Walker::start_el()
	 * @since 2.1.0
	 *
	 * @param string  $output       Used to append additional content. Passed by reference.
	 * @param WP_Post $page         Page data object.
	 * @param int     $depth        Optional. Depth of page. Used for padding. Default 0.
	 * @param array   $args         Optional. Array of arguments. Default empty array.
	 * @param int     $current_page Optional. Page ID. Default 0.
	 */
	public function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
		if ( isset( $args['item_spacing'] ) && 'preserve' === $args['item_spacing'] ) {
			$t = "\t";
			$n = "\n";
		} else {
			$t = '';
			$n = '';
		}
		if ( $depth ) {
			$indent = str_repeat( $t, $depth );
		} else {
			$indent = '';
		}

		$css_class = array( 'page_item', 'page-item-' . $page->ID );

		if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
			$css_class[] = 'page_item_has_children';
		}

		if ( ! empty( $current_page ) ) {
			$_current_page = get_post( $current_page );
			if ( $_current_page && in_array( $page->ID, $_current_page->ancestors ) ) {
				$css_class[] = 'current_page_ancestor';
			}
			if ( $page->ID == $current_page ) {
				$css_class[] = 'current_page_item';
			} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {
				$css_class[] = 'current_page_parent';
			}
		} elseif ( $page->ID == get_option( 'page_for_posts' ) ) {
			$css_class[] = 'current_page_parent';
		}

		/**
		 * Filters the list of CSS classes to include with each page item in the list.
		 *
		 * @since 2.8.0
		 *
		 * @see wp_list_pages()
		 *
		 * @param string[] $css_class    An array of CSS classes to be applied to each list item.
		 * @param WP_Post  $page         Page data object.
		 * @param int      $depth        Depth of page, used for padding.
		 * @param array    $args         An array of arguments.
		 * @param int      $current_page ID of the current page.
		 */
		$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );
		$css_classes = $css_classes ? ' class="' . esc_attr( $css_classes ) . '"' : '';

		if ( '' === $page->post_title ) {
			/* translators: %d: ID of a post */
			$page->post_title = sprintf( __( '#%d (no title)' ), $page->ID );
		}

		$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
		$args['link_after']  = empty( $args['link_after'] ) ? '' : $args['link_after'];

		$atts                 = array();
		$atts['href']         = get_permalink( $page->ID );
		$atts['aria-current'] = ( $page->ID == $current_page ) ? 'page' : '';

		/**
		 * Filters the HTML attributes applied to a page menu item's anchor element.
		 *
		 * @since 4.8.0
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $href         The href attribute.
		 *     @type string $aria_current The aria-current attribute.
		 * }
		 * @param WP_Post $page         Page data object.
		 * @param int     $depth        Depth of page, used for padding.
		 * @param array   $args         An array of arguments.
		 * @param int     $current_page ID of the current page.
		 */
		$atts = apply_filters( 'page_menu_link_attributes', $atts, $page, $depth, $args, $current_page );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$excerpt_text = wp_trim_words( strip_shortcodes( $page->post_content ), $this->ssp_excerpt_length );
		$excerpt = $this->ssp_excerpt == 'true' ? '<div class="excerpt">' . $excerpt_text . '</div>' : '';

		/*
				echo "<pre>";
		print_r($);
		echo "</pre>";

		*/

		$output .= $indent . sprintf(
			'<li%s><a%s>%s%s%s</a>%s',
			$css_classes,
			$attributes,
			$args['link_before'],
			/** This filter is documented in wp-includes/post-template.php */
			apply_filters( 'the_title', $page->post_title, $page->ID ),
			$args['link_after'],
			$excerpt
		);

		if ( ! empty( $args['show_date'] ) ) {
			if ( 'modified' == $args['show_date'] ) {
				$time = $page->post_modified;
			} else {
				$time = $page->post_date;
			}

			$date_format = empty( $args['date_format'] ) ? '' : $args['date_format'];
			$output     .= ' ' . mysql2date( $date_format, $time );
		}
	}
}