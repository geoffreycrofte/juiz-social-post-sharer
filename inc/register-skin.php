<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Get the core array of skins.
 * 
 * @param  (array) $core_skins The skins array.
 * @return (array)             Filtered new array of Core Skins.
 *
 * @since  2.0.0
 * @author Geoffrey Crofte
 */
function jsps_get_core_skins( $core_skins = array() ) {
	/**
	 * Structure of skin array.
	 * 
		$core_skins[ 'skin-slug' ] = array(
			'name'       => 'Skin Name',
		);
	 */
	$core_skins = apply_filters( 'jsps_register_core_skin', $core_skins );
	return $core_skins;
}

/**
 * Get the custom array of skins.
 * 
 * @param  (array) $custom_skins The custom skins array.
 * @return (array)               Filtered new array of Custom Skins.
 *
 * @since  2.0.0
 * @author Geoffrey Crofte
 */
function jsps_get_custom_skins( $custom_skins = array() ) {
	/**
	 * Structure of skin array.
	 * 
	 	$custom_skins[ 'skin-slug' ] = array(
			'name'       => 'Skin Name',
			'author'     => 'Geofrey Crofte', // optional
			'author_url' => 'https://geoffrey.crofte.fr/en/', // optional
			'css_url'    => '../style-skin-slug.css', // optional
			'demo_url'   => '../skin-visual.png', // optional
		);
	 */
	$custom_skins = apply_filters( 'jsps_register_custom_skin', $custom_skins );

	// TODO: check array structure to pop error.
	return $custom_skins;
}