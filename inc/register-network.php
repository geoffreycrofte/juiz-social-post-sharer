<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Get the core array of networks.
 *
 * @param  (array) $core_networks The networks array.
 * @return (array)                The new array of networks.
 */
function jsps_get_core_networks( $core_networks = array() ) {
	/**
	 * Structure of theme array.
	 * 
		$core_networks[ 'network-name' ] = array(
			'name'    => __( 'Network Name', 'juiz-social-post-sharer' ),
			'visible' => 1,
		);
	 */
	$core_networks = apply_filters( 'jsps_register_core_network', $core_networks );

	return $core_networks;
}

/**
 * Get the custom array of networks.
 *
 * @param  (array)  $custom_networks The networks array.
 * @return (array)                   The new array of networks.
 */
function jsps_get_custom_networks( $custom_networks = array() ) {
	/**
	 * Structure of theme array.
	 * 
	 	$custom_networks[ 'network-slug' ] = array(
			'name'       => 'Network Name',
			'api_url'    => 'https://geoffrey.crofte.fr/en/',
			'icon'       => 'css-classname' || 'path/to/image.svg', // optional
			'title'      => __( 'Share on Netwok Name', 'textdomain' ), // optional
			'color'      => '#bada55', // optional
		);
	 */
	$custom_networks = apply_filters( 'jsps_register_custom_network', $custom_networks );

	// TODO: check array structure to pop error.
	return $custom_networks;
}