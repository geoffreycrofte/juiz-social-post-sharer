<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Getting options from the right place.
 * Multisite compatibility.
 *
 * @author Marie Comet, Geoffrey Crofte
 * @since 1.4.7
 */
function jsps_get_option( $option_name = '' ) {

	// When we want a precise option in a network activated website.
	if ( ! empty( $option_name ) && true === JUIZ_SPS_NETWORK_ACTIVATED ) {
		$options = get_blog_option( get_current_blog_id(), JUIZ_SPS_SETTING_NAME );
		return $options[ $option_name ];
	}

	// When we want all options in a network activated website.
	else if ( empty( $option_name ) && true === JUIZ_SPS_NETWORK_ACTIVATED ) {
		return get_blog_option( get_current_blog_id(), JUIZ_SPS_SETTING_NAME );
	}

	// When we want a precise option in a simple website.
	else if ( ! empty( $option_name ) && false === JUIZ_SPS_NETWORK_ACTIVATED ) {
		$options = get_option( JUIZ_SPS_SETTING_NAME );
		return $options[ $option_name ];
	}

	// When we want all options in a simple website.
	else {
		return get_option( JUIZ_SPS_SETTING_NAME );
	}

}

/**
 * Updating options to the right place.
 * Multisite compatibility.
 *
 * @author Marie Comet, Geoffrey Crofte
 * @since 1.4.7
 */
function jsps_update_option( $options ) {

	if ( ! is_array( $options ) ) {
		die( '$options has to be an array' );
	}

	// When we want to update options in a network activated website.
	if ( true === JUIZ_SPS_NETWORK_ACTIVATED ) {
		$options = update_blog_option( get_current_blog_id(), JUIZ_SPS_SETTING_NAME, $options );
	}

	// When we want to update options in a simple website.
	else {
		$options = update_option( JUIZ_SPS_SETTING_NAME, $options );
	}

	return $options;
}
