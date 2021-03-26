<?php

namespace WPGO_Plugins\Simple_Sitemap;

/*
 * Plugin options class.
 */

 class WPGO_Simple_Sitemap_Settings {

	protected $module_roots;

	/* Main class constructor. */
	public function __construct($module_roots) {

		$this->module_roots = $module_roots;

		add_action( 'admin_init', array( &$this, 'register_plugin_settings' ) );
		add_action( 'admin_menu', array( &$this, 'add_options_page' ) );
		add_filter( 'simple_sitemap_defaults', array( &$this, 'add_defaults' ) );
	}

	/**
	 * Register plugin options with Settings API.
	 *
	 */
	public function register_plugin_settings() {

		/* Register plugin options settings for all tabs. */
		register_setting( 'simple_sitemap_options_group', 'simple_sitemap_options',	array( $this, 'sanitize_plugin_options' )	);
	}

	/**
	 * Register plugin options page, and enqueue scripts/styles.
	 *
	 * @since 0.1.0
	 */
	public function add_options_page() {

		add_menu_page(
			__( 'Simple Sitemap Settings Page', 'simple-sitemap' ),
			__( 'Simple Sitemap', 'simple-sitemap' ),
			'manage_options',
			'simple-sitemap-menu',
			array( &$this, 'render' ),
			'dashicons-pressthis',
			82
		);
	}

	/* Define default option settings. */
	public function add_defaults($defaults) {

		$defaults["txtar_sitemap_script"] = "";
		$defaults["chk_parent_page_link"] = "0";
		$defaults["txt_exclude_parent_pages"] = "";
		$defaults["default_on_checkboxes"]["chk_parent_page_link"] =  "0";

		return $defaults;
	}

	/*
	 * Sanitize plugin options.
	 *
	 * Get rid of the local license key status option when adding a new one
	 *
	 */
	public function sanitize_plugin_options( $input ) {

		// strip html from textboxes
		$input['txtar_sitemap_script'] = wp_filter_nohtml_kses( $input['txtar_sitemap_script'] );
		$input['txt_exclude_parent_pages'] = wp_filter_nohtml_kses( $input['txt_exclude_parent_pages'] );

		/* Sanitize plugin options via this filter hook. */
		// this allows you to sanitize options via another class
		//return WPGO_Simple_Sitemap_Hooks::wpgo_sanitize_plugin_options( $input );
		return $input;
	}

	/**
	 * Display plugin options page.
	 *
	 * @since 0.1.0
	 */
	public function render() {
		?>
		<div class="wrap">

			<h2><?php _e( 'Welcome to Simple Sitemap!', 'simple-sitemap' ); ?></h2>
			<div style="position:absolute;padding:15px;top:5px;right:0;"><a style="text-decoration:none;" title="Check out some of our other plugins" alt="WPGO Plugins Site" href="https://wpgoplugins.com/" target="_blank">wpgoplugins.com</a></div>

			<div style="clear:both;"></div>

			<?php
			// Check to see if user clicked on the reset options button
			if ( isset( $_POST['reset_options'] ) ) :

				// Reset plugin defaults
				update_option( 'simple_sitemap_options', self::get_default_plugin_options() );

				// Display update notice here
				?>
				<div style="margin:20px 0 10px;">
					<p><strong><?php echo 'Settings reset.'; ?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
				</div>
				<?php

			endif;
			?>

			<div style="margin:20px 0 10px;font-size:14px;line-height:1.4em;">What type of <a href="http://demo.wpgothemes.com/flexr/simple-sitemap-pro-demo/" target="_blank">sitemap</a> will you create today? There are many different types to choose from. Also, check out the plugin documentation and live demo for more detailed information on the available sitemap blocks, shortcodes, and plugin settings.</div>

			<div><span><a class="plugin-btn" href="http://demo.wpgothemes.com/flexr/simple-sitemap-pro-demo/" target="_blank">Launch Demo</a></span><span style="margin-left:10px;"><a class="plugin-btn" href="https://wpgoplugins.com/document/simple-sitemap-pro-documentation/" target="_blank">Documentation</a></span></div>

			<h2 style="margin:35px 0 0 0;">Sitemap Settings</h2>

			<div class="ss-box" style="margin-top:20px;">
				<h4 style="margin-top:5px;display:inline-block;margin-bottom:10px;">Plugin Settings</h4><button id="settings-btn" class="button">Expand <span style="vertical-align:sub;width:16px;height:16px;font-size:16px;" class="dashicons dashicons-arrow-down-alt2"></span></button>

				<div style="margin:15px;" id="settings-wrap">

					<!-- Start Main Form -->
					<form id="plugin-options-form" method="post" action="options.php">
						<?php
						$options = self::get_plugin_options();
						settings_fields( 'simple_sitemap_options_group' );
						?>

						<div class="simple-sitemap-pro-tab">
							<table class="form-table">

								<tr valign="top">
									<td colspan="2" style="padding:0;">
										<div>

											<label><input name="simple_sitemap_options[chk_parent_page_link]" type="checkbox" value="1" <?php if ( isset( $options['chk_parent_page_link'] ) ) { checked( '1', $options['chk_parent_page_link'] ); } ?>> Remove parent page links?</label><br><br>

											<input type="text" class="exclude regular-text code" name="simple_sitemap_options[txt_exclude_parent_pages]" value="<?php echo $options['txt_exclude_parent_pages']; ?>">

											<p class="description">Enter comma separated list of parent page IDs to remove specific links. Leave blank to remove ALL parent page links.</p>
										<div>
									</td>
								</tr>

								<tr valign="top" style="display:none;">
									<th scope="row">Advanced Configuration</th>
									<td>
										<textarea name="simple_sitemap_options[txtar_sitemap_script]" rows="7" cols="50" type='textarea'><?php echo $options['txtar_sitemap_script']; ?></textarea>
										<p class="description">Add script into the box above to output an advanced sitemap.</p>
									</td>
								</tr>
							</table>
						</div>

						<div class="support-tab">
							<?php do_settings_sections( 'simple-sitemap-menu' ); ?>
						</div>

						<?php submit_button(); ?>

					</form>
					<!-- main form closing tag -->

					<form action="<?php echo self::currURL(); // current page url ?>" method="post" id="simple-sitemap-reset-form" style="display:inline;">
						<span id="simple-sitemap-reset"><a href="#">Reset plugin options</a><input type="hidden" name="reset_options" value="true"></span>
					</form>
				</div>
			</div>

			<h2 style="margin:35px 0 0 0;">Sitemap Blocks <span style="font-style:italic;color: red;">*NEW*</span></h2>

			<div class="ss-box" style="margin-top:20px;">
				<h4 style="margin-top:5px;display:inline-block;margin-bottom:10px;">Available Blocks</h4><button id="blocks-btn" class="button">Expand <span style="vertical-align:sub;width:16px;height:16px;font-size:16px;" class="dashicons dashicons-arrow-down-alt2"></span></button>
				
				<div id="blocks-wrap">
					<p style="margin:15px;">I'm pleased to announce that Simple Sitemap plugin now includes support for <a href="https://wordpress.org/gutenberg/" target="_blank">blocks</a>! This means you can now easily add a sitemap visually and configure settings via the new Gutenberg block based editor.</p>

					<p style="margin:10px;">There's currently one block available at the moment that replaces the <code>[simple-sitemap]</code> shortcode. Subsequent plugin releases will include more settings for this block plus another separate sitemap block to replace the <code>[simple-sitemap-group]</code> shortcode.</p>

					<div style="margin:20px 0 0 10px;text-align:center;"><img style="max-width:650px;" src="<?php echo $this->module_roots['pdir']; ?>shared/images/simple-sitemap-block.png" /></div>
	
					<div style="margin:10px;">
						<h4>Instructions:</h4>
						<ol>
							<li>Inside the new editor click the plus icon to insert a new block.</li>
							<li>In the popup dialog either search directly for 'sitemap' or scroll down until you see the Simple Sitemap section, and expand it.</li>
							<li>Once you see the sitemap icon click on it to add the Simple Sitemap block to the editor.</li>
							<li>Make sure the block is selected to display the sitemap block settings in the inspector panel. Configure as necessary.</li>
							<li>That's it! Pretty simple huh? :-)</li>
						</ol>
					</div>
				</div>
			</div>

			<h2 style="margin:35px 0 0 0;">Sitemap Shortcodes</h2>

			<div class="ss-box" style="margin-top:20px;">
				<h4 style="margin-top:5px;display:inline-block;margin-bottom:10px;">Available Shortcodes</h4><button id="shortcodes-btn" class="button">Expand <span style="vertical-align:sub;width:16px;height:16px;font-size:16px;" class="dashicons dashicons-arrow-down-alt2"></span></button>

				<div id="shortcodes-wrap">

					<p>Click on the shortcodes below to view the full documentation for each shortcode. We recommend using sitemap blocks inside the new WordPress editor rather than shortcodes.</p>
	
					<p style="margin:15px 0 0 0;"><code><a class="code-link" href="https://wpgoplugins.com/document/simple-sitemap-pro-documentation/#simple-sitemap" target="_blank">[simple-sitemap]</a></code> - <?php printf( __( 'Displays a list of posts for one or more post types.', 'simple-sitemap' ) ); ?></p>

					<p style="margin:15px 0 0 0;"><code><a class="code-link" href="https://wpgoplugins.com/document/simple-sitemap-pro-documentation/#simple-sitemap-group" target="_blank">[simple-sitemap-group]</a></code> - <?php printf( __( 'Displays a list of posts grouped by taxonomy.', 'simple-sitemap' ) ); ?></p>

					<p style="margin:15px 0 0 0;"><code><a class="code-link" href="https://wpgoplugins.com/document/simple-sitemap-pro-documentation/#simple-sitemap-tax" target="_blank">[simple-sitemap-tax]</a></code> - <?php printf( __( 'Displays a list of taxonomy terms for any registered taxonomy (e.g. categories).', 'simple-sitemap' ) ); ?></p>

					<p style="margin:15px 0 0 0;"><code><a class="code-link" href="https://wpgoplugins.com/document/simple-sitemap-pro-documentation/#simple-sitemap-menu" target="_blank">[simple-sitemap-menu]</a></code> - <?php printf( __( 'Displays a sitemap based on a nav menu.', 'simple-sitemap' ) ); ?></p>

					<p style="margin:15px 0 30px 0;"><code><a class="code-link" href="https://wpgoplugins.com/document/simple-sitemap-pro-documentation/#simple-sitemap-child" target="_blank">[simple-sitemap-child]</a></code> - <?php printf( __( 'Displays a list of child pages for a specific parent page.', 'simple-sitemap' ) ); ?></p>

					<hr><br><?php printf( __( 'The following (public) registered post types are available: ', 'simple-sitemap' ) ); ?>
					<?php
					$post_type_args = array(
						'public'   => true
					);
					$registered_post_types = get_post_types($post_type_args);

					// remove 'attachment' (media) from list of post types
					if( in_array('attachment', $registered_post_types) ) {
						unset($registered_post_types['attachment']);
					}

					$registered_post_types_str = implode(', ', $registered_post_types);
					echo '<code>"' . $registered_post_types_str . '"</code>';
					?><br><br>

				</div>
			</div>

			<div style="margin-top:25px;"></div>

			<table class="form-table">

				<tr valign="top">
					<th scope="row">Try our other top plugins!</th>
					<td>
						<table class="other-plugins-tbl">
							<tr><td><a class="plugin-image-link" href="https://wpgoplugins.com/plugins/flexible-faqs/" target="_blank"><img src="<?php echo $this->module_roots['pdir']; ?>shared/images/flexible-faq-thumb.png"></a></td></tr>
							<tr><td class="plugin-text-link"><div><h3><a href="https://wpgoplugins.com/plugins/flexible-faqs/" target="_blank">Flexible FAQs</a></h3></div></td></tr>
						</table>
						<table class="other-plugins-tbl">
							<tr><td><a class="plugin-image-link" href="https://wpgoplugins.com/plugins/content-censor/" target="_blank"><img src="<?php echo $this->module_roots['pdir']; ?>shared/images/content-censor-thumb.png"></a></td></tr>
							<tr><td class="plugin-text-link"><div><h3><a href="https://wpgoplugins.com/plugins/content-censor/" target="_blank">Content Censor</a></h3></div></td></tr>
						</table>
						<table class="other-plugins-tbl">
							<tr><td><a class="plugin-image-link" href="https://wpgoplugins.com/plugins/seo-media-manager/" target="_blank"><img src="<?php echo $this->module_roots['pdir']; ?>shared/images/seo-media-manager-thumb.png"></a></td></tr>
							<tr><td class="plugin-text-link"><div><h3><a href="https://wpgoplugins.com/plugins/seo-media-manager/" target="_blank">SEO Media Manager</a></h3></div></td></tr>
						</table>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Read all about it!</th>
					<td>
						<p>Subscribe to our newsletter for news and updates about the latest development work. Be the first to find out about future projects and exclusive promotions.</p>
						<div><a class="plugin-btn" target="_blank" href="http://eepurl.com/bXZmmD">Sign Me Up!</a></div>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">Keep in touch...</th>
					<td>
						<div><p style="margin-bottom:10px;">Come and say hello. I'd love to hear from you!</p>
							<span><a class="social-link" href="http://www.twitter.com/dgwyer" title="Follow us on Twitter" target="_blank"><img src="<?php echo $this->module_roots['pdir']; ?>shared/images/twitter.png" /></a></span>
							<span><a class="social-link" href="https://www.facebook.com/wpgoplugins/" title="Our Facebook page" target="_blank"><img src="<?php echo $this->module_roots['pdir']; ?>shared/images/facebook.png" /></a></span>
							<span><a class="social-link" href="https://www.youtube.com/channel/UCWzjTLWoyMgtIfpDgJavrTg" title="View our YouTube channel" target="_blank"><img src="<?php echo $this->module_roots['pdir']; ?>shared/images/yt.png" /></a></span>
							<span><a style="text-decoration:none;" title="Need help with ANY aspect of WordPress? We're here to help!" href="https://wpgoplugins.com/need-help-with-wordpress/" target="_blank"><span style="margin-left:-2px;color:#d41515;font-size:39px;line-height:32px;width:39px;height:39px;" class="dashicons dashicons-sos"></span></a></span>
						</div>
					</td>
				</tr>

				<tr><td colspan="2" style="padding:0;"><div style="margin-bottom:20px;margin-top:15px;">Please <a href="https://wpgoplugins.com/contact" target="_blank">report</a> any plugin issues, or suggest additional features. <span style="font-weight:bold;">All feedback welcome!</span></div>
					</td></tr>

			</table>

		</div>
		<?php
	}

	/**
	 * Get URL of current page.
	 *
	 * @since 0.1.0
	 */
	public static function currURL() {

		return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
		"https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  
		$_SERVER['REQUEST_URI']; 
	}

	/**
	 * Get plugin option default settings.
	 *
	 * @since 0.1.0
	 */
	public static function get_default_plugin_options() {

		$defaults = array();

		// setup an array to store list of checkboxes that have a checkbox default set to 1
		$defaults["default_on_checkboxes"] = array();

		/* Add plugin specific default settings via this filter hook. */
		return WPGO_Simple_Sitemap_Hooks::simple_sitemap_defaults($defaults);
	}

	/**
	 * Get current plugin options.
	 *
	 * Merges plugin options with the defaults to ensure any gaps are filled.
	 * i.e. when adding new options.
	 *
	 */
	public static function get_plugin_options() {

		$options = get_option( 'simple_sitemap_options' );
		$defaults = self::get_default_plugin_options();

		// store the OFF checkboxes array
		$default_on_checkboxes_arr = $defaults["default_on_checkboxes"];

		// remove the OFF checkboxes array from the main defaults array
		unset($defaults["default_on_checkboxes"]);

		if( is_array($options) ) {
			// merge OFF checkboxes into main options array to add entries for empty checkboxes
			$options = array_merge( $default_on_checkboxes_arr, $options );
		}

		return wp_parse_args(
			$options,
			$defaults
		);

		//return wp_parse_args(
		//	get_option( 'simple_sitemap_options' ),
		//	self::get_default_plugin_options()
		//);
	}
}