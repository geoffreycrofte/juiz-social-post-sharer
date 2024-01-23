<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

// Activation hook
register_activation_hook( JUIZ_SPS_FILE, 'juiz_sps_activation' );

/**
 * Set things at plugin activation.
 *
 * @return void
 * @author Geoffrey Crofte
 *
 * @since   2.0.0  Remove old code for too far backward compatibility.
 * @since   1.4.10 Uses jsps_get_initial_settings() to init options, and jsps_init_option_ms() for Multisite support.
 * @since   1.0.0
 *
 * @version 2.0.0
 */
function juiz_sps_activation() {
	$juiz_sps_options = jsps_get_option();

	if ( ! is_array( $juiz_sps_options ) ) {
		
		$default_options = jsps_get_initial_settings();
		
		jsps_update_option( $default_options );
		jsps_init_option_ms( $default_options );
	}
	else {
		// If was version under 2.0.0
		if ( ! isset( $juiz_sps_options['juiz_sps_version'] ) || ( isset( $juiz_sps_options['juiz_sps_version'] ) && version_compare( $juiz_sps_options['juiz_sps_version'], '2.0.0', '<') ) ) {

			$juiz_sps_options['juiz_sps_version'] = JUIZ_SPS_VERSION; // since 1.4.1
			$juiz_sps_options['juiz_sps_order'] = array(); // since 2.0.0
			
			jsps_update_option( $juiz_sps_options );
			jsps_init_option_ms( $juiz_sps_options );
		}
	}

	// Clean transient on activation, always.
	delete_transient( JUIZ_SPS_SLUG . '-changelog' );
	delete_transient( JUIZ_SPS_SLUG . '-skin-shop-markup' );

	// Debug purpose
	// jsps_update_user_options( array() );
}

/**
 * Get the initial settings for this plugin.
 *
 * TODO: see in a next version (2.0.0+) how to do all of that without preset options.
 *
 * @return (array) The array of option.
 *
 * @since  2.0.0  Remove old options/values. Includes a filter hook.
 * @since  1.4.11 First use.
 *
 * @author Geoffrey Crofte
 */
function jsps_get_initial_settings() {
	/**
	 * Gets the initial (sometimes empty) set of options for this plugin.
	 * 
	 * @hook jsps_get_initial_settings
	 * 
 	 * @since  2.0.0 First version of the hook
 	 * 
 	 * @param  {array}   opts                               - The multiple used options.
 	 * @param  {string}  opts.juiz_sps_version              - The version of the plugin.
 	 * @param  {string}  opts.juiz_sps_style                - The name of the style.
 	 * @param  {array}   opts.juiz_sps_networks             - Empty array to be filled.
 	 * @param  {array}   opts.juiz_sps_order                - Empty array to be filled.
 	 * @param  {int}     opts.juiz_sps_counter              - Show the counter? (1 = yes, 0 = no)
 	 * @param  {string}  opts.juiz_sps_counter_option       - Inline or global, or both?
 	 * @param  {int}     opts.juiz_sps_hide_social_name     - Hide the text? (1 = yes, 0 = no)
 	 * @param  {int}     opts.juiz_sps_target_link          - Open in new tab? (1 = yes, 0 = no)
 	 * @param  {string}  opts.juiz_sps_twitter_user         - The Twitter via option.
 	 * @param  {array}   opts.juiz_sps_display_in_types     - The post-types where to show buttons.
 	 * @param  {string}  opts.juiz_sps_display_where        - bottom, top, both, or none.
 	 * @param  {int}     opts.juiz_sps_write_css_in_html    - A performance option (not used yet)
 	 * @param  {string}  opts.juiz_sps_mail_subject         - Subject of mail to be sent.
 	 * @param  {string}  opts.juiz_sps_mail_body            - Body of mail to be sent.
 	 * @param  {int}     opts.juiz_sps_force_pinterest_snif - Force use JS for Pinterest button.
 	 * @param  {array}   opts.juiz_sps_colors               - Array of colors for future use.
 	 * @param  {string}  opts.juiz_sps_colors.bg_color      - A normal background color.
 	 * @param  {string}  opts.juiz_sps_colors.bg_hcolor     - A hover background color.
 	 * @param  {string}  opts.juiz_sps_colors.txt_color     - A normal text color.
 	 * @param  {string}  opts.juiz_sps_colors.txt_hcolor    - A hover text color.
 	 * 
 	 * @return {array} The options ready to be used to initial setting or multisite setup.
 	 *
	 */
	return apply_filters(
			'jsps_get_initial_settings',
			array(
				'juiz_sps_version' 			=> JUIZ_SPS_VERSION, // since 1.4.1
				'juiz_sps_style' 			=> 7, // Can be a string
				'juiz_sps_networks' 		=> array(), // empty since 2.0.0 (see register-networks.php)
				'juiz_sps_order'			=> array(), // since 2.0.0
				'juiz_sps_counter'			=> 0,
				'juiz_sps_counter_option'	=> 'both', // since 1.3.3.7
				'juiz_sps_hide_social_name' => 0,
				'juiz_sps_target_link'		=> 0, // since 1.1.0
				'juiz_sps_twitter_user'		=> 'wpsharebuttons',
				'juiz_sps_display_in_types' => array( 'post' ),
				'juiz_sps_display_where'	=> 'bottom',
				'juiz_sps_write_css_in_html'=> 0,
				'juiz_sps_mail_subject'		=> __( 'Visit this link found on %%siteurl%%', 'juiz-social-post-sharer'),
				'juiz_sps_mail_body'		=> __( 'Hi, I found this information for you : "%%title%%"! This is the direct link: %%permalink%% Have a nice day :)', 'juiz-social-post-sharer' ),
				'juiz_sps_force_pinterest_snif' => 0,
				'juiz_sps_colors' 			=> array(
					'bg_color'   => '', 
					'bg_hcolor'  => '', 
					'txt_color'  => '',
					'txt_hcolor' => '',
				)
			)
		);
}
