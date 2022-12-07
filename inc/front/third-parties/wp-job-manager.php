<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Specific code for compatibility with WP Job Manager
 * @see https://wordpress.org/plugins/wp-job-manager/
 */

/**
 * Manage the printing of the buttons to the WP Job Manager content before the content.
 *
 * @author Geoffrey Crofte
 * @since 2.3.0
 * @return (string) The button printed if the right settings are applied.
 */
function nobs_include_buttons_at_start() {

	$juiz_sps_options = jsps_get_option();

	// Don't go further if the display for this CPT isn't activated.
	if ( !isset( $juiz_sps_options['juiz_sps_display_in_types'] ) || ( isset($juiz_sps_options['juiz_sps_display_in_types']) && ! in_array( 'job_listing', $juiz_sps_options['juiz_sps_display_in_types'] ))) {
		return;
	}

	// Don't go further if the option doesn't include Both or Top for the display.
	$juiz_sps_display_where = isset( $juiz_sps_options['juiz_sps_display_where'] ) ? $juiz_sps_options['juiz_sps_display_where'] : '';
	if ( 'top' !== $juiz_sps_display_where && 'both' !== $juiz_sps_display_where ) {
		return;
	}

	$need_counters = $juiz_sps_options['juiz_sps_counter'] ? 1 : 0;

	$jsps_links = get_juiz_sps( array(), $need_counters );

	/**
	 * Allows you to do something before the concatenation with the post content. 
	 * 
	 * @hook juiz_sps_before_content_concat
	 *
	 * @since  2.0.0 First version
	 *
	 * @param  {string} $jsps_links              The HTML for all the front buttons.
	 * @param  {string} $juiz_sps_display_where  A keyword among `top`, `bottom` and `both`.
	 * @param  {string} $juiz_sps_options        All the plugin's options.
	 */
	do_action( 'juiz_sps_before_content_concat', $jsps_links, $juiz_sps_display_where, $juiz_sps_options );

	/**
	 * Allows you to filter the content printed before the WP Job Manager Content 
	 * 
	 * @hook juiz_sps_before_printing_to_wp_job_manager_start
	 *
	 * @since  2.3.0 First version
	 *
	 * @param  {string} $jsps_links              The HTML for all the front buttons.
	 * @param  {string} $juiz_sps_options        All the plugin's options.
	 */
	echo apply_filters( 'juiz_sps_before_printing_to_wp_job_manager_start', $jsps_links, $juiz_sps_options);
}
add_action('single_job_listing_start', 'nobs_include_buttons_at_start');

/**
 * Manage the printing of the buttons to the WP Job Manager content after the content.
 *
 * @author Geoffrey Crofte
 * @since 2.3.0
 * @return (string) The button printed if the right settings are applied.
 */
function nobs_include_buttons_at_end() {
	$juiz_sps_options = jsps_get_option();

	// Don't go further if the display for this CPT isn't activated.
	if ( !isset( $juiz_sps_options['juiz_sps_display_in_types'] ) || ( isset($juiz_sps_options['juiz_sps_display_in_types']) && ! in_array( 'job_listing', $juiz_sps_options['juiz_sps_display_in_types'] ))) {
		return;
	}

	// Don't go further if the option doesn't include Both or Top for the display.
	$juiz_sps_display_where = isset( $juiz_sps_options['juiz_sps_display_where'] ) ? $juiz_sps_options['juiz_sps_display_where'] : '';
	if ( 'bottom' !== $juiz_sps_display_where && 'both' !== $juiz_sps_display_where ) {
		return;
	}

	$need_counters = $juiz_sps_options['juiz_sps_counter'] ? 1 : 0;

	$jsps_links = get_juiz_sps( array(), $need_counters );

	/**
	 * Allows you to do something before the concatenation with the post content. 
	 * 
	 * @hook juiz_sps_before_content_concat
	 *
	 * @since  2.0.0 First version
	 *
	 * @param  {string} $jsps_links              The HTML for all the front buttons.
	 * @param  {string} $juiz_sps_display_where  A keyword among `top`, `bottom` and `both`.
	 * @param  {string} $juiz_sps_options        All the plugin's options.
	 */
	do_action( 'juiz_sps_before_content_concat', $jsps_links, $juiz_sps_display_where, $juiz_sps_options );

	/**
	 * Allows you to filter the content printed after the WP Job Manager Content 
	 * 
	 * @hook juiz_sps_before_printing_to_wp_job_manager_end
	 *
	 * @since  2.3.0 First version
	 *
	 * @param  {string} $jsps_links              The HTML for all the front buttons.
	 * @param  {string} $juiz_sps_options        All the plugin's options.
	 */
	echo apply_filters( 'juiz_sps_before_printing_to_wp_job_manager_end', $jsps_links, $juiz_sps_options);
}
add_action('single_job_listing_end', 'nobs_include_buttons_at_end');
