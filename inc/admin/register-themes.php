<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Register default themes.
 */
function jsps_register_core_themes( $core_themes ) {

	$core_themes['1'] = array(
		'name' => __( 'Juizy Light Tone', 'juiz-social-post-sharer' ),
	);

	$core_themes['2'] = array(
		'name' => __( 'Juizy Light Tone Reversed', 'juiz-social-post-sharer' ),
	);

	$core_themes['3'] = array(
		'name' => __( 'Blue Metro Style', 'juiz-social-post-sharer' ),
	);

	$core_themes['4'] = array(
		'name' => __( 'Gray Metro Style', 'juiz-social-post-sharer' ),
	);

	$core_themes['5'] = array(
		'name'       => __( 'Modern Style', 'juiz-social-post-sharer' ),
		'author'     => 'Tony Trancard',
		'author_url' => 'https://tonytrancard.fr/',
	);

	$core_themes['6'] = array(
		'name'       => __( 'Black', 'juiz-social-post-sharer' ),
		'author'     => 'Fandia',
		'author_url' => 'http://fandia.w.pw/',
	);

	$core_themes['7'] = array(
		'name' => __( 'Brands Colors', 'juiz-social-post-sharer' ),
	);

	$core_themes['8'] = array(
		'name' => __( 'Material Brand Colors', 'juiz-social-post-sharer' ),
	);

	return apply_filters( 'jsps_register_core_themes', $core_themes );
}
add_filter( 'jsps_register_core_theme', 'jsps_register_core_themes' );
