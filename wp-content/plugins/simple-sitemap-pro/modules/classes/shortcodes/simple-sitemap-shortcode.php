<?php

namespace WPGO_Plugins\Simple_Sitemap;

/*
 * Class for the [simple-sitemap] shortcode
*/

class WPGO_Simple_Sitemap_Shortcode {

  protected $module_roots;

	  /* Main class constructor. */
	  public function __construct($module_roots) {

	  $this->module_roots = $module_roots;

	  add_shortcode( 'simple-sitemap', array( 'WPGO_Plugins\Simple_Sitemap\WPGO_Simple_Sitemap_Shortcode', 'render' ) );
  	add_shortcode( 'ss', array( 'WPGO_Plugins\Simple_Sitemap\WPGO_Simple_Sitemap_Shortcode', 'render' ) );
	}

	public function render($attr) {

		/* Get attributes from the shortcode. */
		$args = shortcode_atts( array(
			'id' => uniqid(), // unique identifier to avoid conflicts if using multiple sitemaps on the same page. e.g. 5d026c6168954
			'render' => '',
			'types' => 'page',
			'orderby' => 'title',
			'order' => 'asc',
			'include' => '',
			'exclude' => '',
			'show_excerpt' => 'false',
			'page_excerpt_length' => '25',
			'show_label' => 'true',
			'links' => 'true',
			'image' => 'false',
			'list_icon' => 'true',
			'nofollow' => 'false',
			'page_depth' => 0,
			'visibility' => 'true',
			'horizontal' => 'false', // great for adding to footer

			// following attributes don't have block support yet
			'horizontal_separator' => ', ',
			'num_posts' => -1,
			'title_tag' => '',
			'post_type_tag' => 'h3',
			'container_tag' => 'ul',
			'excerpt_tag' => 'div',
			'separator' => 'false',
			'shortcode_type' => 'normal', // only used internally?
			'parent_page_link' => '1', // is this used anymore, or only plugin settings version?			
			// attributes below are only relevant to sitemap block			
			'gutenberg_block' => false,
			'block_post_types' => '',
			'render_tab' => false,
			'post_type_label_font_size' => '1em',
			'sitemap_item_line_height' => '',
			'sitemap_container_margin' => '1em 0 0 0',
			'responsive_breakpoint' => '500px',
			'max_width' => '',
			'post_type_label_padding' => '10px 20px',
			'tab_header_bg' => '#de5737',
			'tab_color' => '#ffffff'
		), $attr );

		// escape tag names
		$args['container_tag'] = tag_escape( $args['container_tag'] );
		$args['title_tag'] = tag_escape( $args['title_tag'] );
		$args['excerpt_tag'] = tag_escape( $args['excerpt_tag'] );
		$args['post_type_tag'] = tag_escape( $args['post_type_tag'] );

		$block_err = '';

		// Format attributes as necessary

		// if we are rendering a block then parse block attributes
		if($args['gutenberg_block'] === true) {

			// setup block types coming from block
			$args['types'] = '';
			$block_cpts = json_decode($args['block_post_types']);
			if(empty($block_cpts)) {
				$block_err = '<h5>Please select one or more post types in the sitemap inspector panel.</h5>';
			} else {
				foreach($block_cpts as $cpt) {
					$args['types'] .= $cpt->value . ', ';
				}
			}

			// enable tabs depending on block settings
			if( $args['render_tab'] === true ) {
				$args['render'] = 'tab';
			}

			// sanitize post_type_label_font_size
			$args['post_type_label_font_size'] = sanitize_text_field($args['post_type_label_font_size']);

			// sanitize sitemap_item_line_height
			$args['sitemap_item_line_height'] = sanitize_text_field($args['sitemap_item_line_height']);

			// sanitize sitemap_container_margin
			$args['sitemap_container_margin'] = sanitize_text_field($args['sitemap_container_margin']);

			// sanitize post_type_label_padding
			$args['post_type_label_padding'] = sanitize_text_field($args['post_type_label_padding']);

			// sanitize responsive_breakpoint
			$args['responsive_breakpoint'] = sanitize_text_field($args['responsive_breakpoint']);

			// sanitize max_width
			$args['max_width'] = sanitize_text_field($args['max_width']);

			// sanitize color picker hex values
			$args['tab_header_bg'] = sanitize_hex_color($args['tab_header_bg']);
			$args['tab_color'] = sanitize_hex_color($args['tab_color']);
		}

		// force 'ul' or 'ol' to be used as the container tag
		$allowed_container_tags = array('ul', 'ol');
		if(!in_array($args['container_tag'], $allowed_container_tags)) {
			$args['container_tag'] = 'ul';
		}

		// validate numeric values
		$args['page_depth'] = intval( $args['page_depth'] );

		$container_format_class = ($args['list_icon'] == "true") ? '' : ' hide-icon';
		$render_class = empty($args['render']) ? ' tab-disabled' : ' tab-enabled';

		// ******************
		// ** OUTPUT START **
		// ******************

		// Start output buffering (so that existing content in the [simple-sitemap] post doesn't get shoved to the bottom of the post
		ob_start();

		if($block_err) {
			return $block_err;
		}

		// output styles
		$container = '.simple-sitemap-container-' . $args['id']; // applies styles to tabbed AND normal sitemap
		$container_tab = $container . '.tab-enabled'; // applies styles ONLY to tabbed sitemap
		echo '<style type="text/css">';
		echo $container_tab . ' .panel { border-top: 4px solid ' . $args['tab_header_bg'] . '; } ';
		echo $container_tab . ' input:checked + label { background-color: ' . $args['tab_header_bg'] . '; } ';
		echo $container_tab . ' input:checked + label > * { color: ' . $args['tab_color'] . '; } ';
		echo $container_tab . ' { max-width: ' . $args['max_width'] . '; }';
		if(!empty($args['sitemap_item_line_height']) ) {
			echo $container . ' .sitemap-item { line-height: ' . $args['sitemap_item_line_height'] . '; } ';
		}
		echo $container . ' { margin: ' . $args['sitemap_container_margin'] . '; } ';
		echo '@media only screen and (max-width: ' . $args['responsive_breakpoint'] . ') { .simple-sitemap-container-' . $args['id'] . '.tab-enabled label { width: 100%; border-radius: 0; } .simple-sitemap-container-' . $args['id'] . '.tab-enabled label[for^="simple-sitemap-tab-1"] { border-radius: 4px 4px 0 0; } }';
		echo '</style>';		

		$post_types = array_map( 'trim', explode( ',', $args['types'] ) ); // convert comma separated string to array
		$registered_post_types = get_post_types();

		$sitemap_id = ' simple-sitemap-container-' .  $args['id'];
		$container_classes = 'simple-sitemap-container' . $sitemap_id . $render_class . $container_format_class;
		echo '<div class="' . esc_attr($container_classes) . '">';

		// conditionally output tab headers
		if( $args['render'] == 'tab' ):

			// create tab headers
			$header_tab_index = 1; // initialize to 1
			foreach( $post_types as $post_type ) {

				if( !array_key_exists( $post_type, $registered_post_types ) )
					break; // bail if post type isn't valid

				$checked = $header_tab_index === 1 ? 'checked' : '';
				$post_type_label = WPGO_Shortcode_Utility::get_post_type_label($args['show_label'], $post_type, $args['post_type_tag'], $args['post_type_label_font_size']);

				$post_type_label_styles = WPGO_Shortcode_Utility::get_post_type_label_styles($args['post_type_label_padding']);

				echo '<input type="radio" name="tab-' . $args['id'] . '" id="simple-sitemap-tab-' . $header_tab_index . '-' . $args['id'] . '" ' . $checked . '>
				<label' . $post_type_label_styles . ' for="simple-sitemap-tab-' . $header_tab_index . '-' . $args['id'] . '">' . $post_type_label . '</label>';

				$header_tab_index++;
			}

		endif;

		// tab panel wrapper - open
		if( $args['render'] == 'tab' ) { echo '<div class="simple-sitemap-content">'; }

		// conditionally create tab panels
		$header_tab_index = 1; // reset to 1
		foreach( $post_types as $post_type ) :

			if( !array_key_exists( $post_type, $registered_post_types ) )
				break; // bail if post type isn't valid

			// set opening and closing title tag
			if( !empty($args['title_tag']) ) {
				$args['title_open'] = '<' . $args['title_tag'] . '>';
				$args['title_close'] = '</' . $args['title_tag'] . '>';
			}
			else {
				$args['title_open'] = $args['title_close'] = '';
			}

			$post_type_label = WPGO_Shortcode_Utility::get_post_type_label($args['show_label'], $post_type, $args['post_type_tag'], $args['post_type_label_font_size']);

			// tab panel wrapper - open
			if( $args['render'] == 'tab' ) {
				$list_item_wrapper_class = 'simple-sitemap-wrap simple-sitemap-tab-' . $header_tab_index . ' panel';
			} else {
				$list_item_wrapper_class = 'simple-sitemap-wrap';
			}

			$header_tab_index++;
			echo '<div class="' . esc_attr($list_item_wrapper_class) . '">';
			if( $args['render'] != 'tab' ) echo $post_type_label;

			$query_args = WPGO_Shortcode_Utility::get_query_args($args, $post_type);
			WPGO_Shortcode_Utility::render_list_items($args, $post_type, $query_args);

		endforeach;

		// tab panel wrapper - close
		if( $args['render'] == 'tab' ) { echo '</div>'; } // .simple-sitemap-content

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