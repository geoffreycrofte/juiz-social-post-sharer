<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

if ( ! function_exists( 'juiz_sps_plugin_action_links' ) ) {
	/**
	 * Add link to settings into Plugins Page
	 * @param  array $links  Array of links.
	 * @param  string $file  File root.
	 * @return array         New array of links.
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 * @author  Geoffrey Crofte
	 */
	function juiz_sps_plugin_action_links( $links, $file ) {
		$links[] = '<a href="' . jsps_get_settings_url() . '">' . __( 'Settings' ) . '</a>';
		$links[] = '<a href="' . jsps_get_welcome_url() . '">' . __( 'Welcome', 'juiz-social-post-sharer' ) . '</a>';
		return $links;
	}
	add_filter( 'plugin_action_links_' . plugin_basename( JUIZ_SPS_FILE ), 'juiz_sps_plugin_action_links',  10, 2 );
}

if ( ! function_exists( 'add_juiz_sps_settings_page' ) ) {
	/**
	 * Create a page of options
	 *
	 * @since   1.0.0
	 * @version 2.0.0
	 * @author  Geoffrey Crofte
	 */
	function add_juiz_sps_settings_page() {
		add_submenu_page( 
			'options-general.php', 
			__( 'Nobs Share Buttons', 'juiz-social-post-sharer' ),
			__( 'Nobs Share Buttons', 'juiz-social-post-sharer' ),
			'administrator',
			JUIZ_SPS_SLUG,
			'juiz_sps_settings_page' 
		);
	}
	add_action( 'admin_menu', 'add_juiz_sps_settings_page' );
}

/**
 * Sections and fields for settings.
 */
function add_juiz_sps_plugin_options() {
	// all options in single registration as array
	register_setting( JUIZ_SPS_SETTING_NAME, JUIZ_SPS_SETTING_NAME, 'juiz_sps_sanitize' );
	
	/**
	 * Themes and Networks SECTION
	 */
	add_settings_section( 'juiz_sps_plugin_main', __( 'Themes &amp; Networks', 'juiz-social-post-sharer' ), 'juiz_sps_section_text', JUIZ_SPS_SLUG);
	add_settings_field( 'juiz_sps_style_choice', __( 'Choose a style to display', 'juiz-social-post-sharer' ), 'juiz_sps_setting_radio_style_choice', JUIZ_SPS_SLUG, 'juiz_sps_plugin_main');
	add_settings_field( 'juiz_sps_temp_submit', get_submit_button( __( 'Save Changes' ), 'secondary' ), '__return_empty_string', JUIZ_SPS_SLUG, 'juiz_sps_plugin_main' );
	add_settings_field( 'juiz_sps_network_selection', __( 'Display those following social networks:', 'juiz-social-post-sharer' ) , 'juiz_sps_setting_checkbox_network_selection', JUIZ_SPS_SLUG, 'juiz_sps_plugin_main' );
	add_settings_field( 'juiz_sps_twitter_user', __( 'What is your X.com user name to be mentioned?', 'juiz-social-post-sharer' ) , 'juiz_sps_setting_input_twitter_user', JUIZ_SPS_SLUG, 'juiz_sps_plugin_main' );
	add_settings_field( 'juiz_sps_temp_submit_1', get_submit_button( __( 'Save Changes' ), 'primary' ), '__return_empty_string', JUIZ_SPS_SLUG, 'juiz_sps_plugin_main' );

	/**
	 * Display SECTION
	 */
	add_settings_section( 'juiz_sps_plugin_display_in', __( 'Display settings','juiz-social-post-sharer'), 'juiz_sps_section_text_display', JUIZ_SPS_SLUG );
	add_settings_field( 'juiz_sps_display_in_types', __( 'What type of content must have buttons?', 'juiz-social-post-sharer' ), 'juiz_sps_setting_checkbox_content_type', JUIZ_SPS_SLUG, 'juiz_sps_plugin_display_in' );
	add_settings_field( 'juiz_sps_display_where', __( 'Where do you want to display buttons?','juiz-social-post-sharer' ), 'juiz_sps_setting_radio_where', JUIZ_SPS_SLUG, 'juiz_sps_plugin_display_in' );
	add_settings_field( 'juiz_sps_compact_display', __( 'Prefer Compact Display', 'juiz-social-post-sharer' ) . '<br /><em>(' . __( 'Some skins can propose a more compact display, it will remove space around buttons and make them tinier depending on how the skin handles this option.', 'juiz-social-post-sharer' ) . ')</em>', 'juiz_sps_setting_radio_compact_display', JUIZ_SPS_SLUG, 'juiz_sps_plugin_display_in' );
	add_settings_field( 'juiz_sps_hide_social_name', __( 'Show only social icon?', 'juiz-social-post-sharer' ) . '<br /><em>(' . __( 'hide text, show it on mouse over or focus', 'juiz-social-post-sharer' ) . ')</em>', 'juiz_sps_setting_radio_hide_social_name', JUIZ_SPS_SLUG, 'juiz_sps_plugin_display_in' );
	add_settings_field( 'juiz_sps_temp_submit_2', get_submit_button( __( 'Save Changes' ), 'primary' ), '__return_empty_string', JUIZ_SPS_SLUG, 'juiz_sps_plugin_display_in' );

	/**
	 * Advanced Settings SECTION
	 */
	add_settings_section( 'juiz_sps_plugin_advanced', __( 'Advanced settings','juiz-social-post-sharer' ), 'juiz_sps_section_text_advanced', JUIZ_SPS_SLUG );
	add_settings_field( 'juiz_sps_target_link', __( 'Open links in a new window?', 'juiz-social-post-sharer' ).'<br /><em>(' . sprintf( __( 'adds a %s attribute', 'juiz-social-post-sharer' ), '<code>target="_blank"</code>' ) . ')</em>', 'juiz_sps_setting_radio_target_link', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');
	add_settings_field( 'juiz_sps_force_pinterest_snif', __( 'Force Pinterest button sniffing all images of the page?', 'juiz-social-post-sharer' ) . '<br /><em>(' . __( 'uses JavaScript', 'juiz-social-post-sharer' ) . ')</em>', 'juiz_sps_setting_radio_force_snif', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');
	add_settings_field( 'juiz_sps_counter', __( 'Display counter of sharing?', 'juiz-social-post-sharer' ) . '<br /><em>(' . __( 'uses JavaScript', 'juiz-social-post-sharer' ) . ')</em>', 'juiz_sps_setting_radio_counter', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced' );
	add_settings_field( 'juiz_sps_counter_option', __( 'For this counter, you want to display:', 'juiz-social-post-sharer' ), 'juiz_sps_setting_radio_counter_option', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced' );
	add_settings_field( 'juiz_sps_use_css', __( 'Use your own CSS file(s)?', 'juiz-social-post-sharer' ) . '<br /><em>(' . __( 'This option will make the plugin not loading the modal, or skin CSS file anymore. Good if you have a piece of CSS in your style.css Theme file.', 'juiz-social-post-sharer' ) . ')</em>', 'juiz_sps_setting_radio_use_css', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced' );
	add_settings_field( 'juiz_sps_temp_submit_3', get_submit_button( __( 'Save Changes' ), 'primary' ), '__return_empty_string', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced' );

	/**
	 * Mail customisation SECTION
	 */
	add_settings_section( 'juiz_sps_plugin_mail_informations', __( 'Customize mail texts', 'juiz-social-post-sharer' ), 'juiz_sps_section_text_mail', JUIZ_SPS_SLUG );
	add_settings_field( 'juiz_sps_mail_subject', __( 'Mail subject:', 'juiz-social-post-sharer' ), 'juiz_sps_setting_input_mail_subject', JUIZ_SPS_SLUG, 'juiz_sps_plugin_mail_informations' );
	add_settings_field( 'juiz_sps_mail_body', __( 'Mail body:', 'juiz-social-post-sharer'), 'juiz_sps_setting_textarea_mail_body', JUIZ_SPS_SLUG, 'juiz_sps_plugin_mail_informations' );
	add_settings_field( 'juiz_sps_temp_submit_4', get_submit_button( __( 'Save Changes' ), 'primary' ), '__return_empty_string', JUIZ_SPS_SLUG, 'juiz_sps_plugin_mail_informations' );

	/**
	 * What's new SECTION
	 */
	add_settings_section( 'juiz_sps_plugin_whats_new', __( 'What’s new', 'juiz-social-post-sharer' ), 'juiz_sps_section_text_whats_new', JUIZ_SPS_SLUG );
	add_settings_field( 'juiz_sps_readme_changelog', __( 'Changelog', 'juiz-social-post-sharer' ), 'juiz_sps_setting_changelog', JUIZ_SPS_SLUG, 'juiz_sps_plugin_whats_new' );

	/**
	 * What's new SECTION
	 */
	add_settings_section( 'juiz_sps_plugin_skin_shop', __( 'Skin Shop', 'juiz-social-post-sharer' ), 'juiz_sps_section_text_skin_shop', JUIZ_SPS_SLUG );
	add_settings_field( 'juiz_sps_skin_shop', __( 'Skin Shop', 'juiz-social-post-sharer' ), 'juiz_sps_setting_skin_shop', JUIZ_SPS_SLUG, 'juiz_sps_plugin_skin_shop' );

}
add_filter( 'admin_init', 'add_juiz_sps_plugin_options' );


// sanitize posted data
function juiz_sps_sanitize( $options ) {
	$newoptions = array();
	
	// Normal option update only send an array with visible networks. array('twitter', 'facebook')
	// AJAX Update send complete network array.
	// if $options['juiz_sps_networks']['twitter'] is set, it's an AJAX Request
	if ( isset( $options['juiz_sps_networks'] ) && is_array( $options['juiz_sps_networks'] ) && ! isset( $options['juiz_sps_networks']['twitter'] ) ) {

		//$juiz_sps_opt = jsps_get_option();
		$all_networks = jsps_get_all_registered_networks();
		
		// Change de visibility of a network if found in the juiz_sps_networks option sent.
		foreach ( $all_networks as $k => $v ) {
			$all_networks[ $k ]['visible'] = in_array( $k, $options['juiz_sps_networks'] ) ? 1 : 0;
		}

		$newoptions['juiz_sps_networks'] = $all_networks;

	} else {
		$newoptions['juiz_sps_networks'] = isset( $options['juiz_sps_networks'] ) ? $options['juiz_sps_networks'] : jsps_get_old_network_array();
	}

	$newoptions['juiz_sps_style'] = isset( $options['juiz_sps_style'] ) ? esc_attr( $options['juiz_sps_style'] ) : 8;
	$newoptions['juiz_sps_hide_social_name'] = isset( $options['juiz_sps_hide_social_name'] ) && (int) $options['juiz_sps_hide_social_name'] == 1 ? 1 : 0;
	$newoptions['juiz_sps_compact_display'] = isset( $options['juiz_sps_compact_display'] ) && (int) $options['juiz_sps_compact_display'] == 1 ? 1 : 0;
	$newoptions['juiz_sps_target_link'] = isset( $options['juiz_sps_target_link'] ) && (int) $options['juiz_sps_target_link'] == 1 ? 1 : 0;
	$newoptions['juiz_sps_counter'] = isset( $options['juiz_sps_counter'] ) && (int) $options['juiz_sps_counter'] == 1 ? 1 : 0;

	// new options (1.1.0)
	$newoptions['juiz_sps_write_css_in_html'] = isset( $options['juiz_sps_write_css_in_html'] ) && (int) $options['juiz_sps_write_css_in_html'] == 1 ? 1 : 0;
	$newoptions['juiz_sps_twitter_user'] = isset( $options['juiz_sps_twitter_user'] ) ?preg_replace( "#@#", '', sanitize_key( $options['juiz_sps_twitter_user'] ) ) : 'WPShareButtons';
	$newoptions['juiz_sps_mail_subject'] = isset( $options['juiz_sps_mail_subject'] ) ?sanitize_text_field( $options['juiz_sps_mail_subject'] ) : '';
	$newoptions['juiz_sps_mail_body'] = isset( $options['juiz_sps_mail_body'] ) ?sanitize_text_field( $options['juiz_sps_mail_body'] ) : '';

	if ( isset( $options['juiz_sps_display_in_types'] ) && is_array( $options['juiz_sps_display_in_types'] ) && count( $options['juiz_sps_display_in_types'] ) > 0 ) {
		$newoptions['juiz_sps_display_in_types'] = $options['juiz_sps_display_in_types'];
	} else {
		wp_redirect( admin_url( 'options-general.php?page=' . JUIZ_SPS_SLUG . '&message=1337' ) );
		exit;
	}

	$newoptions['juiz_sps_display_where'] = isset( $options['juiz_sps_display_where'] ) && in_array( $options['juiz_sps_display_where'], array( 'bottom', 'top', 'both', 'nowhere' ) ) ? $options['juiz_sps_display_where'] : 'bottom';

	// new options (1.2.5)
	$newoptions['juiz_sps_force_pinterest_snif'] = isset( $options['juiz_sps_force_pinterest_snif'] ) && (int) $options['juiz_sps_force_pinterest_snif'] == 1 ? 1 : 0;

	// new options (1.3.3.7)
	$newoptions['juiz_sps_counter_option'] = isset( $options['juiz_sps_counter_option'] ) && in_array( $options['juiz_sps_counter_option'], array( 'both', 'total', 'subtotal' ) ) ? $options['juiz_sps_counter_option'] : 'both';

	// new options (2.0.0)
	$newoptions['juiz_sps_css_files'] = isset( $options['juiz_sps_css_files'] ) && in_array( $options['juiz_sps_css_files'], array( 'both', 'nope', 'buttons', 'modal' ) ) ? $options['juiz_sps_css_files'] : 'nope';

	$newoptions['juiz_sps_order'] = isset( $options['juiz_sps_order'] ) && is_array( $options['juiz_sps_order'] ) ? $options['juiz_sps_order'] : array();

	$newoptions['juiz_sps_version'] = JUIZ_SPS_VERSION;
	
	return $newoptions;
}

// first section text
if ( ! function_exists( 'juiz_sps_section_text' ) ) {
	function juiz_sps_section_text() {
		echo '<p class="juiz_sps_section_intro">' . __( 'Edit the style of your buttons and the networks you want to activate.', 'juiz-social-post-sharer' ) . '</p>';
	}
}

// Radio fields styles choice
if ( ! function_exists( 'juiz_sps_setting_radio_style_choice' ) ) {
function juiz_sps_setting_radio_style_choice() {
	// Only for debugging.
	// jsps_update_option( jsps_get_initial_old_settings() );

	/**
	 * Some clean up in this first form element. (TODO: could be done somewhere else)
	 */
	if ( isset( $_GET['action'] ) && $_GET['action'] === 'reset-options' ) {
		jsps_delete_plugin_options();
	}

	$options      = jsps_get_option();
	$core_skins   = jsps_get_core_skins();
	$custom_skins = jsps_get_custom_skins();

	/**
	 * Another part of cleanup for multisite and single site.
	 * We do that only one time at the first option. (arbitrary)
	 */
	if ( ! is_array( $options ) ) {
		$options = jsps_get_initial_settings();
		if ( function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( JUIZ_SPS_SLUG . '/' . JUIZ_SPS_SLUG . '.php' ) ) {
			jsps_init_option_ms( $options );
		} else {
			jsps_update_option( $options );
		}
	}

	// Ready to start displaying options!
	if ( is_array( $options ) && is_array( $core_skins ) && is_array( $custom_skins ) ) {
		
		// Slug of theme activated.
		$current_skin = $options['juiz_sps_style'];
		$all_skins    = $core_skins + $custom_skins;

		foreach ( $all_skins as $slug => $skin ) {
			$skin_author = isset( $skin['author'] ) ? esc_html( $skin['author'] ) : '';
			$skin_author = isset( $skin['author_url'] ) ? '<a href="' . esc_url( $skin['author_url'] ) . '" target="_blank">' . $skin_author . '</a>' : $skin_author;
			$skin_name   = isset( $skin['name'] ) ? esc_html( $skin['name'] ) : __( "This skin doesn't have a name", 'juiz-social-post-sharer' );
			$skin_name   = isset( $skin['author'] ) ? sprintf( __( '%1$s by %2$s', 'juiz-social-post-sharer' ), $skin_name, $skin_author) : $skin_name;
			$demo_src     = isset( $skin['demo_url'] ) ? esc_url( $skin['demo_url'] ) : JUIZ_SPS_PLUGIN_URL . 'skins/' . $slug . '/demo.png';
			$demo_src_2x  = isset( $skin['demo_url_2x'] ) ? esc_url( $skin['demo_url_2x'] ) : null;

			// Print the skins.
			echo '<p class="juiz_sps_styles_options">
					<input id="jsps_style_' . esc_attr( $slug ) . '" value="' . esc_attr( $slug ) . '" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_style]" type="radio" ' . ( (string) $current_skin === (string) $slug ? ' checked="checked"' : '' ) . ' />
					<label for="jsps_style_' . esc_attr( $slug ) . '">
						<span class="juiz_sps_demo_styles">
							<img src="' . $demo_src . '"' . ( isset( $demo_src_2x ) ? ' srcset="' . $demo_src_2x . ' 2x"' : '' ) . '>
						</span>
						<span class="juiz_sps_style_name">' . $skin_name . '</span>
					</label>
				</p>';
		}
	}

	// Includes Promotion Banner.
	include_once('partials/promo-form.php');
	if ( function_exists( 'jsps_promo_form' ) ) {
		jsps_promo_form();
	}
}
}

// Checkboxes fields of networks.
if ( ! function_exists( 'juiz_sps_setting_checkbox_network_selection' ) ) {
function juiz_sps_setting_checkbox_network_selection() {
	$y = $n = '';
	$options = jsps_get_option();

	if ( is_array( $options ) ) {

		$all_networks = jsps_get_all_registered_networks();

		// Set the visibility value to the all_networks array from juiz_sps_networks option.
		foreach( $all_networks as $k => $v ) {
			if ( isset( $options['juiz_sps_networks'][ $k ] ) ) {
				// [ $k ][0] is retro compat from < 2.0.0
				$all_networks[ $k ]['visible'] = isset( $options['juiz_sps_networks'][ $k ]['visible'] ) ? $options['juiz_sps_networks'][ $k ]['visible'] : $options['juiz_sps_networks'][ $k ][0];
			}
			/*else {
				// Avoid activating new networks for existing users.
				// Or should it? Otherwise this option is useless for new buttons.
				$all_networks[ $k ]['visible'] = 0;
			}*/
		}

		$networks = jsps_get_displayable_networks( $all_networks, $options['juiz_sps_order'] );

		// Start the admin markup.
		echo '<div class="jsps-drag-container">
				<div id="jsps-draggable-networks">
					<div class="juiz-sps-squared-options">';

		foreach ( $networks as $k => $v ) {
			$icon         = jsps_get_network_html_icon($k, $v);
			$network_name = isset( $v['name'] ) ? $v['name'] : $k;
			$is_checked   = ( $v['visible'] == 1 ) ? ' checked="checked"' : '';
			$is_js_test   = ( $k == 'pinterest' ) ? ' <em>(' . __( 'uses JavaScript to work', 'juiz-social-post-sharer' ) . ')</em>' : '';

			echo '<p class="juiz_sps_options_p" data-network="' . esc_attr( $k ) . '">
					<input id="jsps_network_selection_' . esc_attr( $k ) . '" value="' . esc_attr( $k ) . '" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_networks][]" type="checkbox"
				' . $is_checked . ' />
			  		<label for="jsps_network_selection_' . esc_attr( $k ) . '"' . ( isset( $v['color'] ) ? ' style="--custom-color: ' . esc_attr( $v['color'] ) . '"' : '' ) . '>
			  			<span class="jsps_demo_icon">
			  				' . $icon . '
			  			</span>
			  			<span class="jsps_demo_name">' . esc_html( $network_name ) . '' . $is_js_test . '</span>
			  		</label>
			  		<input type="hidden" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_order][]" value=' . esc_attr( $k ) . '>
			  		<span class="juiz-sps-handle"></span>
			  	</p>';
		}

		echo '</div></div></div>';

		// List of coming network support.
		?>
		<div class="jsps-future-networks">
			<p><?php 
				printf( 
					__( '%sYou don’t see your favorite network here?%s I might be already working on it! You can see the list of incoming network support, or suggest new ones. If you are a developer, you can also build your own one. You can also see our %sroadmap%s.', 'juiz-social-post-sharer' ),
					'<strong>',
					'</strong><br>',
					'<a href="' . jsps_get_roadmap_url() . '">',
					'</a>'
				); ?></p>
			<p>
				<a href="#jsps-nw-list" class="jsps-show-networks button button-secondary">
					<i class="dashicons dashicons-arrow-down-alt2" aria-hidden="true"></i>&nbsp;<?php _e('See what’s coming', 'juiz-social-post-sharer'); ?>
				</a>
				<a class="button button-secondary" href="<?php echo jsps_get_issue(); ?>">
					<i class="dashicons dashicons-welcome-add-page" aria-hidden="true"></i>&nbsp;<?php _e('Suggest new one', 'juiz-social-post-sharer'); ?>
				</a>
				<a class="button button-secondary" href="<?php echo jsps_get_public_website( 'doc/tutorial-create-a-custom-button.html', array('source' => 'wp-plugin', 'medium' => 'settings', 'campaign' => 'create-your-own' ) ); ?>">
					<i class="dashicons dashicons-welcome-learn-more" aria-hidden="true"></i>&nbsp;<?php _e('Create your own', 'juiz-social-post-sharer'); ?>
				</a>
			</p>

			<ul id="jsps-nw-list" class="jsps-future-networks-list">
				<?php
					$coming_networks = array(
						'37' => 'App.net',
						'38' => 'Buffer',
						'35' => 'Instagram',
						'36' => 'Plurk',
						'39' => 'Telegram',
						'34' => 'Yummly'
					);

					foreach ($coming_networks as $issue => $name) {
				?>

					<li><a href="<?php echo jsps_get_issue( (int) $issue ); ?>" title="<?php echo esc_attr( __('Open corresponding issue', 'juiz-social-post-sharer') ) ?>" target="_blank"><?php echo $name; ?></a></li>

				<?php
					}
				?>
			</ul>
		</div>
		<?php
		// For AJAX notification.
		juiz_sps_get_notification_markup();

	}
}
}

// input for twitter username
if ( ! function_exists( 'juiz_sps_setting_input_twitter_user' ) ) {
function juiz_sps_setting_input_twitter_user() {
	$options = jsps_get_option();
	if ( is_array( $options ) ) {
		$username = isset( $options['juiz_sps_twitter_user'] ) ? $options['juiz_sps_twitter_user'] : '';
	echo '<p class="juiz_sps_options_p juiz_option_twitter_name">
			<input id="juiz_sps_twitter_user" value="' . esc_attr( $username ) . '" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_twitter_user]" type="text"> <em>(' . __( 'Username without "@"', 'juiz-social-post-sharer' ) . ')</em>
	  	</p>';
	}
}
}


// Advanced section text
if ( ! function_exists( 'juiz_sps_section_text_display' ) ) {
function juiz_sps_section_text_display() {
	echo '<p class="juiz_sps_section_intro">' . __( 'You can choose precisely the types of content that will benefit from the sharing buttons.', 'juiz-social-post-sharer' ) . '</p>';
}
}
// checkbox for type of content
if ( ! function_exists( 'juiz_sps_setting_checkbox_content_type' ) ) {
function juiz_sps_setting_checkbox_content_type() {
	$pts	= get_post_types( array( 'public'=> true, 'show_ui' => true, '_builtin' => true ) );
	$cpts	= get_post_types( array( 'public'=> true, 'show_ui' => true, '_builtin' => false ) );
	$options = jsps_get_option();
	$all_lists_icon = '<span class="dashicons-before dashicons-editor-ul"></span>';
	$all_lists_selected = '';

	if ( is_array( $options['juiz_sps_display_in_types'] ) ) {
		$all_lists_selected = in_array( 'all_lists', $options['juiz_sps_display_in_types'] ) ? 'checked="checked"' : '';
	}

	if ( is_array( $options ) && isset( $options['juiz_sps_display_in_types'] ) && is_array( $options['juiz_sps_display_in_types'] ) ) {
		
		global $wp_post_types;
		$no_icon = '<span class="jsps_no_icon">&#160;</span>';

		// classical post type listing
		foreach ( $pts as $pt ) {

			$selected = in_array( $pt, $options['juiz_sps_display_in_types'] ) ? 'checked="checked"' : '';
			$icon = '';

			switch( $wp_post_types[ $pt ]->name ) {
				case 'post' :
					$icon = 'dashicons-before dashicons-admin-post';
					break;
				case 'page' :
					$icon = 'dashicons-before dashicons-admin-page';
					break;
				case 'attachment' :
					$icon = 'dashicons-before dashicons-admin-media';
					break;
			}
			echo '<p class="juiz_sps_options_p"><input type="checkbox" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_display_in_types][]" id="' . $pt . '" value="' . $pt . '" ' . $selected . '> <label for="' . $pt . '"><span class="' . $icon . '"></span> ' . $wp_post_types[ $pt ]->label . '</label></p>';
		}

		// Custom post types listing
		if ( is_array( $cpts ) && ! empty( $cpts ) ) {
			foreach ( $cpts as $cpt ) {

				$selected = in_array( $cpt, $options['juiz_sps_display_in_types'] ) ? 'checked="checked"' : '';
				$icon = $no_icon;

				// image & dashicons support
				if ( isset( $wp_post_types[ $cpt ]->menu_icon ) && $wp_post_types[ $cpt ]->menu_icon ) {
					$icon = preg_match('#dashicons#', $wp_post_types[ $cpt ]->menu_icon) ?
							'<span class="dashicons ' . $wp_post_types[ $cpt ]->menu_icon . '"></span>'
							:
							'<img alt="&#8226;" src="' . esc_url( $wp_post_types[ $cpt ]->menu_icon ) . '" />';
				}

				echo '<p class="juiz_sps_options_p"><input type="checkbox" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_display_in_types][]" id="' . $cpt . '" value="' . $cpt . '" ' . $selected . '> <label for="' . $cpt . '">' . $icon . ' ' . $wp_post_types[ $cpt ]->label . '</label></p>';
			}
		}
	}
	echo '<p class="juiz_sps_options_p"><input type="checkbox" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_display_in_types][]" id="all_lists" value="all_lists" ' . $all_lists_selected . '> <label for="all_lists">' . $all_lists_icon . ' ' . sprintf( __( 'Lists of articles %s(blog, archives, search results, etc.)%s', 'juiz-social-post-sharer' ), '<em>', '</em>' ) . '</label></p>';
}
}

// where display buttons
// radio fields styles choice
if ( ! function_exists( 'juiz_sps_setting_radio_where' ) ) {
function juiz_sps_setting_radio_where() {

	$options = jsps_get_option();

	$w_bottom = $w_top = $w_both = $w_nowhere = '';
	if ( is_array( $options ) && isset( $options['juiz_sps_display_where'] ) )
		${'w_' . $options['juiz_sps_display_where']} = ' checked="checked"';
	
	echo '<p class="juiz_sps_options_p">
			<input id="jsps_w_b" value="bottom" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_display_where]" type="radio" ' . $w_bottom . ' />
			<label for="jsps_w_b">' . __( 'Content bottom', 'juiz-social-post-sharer' ) . '</label>
		</p>
		<p class="juiz_sps_options_p">
			<input id="jsps_w_t" value="top" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_display_where]" type="radio" ' . $w_top . ' />
			<label for="jsps_w_t">'. __( 'Content top', 'juiz-social-post-sharer' ) . '</label>
		</p>
		<p class="juiz_sps_options_p">	
			<input id="jsps_w_2" value="both" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_display_where]" type="radio" ' . $w_both . ' />
			<label for="jsps_w_2">' . __( 'Both', 'juiz-social-post-sharer' ) . '</label>
		</p>
		<p class="juiz_sps_options_p">
			<input id="jsps_w_0" value="nowhere" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_display_where]" type="radio" ' . $w_nowhere . ' />
			<label for="jsps_w_0">' . __( "I'm a ninja, I want to use the shortcode only!", 'juiz-social-post-sharer') . '</label>
		</p>'; // nowhere new in 1.2.2
}
}



// Advanced section text
if ( ! function_exists( 'juiz_sps_section_text_advanced' ) ) {
function juiz_sps_section_text_advanced() {
	echo '<p class="juiz_sps_section_intro">' . __( 'Modify advanced settings of the plugin. Some of them needs JavaScript (only one file loaded)', 'juiz-social-post-sharer' ) . '</p>';
}
}


// radio fields Y or N for hide text
if ( ! function_exists( 'juiz_sps_setting_radio_hide_social_name' ) ) {
function juiz_sps_setting_radio_hide_social_name() {
	$y = $n = '';
	$options = jsps_get_option();

	if ( is_array( $options ) ) {
		( isset( $options['juiz_sps_hide_social_name'] ) && $options['juiz_sps_hide_social_name'] == 1 ) ? $y = ' checked="checked"' : $n = ' checked="checked"';
		$core_skins   = jsps_get_core_skins();
		$custom_skins = jsps_get_custom_skins();
		$all_skins    = $core_skins + $custom_skins;

		echo '<img class="jsps-gif-demo" src="' . JUIZ_SPS_PLUGIN_ASSETS . 'img/jsps-icon-only.gif" width="350" height="60">';

		if ( isset( $all_skins[ $options['juiz_sps_style'] ]['support_hidden_name'] ) && $all_skins[ $options['juiz_sps_style'] ]['support_hidden_name'] === false ) {
			echo jsps_get_notif(
				'is-error',
				__( 'This buttons skin tells there is no support for hidden name.', 'juiz-social-post-sharer' )
			);
		} elseif ( isset( $all_skins[ $options['juiz_sps_style'] ]['support_hidden_name'] ) && $all_skins[ $options['juiz_sps_style'] ]['support_hidden_name'] === true ) {
			echo jsps_get_notif(
				'is-success',
				__( 'This buttons skin tells there is a support of hidden name.', 'juiz-social-post-sharer' )
			);
		} else {
			echo jsps_get_notif(
				'',
				__( 'We can\'t tell if the button skin supports the hidden name option.', 'juiz-social-post-sharer' )
			);
		}

		echo '<input id="jsps_hide_name_y" value="1" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_hide_social_name]" type="radio" ' . $y . ' />
			<label for="jsps_hide_name_y">' . __( 'Yes', 'juiz-social-post-sharer' ) . '</label>
				
			<input id="jsps_hide_name_n" value="0" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_hide_social_name]" type="radio" ' . $n . ' />
			<label for="jsps_hide_name_n">' . __( 'No', 'juiz-social-post-sharer' ) . '</label>';
	}
}
}

// Radio fields Y or N for compact display
if ( ! function_exists( 'juiz_sps_setting_radio_compact_display' ) ) {
function juiz_sps_setting_radio_compact_display() {
	$y = $n = '';
	$options = jsps_get_option();

	if ( is_array( $options ) )
		$core_skins   = jsps_get_core_skins();
		$custom_skins = jsps_get_custom_skins();
		$all_skins    = $core_skins + $custom_skins;

		if ( isset( $all_skins[ $options['juiz_sps_style'] ]['support_compact'] ) && $all_skins[ $options['juiz_sps_style'] ]['support_compact'] === false ) {
			echo jsps_get_notif(
				'is-error',
				__( 'This buttons skin tells there is no support for compact display.', 'juiz-social-post-sharer' )
			);
		} elseif ( isset( $all_skins[ $options['juiz_sps_style'] ]['support_compact'] ) && $all_skins[ $options['juiz_sps_style'] ]['support_compact'] === true ) {
			echo jsps_get_notif(
				'is-success',
				__( 'This buttons skin tells there is a support of compact display.', 'juiz-social-post-sharer' )
			);
		} else {
			echo jsps_get_notif(
				'',
				__( 'We can\'t tell if the button skin supports the compact display.', 'juiz-social-post-sharer' )
			);
		}

		( isset( $options['juiz_sps_compact_display'] ) && $options['juiz_sps_compact_display'] == 1 ) ? $y = ' checked="checked"' : $n = ' checked="checked"';
	
	echo '<input id="jsps_compact_display_y" value="1" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_compact_display]" type="radio" ' . $y . ' />
		<label for="jsps_compact_display_y">' . __( 'Yes', 'juiz-social-post-sharer' ) . '</label>
			
		<input id="jsps_compact_display_n" value="0" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_compact_display]" type="radio" ' . $n . ' />
		<label for="jsps_compact_display_n">' . __( 'No', 'juiz-social-post-sharer' ) . '</label>';
}
}

// radio fields Y or N for target _blank
if ( ! function_exists( 'juiz_sps_setting_radio_target_link' ) ) {
function juiz_sps_setting_radio_target_link() {
	$y = $n = '';
	$options = jsps_get_option();

	if ( is_array( $options ) )
		( isset( $options['juiz_sps_target_link'] ) && $options['juiz_sps_target_link'] == 1 ) ? $y = ' checked="checked"' : $n = ' checked="checked"';
	
	echo '<input id="jsps_target_link_y" value="1" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_target_link]" type="radio" ' . $y . ' />
		<label for="jsps_target_link_y">' . __( 'Yes', 'juiz-social-post-sharer' ) . '</label>
			
		<input id="jsps_target_link_n" value="0" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_target_link]" type="radio" ' . $n . ' />
		<label for="jsps_target_link_n">' .  __( 'No', 'juiz-social-post-sharer' ) . '</label>';
}
}

// radio fields Y or N for pinterest sniffing
if ( ! function_exists( 'juiz_sps_setting_radio_force_snif' ) ) {
function juiz_sps_setting_radio_force_snif() {
	$y = $n = '';
	$options = jsps_get_option();

	if ( is_array( $options ) )
		( isset( $options['juiz_sps_force_pinterest_snif'] ) && $options['juiz_sps_force_pinterest_snif'] == 1 ) ? $y = ' checked="checked"' : $n = ' checked="checked"';
	
	echo '	<input id="jsps_forcer_snif_y" value="1" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_force_pinterest_snif]" type="radio" ' . $y . ' />
			<label for="jsps_forcer_snif_y">' . __( 'Yes', 'juiz-social-post-sharer' ) . '</label>
			
			<input id="jsps_forcer_snif_n" value="0" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_force_pinterest_snif]" type="radio" ' . $n . ' />
			<label for="jsps_forcer_snif_n">' . __( 'No', 'juiz-social-post-sharer' ) . '</label>';
}
}

// radio fields Y or N for counter
if ( ! function_exists( 'juiz_sps_setting_radio_counter' ) ) {
function juiz_sps_setting_radio_counter() {

	$y = $n = '';
	$options = jsps_get_option();

	( isset( $options['juiz_sps_counter'] ) && $options['juiz_sps_counter'] == 1 ) ? $y = ' checked="checked"' : $n = ' checked="checked"';
	
	echo jsps_get_notif(
		'is-info',
		sprintf( __( 'Counters are internal tool for your website. Most social networks don’t allow developers to count real URL sharing nowadays. %sMore info%s', 'juiz-social-post-sharer' ), '<a href="' . jsps_get_public_website( 'counters-are-fake.html', array('source' => 'wp-plugin', 'medium' => 'settings', 'campaign' => 'counter-option' ) ) . '">', '</a>' )
	);

	echo '<p>
			<input id="jsps_counter_y" value="1" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_counter]" type="radio" ' . $y . ' />
			<label for="jsps_counter_y">'. __('Yes', 'juiz-social-post-sharer') . '</label>
			
			<input id="jsps_counter_n" value="0" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_counter]" type="radio" ' . $n . ' />
			<label for="jsps_counter_n">' . __( 'No', 'juiz-social-post-sharer' ) . '</label>
		</p>';
}
}

// radio fields for what to display as counter
if ( ! function_exists( 'juiz_sps_setting_radio_counter_option' ) ) {
function juiz_sps_setting_radio_counter_option() {

	$options = jsps_get_option();
	if ( is_array( $options ) ) {
		$both 		= ( isset( $options['juiz_sps_counter_option'] ) && $options['juiz_sps_counter_option'] == 'both' ) ? ' checked="checked"' : '';
		$total 		= ( isset( $options['juiz_sps_counter_option'] ) && $options['juiz_sps_counter_option'] == 'total' ) ? ' checked="checked"' : '';
		$subtotal 	= ( isset( $options['juiz_sps_counter_option'] ) && $options['juiz_sps_counter_option'] == 'subtotal' ) ? ' checked="checked"' : '';

		if ( $both == '' && $total == '' && $subtotal == '' ) {
			$both = 'checked="checked"';
		}
	}
	
	echo '	<input id="jsps_counter_both" value="both" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_counter_option]" type="radio" ' . $both . ' />
			<label for="jsps_counter_both">' . __( 'Total &amp; Sub-totals', 'juiz-social-post-sharer' ) . '</label>
			
			<input id="jsps_counter_total" value="total" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_counter_option]" type="radio" ' . $total . ' />
			<label for="jsps_counter_total">' . __( 'Only Total', 'juiz-social-post-sharer' ) . '</label>

			<input id="jsps_counter_subtotal" value="subtotal" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_counter_option]" type="radio" ' . $subtotal . ' />
			<label for="jsps_counter_subtotal">' . __( 'Only Sub-totals', 'juiz-social-post-sharer' ) . '</label>';
}
}

// radio fields for deactivating CSS loading.
if ( ! function_exists( 'juiz_sps_setting_radio_use_css' ) ) {
function juiz_sps_setting_radio_use_css() {

	$options = jsps_get_option();
	if ( is_array( $options ) ) {
		$nope 		 = ( isset( $options['juiz_sps_css_files'] ) && $options['juiz_sps_css_files'] == 'nope' ) ? ' checked="checked"' : '';
		$both 		 = ( isset( $options['juiz_sps_css_files'] ) && $options['juiz_sps_css_files'] == 'both' ) ? ' checked="checked"' : '';
		$modal_css 	 = ( isset( $options['juiz_sps_css_files'] ) && $options['juiz_sps_css_files'] == 'modal' ) ? ' checked="checked"' : '';
		$buttons_css = ( isset( $options['juiz_sps_css_files'] ) && $options['juiz_sps_css_files'] == 'buttons' ) ? ' checked="checked"' : '';

		if ( $nope == '' && $both == '' && $modal_css == '' && $buttons_css == '' ) {
			$nope = 'checked="checked"';
		}
	}
	
	echo '<input id="jsps_css_file_nope" value="nope" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_css_files]" type="radio" ' . $nope . ' />
			<label for="jsps_css_file_nope">' . __( 'Nope, load the plugin’s', 'juiz-social-post-sharer' ) . '</label>
			
			<input id="jsps_css_file_modal" value="modal" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_css_files]" type="radio" ' . $modal_css . ' />
			<label for="jsps_css_file_modal">' . __( 'for the modal', 'juiz-social-post-sharer' ) . '</label>

			<input id="jsps_css_file_buttons" value="buttons" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_css_files]" type="radio" ' . $buttons_css . ' />
			<label for="jsps_css_file_buttons">' . __( 'for the buttons', 'juiz-social-post-sharer' ) . '</label>

			<input id="jsps_css_file_both" value="both" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_css_files]" type="radio" ' . $both . ' />
			<label for="jsps_css_file_both">' . __( 'for both (Ninja!)', 'juiz-social-post-sharer' ) . '</label>';
}
}

// Mail section text
if ( ! function_exists( 'juiz_sps_section_text_mail' ) ) {
function juiz_sps_section_text_mail() {
	echo '<p class="juiz_sps_section_intro">' . __( 'You can customize texts to display when visitors share your content by mail button', 'juiz-social-post-sharer' ) . '.<br>' . sprintf( __( 'To perform customization, you can use %s%%%%title%%%%%s, %s%%%%siteurl%%%%%s or %s%%%%permalink%%%%%s variables.', 'juiz-social-post-sharer' ), '<code>', '</code>', '<code>', '</code>', '<code>', '</code>' ) . '</p>';
}
}

if ( ! function_exists( 'juiz_sps_setting_input_mail_subject' ) ) {
function juiz_sps_setting_input_mail_subject() {
	$options = jsps_get_option();
	if ( isset( $options['juiz_sps_mail_subject'] ) ) {
		echo '<input id="juiz_sps_mail_subject" value="' . esc_attr( $options['juiz_sps_mail_subject'] ) . '" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_mail_subject]" type="text">';
	}
}
}

if ( ! function_exists( 'juiz_sps_setting_textarea_mail_body' ) ) {
function juiz_sps_setting_textarea_mail_body() {
	$options = jsps_get_option();
	if ( isset( $options['juiz_sps_mail_body'] ) ) {
		echo '<textarea id="juiz_sps_mail_body" name="' . JUIZ_SPS_SETTING_NAME . '[juiz_sps_mail_body]" cols="55" rows="7">' . esc_textarea( $options['juiz_sps_mail_body'] ) . '</textarea>';
	}
}
}

if ( ! function_exists( 'juiz_sps_section_text_whats_new' ) ) {
function juiz_sps_section_text_whats_new() {
	echo '<p class="juiz_sps_section_intro">' . __( 'Discover what is fresh in your favorite Social Sharing plugin to keep track of updates.', 'juiz-social-post-sharer' ) . '.<br>' . sprintf( __( 'You can also display the %sWelcome Page%s to get a quick tour of the features.', 'juiz-social-post-sharer' ), '<a href="' . jsps_get_welcome_url() . '">', '</a>') . '</p>';
}
}

if ( ! function_exists( 'juiz_sps_setting_changelog' ) ) {
function juiz_sps_setting_changelog() {

	$output = '';

	// Use transient saved to be more performant.
	if ( $output = get_transient( JUIZ_SPS_SLUG . '-changelog' ) ) {
		echo $output;
		return;
	}

	$readme = file_get_contents( dirname( JUIZ_SPS_FILE ) . '/readme.txt');
	
	if ( preg_match( '#(.*)(==\s?Changelog\s?==)(.*)#ms', $readme, $matches ) ) {

		$output .= '<div class="juiz_sps_changelog">';
		$output .= '<h3 class="screen-reader-text">' . __( 'Changelog', 'juiz-social-post-sharer' ) . '</h3>';

		//$changelog = make_clickable( esc_html( $matches[3] ) );
		$changelog = $matches[3];

		// Some preg_replace for formatting.
		$changelog = preg_replace( '/`(.*?)`/', '<code>$1</code>', $changelog );
		$changelog = preg_replace( '/\*\*(.*?)\*\*/', '<strong>$1</strong>', $changelog);;

		// Titles
		$changelog = preg_replace( '/= (.*?) =/', '<h4>$1</h4>', $changelog );

		$sections = preg_split( '#<h4>(.*?)</h4>#', trim( $changelog ), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );

		for ($i = 0; $i < count( $sections ); $i++) {
			if ( $i % 2 ) {
				// Version changes.
				$reg_changes = '/(?:([\n]^\*[\S ]*){1}((?:[\n][\s]{1}\*[\S ]*)*))/m';
				if ( preg_match_all( $reg_changes, $sections[$i], $matches, PREG_SET_ORDER, 0 ) ) {

					$output .= '<ul class="juiz-changelog-list juiz-d1">';

					foreach($matches as $match) {
						// $match[0] - the whole block, subitem included
						// $match[1] - the item depth-1
						// $match[2] - the items in one string depth-2 (to be split)
						$thistring = preg_replace( '#\[(.*)\]\((([a-zA-Z0-9\.\:\/\-\_\#\?\=]*)(?: ?(\".*\"))?)?\)#', '<a href="$3" title=$4>$1</a>', substr( $match[1], 2 ) );
									$thistring = preg_replace( '#\(?@see ((?:https?:\/\/)?(?:[^@\n]+@)?(?:www\.)?([^:\/\n?]+)[\S]*)\)#', '(@see <a href="$1">$2</a>)', $thistring );
						$output .= '<li class="juiz-changelog-list-item juiz-d1">' . $thistring;

						if ( isset( $match[2] ) && ! empty( $match[2] ) ) {

							$output .= '<ul class="juiz-changelog-list juiz-d2">';

							$subitems = preg_split('#\s\*\s(.*)#', $match[2], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );

							for ($j = 0; $j < count( $subitems ); $j++) {
								if ( $j % 2 ) {
									$thisub = preg_replace( '#\[(.*)\]\((([a-zA-Z0-9\.\:\/\-\_\#\?\=]*)(?: ?(\".*\"))?)?\)#', '<a href="$3" title=$4>$1</a>', $subitems[$j] );
									$thisub = preg_replace( '#\(?@see ((?:https?:\/\/)?(?:[^@\n]+@)?(?:www\.)?([^:\/\n?]+)[\S]*)\)#', '(@see <a href="$1">$2</a>)', $thisub );
									$output .= '<li class="juiz-changelog-list-item juiz-d2">' . $thisub . '</li>';
								}
							}

							$output .= '</ul><!-- depth 2 -->';
						}

						$output .= '</li>';
					}

					$output .= '</ul><!-- depth 1 -->';
				}
			} else {
				// Version number.
				$output .= '<h4 class="juiz-changelog-version-number">' . $sections[$i] . '</h4>';
			}
		}

		$output .= '</div>';

	}

	echo $output;

	// Save this heavy processing code for later use.
	set_transient( JUIZ_SPS_SLUG . '-changelog', $output, 60*60*24*2 );
}
}

if ( ! function_exists( 'juiz_sps_section_text_skin_shop' ) ) {
function juiz_sps_section_text_skin_shop() {
	echo '<p class="juiz_sps_section_intro" id="jsps-skin-shop-intro">' . __( 'I’m working on a Skin Shop to give you the most premium skin ever made with tone of choice: free skins, paid skins, new skins, colorful skins or more neutral, animated or static… just for every taste.', 'juiz-social-post-sharer' ) . '</p>';
}
}

if ( ! function_exists( 'juiz_sps_setting_skin_shop' ) ) {
function juiz_sps_setting_skin_shop() {
	if ( function_exists( 'jsps_promo_form' ) ) {
		jsps_promo_form();
	}

	echo '<section class="jsps-skins-list">
		<h1 class="jsps-h3-like">' . esc_html__('Skin available soon', 'juiz-social-post-sharer') . '</h1>
		<p class="juiz_sps_section_intro">' . esc_html__( 'The last finest skins directly in your WordPress Admin!', 'juiz-social-post-sharer') . '</p>

		<noscript>
			<p class="jsps-no-script-info">' . esc_html__( 'Sorry, for performance issue, this listing needs JavaScript activated.', 'juiz-social-post-sharer') . '</p>
			<p>' . sprintf( __( 'You can still visit the %sofficial website%s for further information.', 'juiz-social-post-sharer' ), '<a href="' . jsps_get_public_website( '', array('source' => 'wp-plugin', 'medium' => 'settings', 'campaign' => 'no-js' ) ) . '">', '</a>' ) . '</p>	
		</noscript>

		<div id="jsps-skin-list-drop" aria-live="polite">
			<span class="jsps-spinner"></span>
		</div>
	</section>';
}
}

// The page layout/form
if ( ! function_exists( 'juiz_sps_settings_page' ) ) {
	function juiz_sps_settings_page() {
	?>

	<div id="juiz-sps" class="wrap">
		<div class="jsps-main-content">
			<div class="jsps-main-header">
				<h1>
					<img src="<?php echo JUIZ_SPS_PLUGIN_ASSETS . 'admin/nobs-logo-light.svg' ?>" width="249" height="48" alt="Nobs • Share Buttons">
					<small>v.&nbsp;<?php echo JUIZ_SPS_VERSION; ?></small>
				</h1>
			</div>

			<div class="jsps-main-body">
				<?php if ( isset( $_GET['message'] ) && $_GET['message'] = '1337' ) { ?>
				<div class="error settings-error">
					<p>
						<strong><?php echo sprintf( __( 'You must chose at least one %stype of content%s.', 'juiz-social-post-sharer' ), '<a href="#post">', '</a>' ); ?></strong><br>
						<em><?php _e( 'Is you don\'t want to use this plugin more longer, go to Plugins section and deactivate it.', 'juiz-social-post-sharer' ); ?></em></p>
				</div>
				<?php }

				// Put the form of Mailchimp here to avoid form > form
				// Hoping the form attribute is working.
				?>
				<form action="https://gmail.us10.list-manage.com/subscribe/post?u=5339b8dfa2b000a82251effc3&amp;id=c9c0f762f1" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate></form>

				<form method="post" action="options.php">
					<?php
						settings_fields( JUIZ_SPS_SETTING_NAME );
						do_settings_sections( JUIZ_SPS_SLUG );
					?>
				</form>

				<p class="jsps_info">
					<?php echo sprintf( __( 'You can use %s[juiz_sps]%s or %s[juiz_social]%s shortcode with an optional attribute "buttons" listing the social networks you want.', 'juiz-social-post-sharer' ), '<code>','</code>', '<code>','</code>' ); ?>
					<br />
					<?php _e( 'Example with all the networks available:', 'juiz-social-post-sharer'); ?>

					<?php
					$networks = jsps_get_all_registered_networks();
					$nws = '';
					
					foreach( $networks as $k => $v ) {
						$nws .= $k . ', ';
					}
					?>
					<code>[juiz_sps buttons="<?php echo trim( $nws, ', ' ); ?>"]</code>
				</p>
			</div><!-- .jsps-main-body -->
		</div><!-- .jsps-main-content -->

		<div class="juiz_bottom_links">
			<div class="juiz_bottom_links_container">
				<div class="jsps-links-header">
					<p><i class="dashicons dashicons-format-status" aria-hidden="true"></i>&nbsp;<?php esc_html_e( 'Thanks!', 'juiz-social-post-sharer' ); ?></p>
				</div>
				<p class="juiz_bottom_links_p">
					<em class="jsps-emphasis"><?php _e( 'Like it? Support this plugin! Thank you 😊', 'juiz-social-post-sharer' ); ?></em>
				</p>

				<div class="juiz_btns_set">
					<a class="juiz_btn_link juiz_paypal" target="_blank" href="<?php echo jsps_get_paypal_url(); ?>" aria-label="<?php printf( __( 'Donate with %s', 'juiz-social-post-sharer' ), 'Paypal' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Paypal" x="0px" y="0px" viewBox="0 0 16 16" xml:space="preserve"><path d="M5.444 9.488a.714.714 0 0 1 .707-.6h1.47c2.889 0 5.15-1.164 5.811-4.531.02-.1.051-.292.051-.292.188-1.246-.001-2.091-.68-2.858C12.057.362 10.707 0 8.98 0H3.968a.717.717 0 0 0-.709.6L1.172 13.731a.428.428 0 0 0 .425.493h3.094l.777-4.89-.024.154z" fill="currentColor"></path><path d="M7.621 9.772H6.303L5.319 16h2.138a.626.626 0 0 0 .62-.526l.025-.132.492-3.091.032-.17a.626.626 0 0 1 .619-.526h.391c2.527 0 4.505-1.018 5.083-3.963.232-1.182.12-2.173-.453-2.889-.718 3.315-3.009 5.069-6.645 5.069z" fill="currentColor"></path></svg><?php _e( 'Paypal', 'juiz-social-post-sharer' ); ?>
					</a>

					<a class="juiz_btn_link juiz_flattr" target="_blank" href="https://flattr.com/@geoffreycrofte" lang="en" hreflang="en" aria-label="<?php printf( __( 'Donate with %s', 'juiz-social-post-sharer' ), 'Flattr' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Flattr" x="0px" y="0px" viewBox="0 0 20 20" xml:space="preserve"><path d="M5.598 8.541c0-1.637.434-2.678 1.889-2.912.508-.1 1.566-.064 2.239-.064v2.5c0 .024.003.064.009.084a.236.236 0 0 0 .228.175c.061 0 .118-.031.178-.09L16.377 2H7.548C3.874 2 2 4.115 2 8.066v8.287l3.598-3.602v-4.21zM14.4 7.248v4.209c0 1.637-.434 2.68-1.889 2.912-.508.1-1.566.065-2.238.065v-2.5a.48.48 0 0 0-.009-.084.242.242 0 0 0-.228-.176c-.062 0-.118.033-.179.092l-6.235 6.232L7.809 18h4.643C16.125 18 18 15.885 18 11.934V3.647l-3.6 3.601z" fill="currentColor"></path></svg><?php _e( 'Flattr', 'juiz-social-post-sharer' ); ?>
					</a>

					<a class="juiz_btn_link juiz_bmc juiz_full_width" target="_blank" href="https://www.buymeacoffee.com/geoffreycrofte" lang="en" hreflang="en" aria-label="<?php printf( __( 'Donate with %s', 'juiz-social-post-sharer' ), 'Buy Me a Coffee' ); ?>">
						<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M32.5073 11.4925L32.3062 10.4779C32.1257 9.56757 31.716 8.70743 30.7816 8.37838C30.482 8.27315 30.1422 8.22791 29.9126 8.01005C29.6829 7.79219 29.615 7.45381 29.5619 7.14006C29.4635 6.56416 29.3711 5.98779 29.2703 5.41286C29.1832 4.9186 29.1144 4.36337 28.8877 3.90993C28.5926 3.30108 27.9803 2.94502 27.3715 2.70945C27.0595 2.59299 26.7411 2.49447 26.4179 2.41437C24.8967 2.01307 23.2974 1.86553 21.7325 1.78143C19.8542 1.67778 17.9709 1.70901 16.097 1.87487C14.7023 2.00175 13.2333 2.1552 11.9079 2.63765C11.4235 2.8142 10.9243 3.02617 10.5559 3.40043C10.104 3.86025 9.95642 4.57139 10.2864 5.14483C10.521 5.55204 10.9184 5.83972 11.3398 6.03007C11.8888 6.27531 12.4621 6.4619 13.0503 6.58678C14.688 6.94875 16.3842 7.09089 18.0573 7.15135C19.9117 7.22618 21.7691 7.16556 23.6146 6.96989C24.071 6.91972 24.5266 6.85958 24.9813 6.78939C25.5169 6.70727 25.8607 6.00697 25.7028 5.51909C25.5139 4.93581 25.0064 4.70959 24.4325 4.79762C24.3479 4.8109 24.2638 4.82319 24.1792 4.83549L24.1182 4.84434C23.9238 4.86893 23.7294 4.89188 23.5349 4.91319C23.1333 4.95647 22.7307 4.99188 22.3271 5.01942C21.4231 5.08237 20.5168 5.11139 19.6109 5.11286C18.7207 5.11286 17.8301 5.08778 16.9419 5.02926C16.5367 5.0027 16.1324 4.96893 15.7291 4.92795C15.5457 4.90877 15.3628 4.8886 15.1798 4.86598L15.0057 4.84385L14.9679 4.83844L14.7874 4.81237C14.4185 4.7568 14.0497 4.69287 13.6847 4.61566C13.6479 4.60749 13.615 4.587 13.5914 4.55758C13.5678 4.52816 13.5549 4.49157 13.5549 4.45386C13.5549 4.41613 13.5678 4.37955 13.5914 4.35013C13.615 4.32071 13.6479 4.30022 13.6847 4.29205H13.6916C14.0079 4.22467 14.3265 4.16714 14.6462 4.11698C14.7528 4.10025 14.8596 4.08386 14.9669 4.06779H14.9698C15.17 4.05452 15.3711 4.01861 15.5703 3.99501C17.3032 3.81475 19.0465 3.7533 20.7878 3.81108C21.6332 3.83567 22.4781 3.88534 23.3195 3.97091C23.5005 3.9896 23.6805 4.00927 23.8605 4.0314C23.9294 4.03976 23.9987 4.0496 24.068 4.05796L24.2077 4.07812C24.6149 4.13878 25.02 4.21238 25.4229 4.29894C26.02 4.42877 26.7867 4.47107 27.0523 5.12516C27.1369 5.33269 27.1752 5.56335 27.2219 5.78122L27.2814 6.0591C27.283 6.06406 27.2842 6.06915 27.2849 6.07431C27.4255 6.73005 27.5664 7.38578 27.7074 8.04152C27.7177 8.08997 27.7179 8.14002 27.7081 8.18855C27.6982 8.23708 27.6784 8.28308 27.65 8.32364C27.6216 8.3642 27.5851 8.39848 27.5428 8.4243C27.5006 8.45013 27.4535 8.46699 27.4044 8.47379H27.4004L27.3144 8.4856L27.2293 8.49694C26.9598 8.53201 26.69 8.5648 26.4198 8.59527C25.8877 8.65593 25.3548 8.70839 24.821 8.75267C23.7604 8.84084 22.6976 8.8987 21.6327 8.92624C21.0901 8.9407 20.5476 8.94739 20.0053 8.94642C17.8468 8.9447 15.6902 8.81926 13.546 8.57069C13.3139 8.54314 13.0818 8.51363 12.8497 8.48364C13.0297 8.50675 12.7188 8.46594 12.6559 8.4571C12.5084 8.43643 12.3608 8.41493 12.2133 8.39267C11.718 8.3184 11.2258 8.22691 10.7315 8.14675C10.134 8.04841 9.56249 8.09758 9.02201 8.39267C8.57835 8.63543 8.21927 9.00773 7.99268 9.45986C7.75957 9.94182 7.69022 10.4665 7.58596 10.9844C7.4817 11.5023 7.31941 12.0595 7.38088 12.5911C7.51318 13.7385 8.3153 14.6709 9.46905 14.8795C10.5544 15.0762 11.6457 15.2355 12.74 15.3713C17.0384 15.8977 21.381 15.9607 25.6929 15.5591C26.0441 15.5263 26.3947 15.4906 26.7449 15.4519C26.8543 15.4399 26.9649 15.4525 27.0688 15.4888C27.1727 15.5251 27.2671 15.5841 27.3452 15.6617C27.4233 15.7392 27.483 15.8332 27.5201 15.9368C27.5571 16.0404 27.5705 16.151 27.5593 16.2604L27.4501 17.3217C27.2302 19.4663 27.0101 21.6107 26.7902 23.7549C26.5606 26.0067 26.3297 28.2583 26.0972 30.5097C26.0316 31.1438 25.9661 31.7778 25.9005 32.4115C25.8375 33.0356 25.8287 33.6794 25.7102 34.2961C25.5233 35.2659 24.8667 35.8615 23.9087 36.0794C23.031 36.2791 22.1344 36.3839 21.2343 36.3922C20.2364 36.3976 19.2391 36.3533 18.2412 36.3587C17.176 36.3646 15.8713 36.2662 15.049 35.4735C14.3265 34.7771 14.2267 33.6868 14.1283 32.744C13.9972 31.4958 13.8672 30.2478 13.7383 28.9999L13.0154 22.0612L12.5477 17.5716C12.5398 17.4973 12.532 17.424 12.5246 17.3493C12.4685 16.8137 12.0893 16.2894 11.4918 16.3165C10.9803 16.3391 10.399 16.7739 10.459 17.3493L10.8058 20.6778L11.5228 27.5629C11.7271 29.5186 11.9308 31.4747 12.1341 33.431C12.1735 33.8058 12.2103 34.1815 12.2517 34.5562C12.4764 36.6041 14.0403 37.7077 15.977 38.0185C17.1082 38.2005 18.2668 38.2378 19.4147 38.2565C20.8861 38.2802 22.3723 38.3367 23.8197 38.0701C25.9644 37.6767 27.5736 36.2446 27.8032 34.0231C27.8688 33.3819 27.9344 32.7404 28 32.0987C28.218 29.9768 28.4357 27.8547 28.6531 25.7325L29.3642 18.7981L29.6903 15.6201C29.7065 15.4625 29.7731 15.3144 29.88 15.1975C29.9871 15.0807 30.1288 15.0014 30.2844 14.9714C30.8976 14.8519 31.4838 14.6478 31.9201 14.1811C32.6145 13.438 32.7527 12.4691 32.5073 11.4925ZM9.43758 12.178C9.44692 12.1736 9.42971 12.2538 9.42233 12.2911C9.42086 12.2346 9.42381 12.1844 9.43758 12.178ZM9.49709 12.6384C9.50201 12.6349 9.51676 12.6546 9.532 12.6782C9.50889 12.6565 9.49414 12.6403 9.4966 12.6384H9.49709ZM9.55561 12.7156C9.57676 12.7515 9.58807 12.7741 9.55561 12.7156V12.7156ZM9.67315 12.811H9.6761C9.6761 12.8144 9.68151 12.8179 9.68347 12.8213C9.68022 12.8175 9.67659 12.8141 9.67266 12.811H9.67315ZM30.2558 12.6683C30.0355 12.8778 29.7035 12.9752 29.3755 13.0239C25.6969 13.5698 21.9646 13.8462 18.2456 13.7242C15.5841 13.6332 12.9505 13.3377 10.3154 12.9654C10.0572 12.929 9.77741 12.8818 9.59987 12.6915C9.26545 12.3325 9.42971 11.6095 9.51676 11.1757C9.59643 10.7784 9.74888 10.2487 10.2215 10.1922C10.9592 10.1056 11.8159 10.4169 12.5457 10.5276C13.4244 10.6617 14.3064 10.769 15.1916 10.8497C18.9696 11.1939 22.811 11.1403 26.5723 10.6367C27.2578 10.5446 27.9409 10.4376 28.6216 10.3156C29.228 10.2069 29.9003 10.0028 30.2667 10.6308C30.518 11.0587 30.5514 11.6311 30.5126 12.1146C30.5006 12.3252 30.4086 12.5233 30.2553 12.6683H30.2558Z" fill="currentColor"/></svg>
	<?php _e( 'Buy me a Coffee', 'juiz-social-post-sharer' ); ?>
					</a>
					
					<a class="juiz_btn_link juiz_twitter" target="_blank" href="https://twitter.com/intent/tweet?source=webclient&amp;hastags=WordPress,Plugin&amp;text=Juiz%20Social%20Post%20Sharer%20is%20an%20awesome%20WordPress%20plugin%20to%20share%20content!%20Try%20it!&amp;url=http://wordpress.org/extend/plugins/juiz-social-post-sharer/&amp;related=geoffrey_crofte&amp;via=geoffrey_crofte"><i class="dashicons dashicons-twitter" aria-hidden="true"></i>&nbsp;<?php _e( 'Tweet it', 'juiz-social-post-sharer' ); ?></a>

					<a class="juiz_btn_link juiz_rate" target="_blank" href="https://wordpress.org/support/plugin/juiz-social-post-sharer/reviews/?rate=5#new-post"><i class="dashicons dashicons-star-filled" aria-hidden="true"></i>&nbsp;<?php _e( 'Rate it', 'juiz-social-post-sharer' ); ?></a>

					<a class="juiz_btn_link juiz_translation juiz_full_width" target="_blank" href="https://translate.wordpress.org/projects/wp-plugins/juiz-social-post-sharer/"><i class="dashicons dashicons-translation" aria-hidden="true"></i>&nbsp;<?php _e( 'Translate it', 'juiz-social-post-sharer' ); ?></a>
				</div>
				
				<p class="juiz_bottom_links_p">
					<em class="jsps-emphasis"><?php _e( 'Want to customize everything? Look at the documentation.', 'juiz-social-post-sharer' ); ?></em>

					<a class="juiz_btn_link juiz_doc juiz_full_width" target="_blank" href="<?php echo jsps_get_public_website( 'doc/', array('source' => 'wp-plugin', 'medium' => 'sidebar', 'campaign' => 'settings' ) ); ?>"><i class="dashicons dashicons-welcome-learn-more" aria-hidden="true"></i>&nbsp;<?php esc_html_e( 'Documentation', 'juiz-social-post-sharer' ); ?></a>
				</p>
			</div><!-- . juiz_bottom_links_container -->
		</div><!-- . juiz_bottom_links -->

		<div class="nobs-modal-container" tabindex="-1" role="modal" aria-hidden="true" aria-labelledby="nobs-modal-title">
			<div class="nobs-modal">
				<div class="nobs-modal-icon"></div>
				<div class="nobs-modal-title" id="nobs-modal-title"></div>
				<div class="nobs-modal-content"></div>
				<div class="nobs-modal-actions">
					<button id="nobs-modal-close" class="button" aria-hidden="true"><?php _e('Close'); ?></button>
					<button id="nobs-modal-primary" class="button button-primary" id="close" aria-hidden="true"></button>
				</div>
			</div>
		</div>
	</div><!-- .wrap -->

	<?php
	} // Eo juiz_sps_settings_page()
} // Eo If function_exists()