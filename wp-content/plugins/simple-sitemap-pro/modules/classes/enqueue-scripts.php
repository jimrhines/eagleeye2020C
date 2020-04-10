<?php
/*
 *	Enqueue plugin scripts
*/

class WPGO_Simple_Sitemap_Enqueue_Scripts {

	protected $module_roots;

	/* Main class constructor. */
	public function __construct($module_roots) {

		$this->module_roots = $module_roots;

		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'register_front_end_scripts' ) );
		add_action( 'init', array( &$this, 'block_init' ) );
		add_action( 'enqueue_block_assets', array( &$this, 'enqueue_assets' ) );
	}

	/**
	 * Enqueue front end and editor JavaScript and CSS assets.
	 */
	public function enqueue_assets() {

		// if ( is_admin() ) {
		// 	$dep = array( 'jquery' );
		// } else {
			//$dep = array(  'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor', 'jquery' );
			$dep = array( 'jquery' );
//		}

		// only enqueue scripts on pages containing sitemap blocks
		if (
			has_blocks() && (
			has_block( 'wpgoplugins/simple-sitemap-block' ) ||
			has_block( 'wpgoplugins/simple-sitemap-group-block' )
			) ) {
			wp_enqueue_style( 'simple-sitemap-css', plugins_url( 'modules/css/simple-sitemap.css', $this->module_roots['file'] ) );
			wp_enqueue_script( 'simple-sitemap-js', plugins_url( 'modules/js/simple-sitemap.js', $this->module_roots['file'] ), $dep );
		} elseif (
			!has_blocks() ||
			!(
			has_block( 'wpgoplugins/simple-sitemap-block' ) ||
			has_block( 'wpgoplugins/simple-sitemap-group-block' )
			)	) {
			// Add scripts to the editor if no blocks have been added. Otherwise when a sitemap block is added before any other blocks have been added and the page refreshed the sitemap scripts won't have been enqueued.
			wp_enqueue_style( 'simple-sitemap-css', plugins_url( 'modules/css/simple-sitemap.css', $this->module_roots['file'] ) );
			wp_enqueue_script( 'simple-sitemap-js', plugins_url( 'modules/js/simple-sitemap.js', $this->module_roots['file'] ), $dep );
		}
	}

	/* Scripts just for the plugin settings page. */
	public function enqueue_admin_scripts($hook) {

		if($hook != 'toplevel_page_simple-sitemap-menu') {
			return;
		}

		wp_enqueue_style( 'simple-sitemap-settings-css', plugins_url('modules/css/simple-sitemap-admin.css', $this->module_roots['file']) );
		wp_enqueue_script( 'simple-sitemap-settings-js', plugins_url('modules/js/simple-sitemap-admin.js', $this->module_roots['file']) );
	}

	/*
	 * Enqueue scripts for the front end
	 */
	public function register_front_end_scripts() {

		//wp_register_style( 'simple-sitemap-css', plugins_url( 'modules/css/simple-sitemap.css', $this->module_roots['file'] ) );
		//wp_register_script( 'simple-sitemap-js', plugins_url( 'modules/js/simple-sitemap.js', $this->module_roots['file'] ), array( 'jquery' ) );
	}

	/**
	 * Register our block.
	 */
	public function block_init() {

		// only register block if gutenberg enabled
		if( function_exists( 'register_block_type' ) ) {

			// Register our block editor script.
			wp_register_script(
				'simple-sitemap-block',
				plugins_url( 'modules/block_assets/js/blocks.editor.js', $this->module_roots['file'] ),
				array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' )
			);

			// Register our block, and explicitly define the attributes we accept.
			register_block_type( 'wpgoplugins/simple-sitemap-block', array(
				'attributes' => array(
					'max_width' => [
						'type' => 'string',
						'default' => '',
					],
					'responsive_breakpoint' => [
						'type' => 'string',
						'default' => '500px',
					],
					'sitemap_container_margin' => [
						'type' => 'string',
						'default' => '1em 0 0 0',
					],
					'sitemap_item_line_height' => [
						'type' => 'string',
						'default' => '',
					],
					'render_tab' => [
						'type' => 'boolean',
						'default' => 'false'
					],
					'show_excerpt' => [
						'type' => 'boolean',
						'default' => 'false'
					],
					'nofollow' => [
						'type' => 'boolean',
						'default' => 'false'
					],
					'image' => [
						'type' => 'boolean',
						'default' => 'false'
					],
					'list_icon' => [
						'type' => 'boolean',
						'default' => 'true'
					],
					'post_type_label_font_size' => [
						'type' => 'string',
						'default' => '1.2em'
					],
					'tab_header_bg' => [
						'type' => 'string',
						'default' => '#de5737'
					],		
					'tab_color' => [
						'type' => 'string',
						'default' => '#ffffff'
					],		
					'post_type_label_padding' => [
						'type' => 'string',
						'default' => '10px 20px'
					],
					'exclude' => array(
						'type' => 'string'
					),
					'include' => array(
						'type' => 'string'
					),
					'gutenberg_block' => array(
						'type'  => 'boolean',
						'default' => true,
					),
					'orderby' => array(
						'type' => 'string',
						'default' => 'title'
					),
					'order' => array(
						'type' => 'string',
						'default' => 'asc'
					),
					'visibility' => array(
						'type' => 'boolean',
						'default' => true
					),
					'horizontal' => array(
						'type' => 'boolean',
						'default' => false
					),
					'page_depth' => array(
						'type' => 'number',
						'default' => 0
					),
					'block_post_types'  => [
						'type'  => 'string',
						'default' => '[{ "value": "page", "label": "Pages" }]'
					],
					'show_label' => [
						'type' => 'boolean',
						'default' => true
					],
					'links' => [
						'type' => 'boolean',
						'default' => true
					]
				),
				'editor_script'   => 'simple-sitemap-block', // The script name we gave in the wp_register_script() call.
				'render_callback' => array( 'WPGO_Simple_Sitemap_Shortcode', 'render' ),
			) );

			// Register our block, and explicitly define the attributes we accept.
			register_block_type( 'wpgoplugins/simple-sitemap-group-block', array(
				'attributes' => array(
					'sitemap_container_margin' => [
						'type' => 'string',
						'default' => '1em 0 0 0',
					],
					'sitemap_item_line_height' => [
						'type' => 'string',
						'default' => '',
					],		
					'show_excerpt' => [
						'type' => 'boolean',
						'default' => 'false'
					],
					'show_label' => [
						'type' => 'boolean',
						'default' => true
					],
					'links' => [
						'type' => 'boolean',
						'default' => true
					],
					'nofollow' => [
						'type' => 'boolean',
						'default' => 'false'
					],
					'image' => [
						'type' => 'boolean',
						'default' => 'false'
					],
					'list_icon' => [
						'type' => 'boolean',
						'default' => 'true'
					],
					'post_type_label_font_size' => [
						'type' => 'string',
						'default' => '1.2em'
					],
					'post_type_label_padding' => [
						'type' => 'string',
						'default' => '10px 20px'
					],
					'include_terms' => array(
						'type' => 'string',
						'default' => ''
					),
					'exclude_terms' => array(
						'type' => 'string',
						'default' => ''
					),
					'orderby' => array(
						'type' => 'string',
						'default' => 'title'
					),
					'order' => array(
						'type' => 'string',
						'default' => 'asc'
					),
					'visibility' => array(
						'type' => 'boolean',
						'default' => true
					),
					'page_depth' => array(
						'type' => 'number',
						'default' => 0
					),
					'block_post_type'  => [
						'type'  => 'string',
						'default' => 'post'
					],
					'block_taxonomy'  => [
						'type'  => 'string',
						'default' => 'category'
					],
					'gutenberg_block' => array(
						'type'  => 'boolean',
						'default' => true
					)
				),
				'editor_script'   => 'simple-sitemap-block', // The script name we gave in the wp_register_script() call.
				'render_callback' => array( 'WPGO_Simple_Sitemap_Group_Shortcode', 'render' ),
			) );			
		}
	}

} /* End class definition */