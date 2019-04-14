<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Get the custom array of themes.
 * 
 * @param  (array) $core_themes The themes array.
 * @return (array)              The new array of themes.
 */
function jsps_get_core_themes( $core_themes = array() ) {
	/**
	 * Structure of theme array.
	 * 
		$core_themes[ 'theme-slug' ] = array(
			'name'       => 'Theme Name',
		);
	 */
	$core_themes = apply_filters( 'jsps_register_core_theme', $core_themes );
	return $core_themes;
}

/**
 * Get the custom array of themes.
 *
 * @param  (array) $custom_themes The themes array.
 * @return (array)                The new array of themes.
 */
function jsps_get_custom_themes( $custom_themes = array() ) {
	/**
	 * Structure of theme array.
	 * 
	 	$core_themes[ 'theme-slug' ] = array(
			'name'       => 'Theme Name',
			'author'     => 'Geofrey Crofte', // optional
			'author_url' => 'https://geoffrey.crofte.fr/en/', // optional
			'css_url'    => '../style-theme-slug.css', // optional
			'demo_url'   => '../theme-visual.png', // optional
		);
	 */
	$custom_themes = apply_filters( 'jsps_register_custom_theme', $custom_themes );

	// TODO: check array structure to pop error.
	return $custom_themes;
}