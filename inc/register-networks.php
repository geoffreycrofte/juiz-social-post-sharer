<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Register default networks.
 */
function jsps_register_core_networks( $core_networks ) {

	$core_networks = array(
		'diigo'    => array(
			'name'    => __( 'Diigo', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'evernote'    => array(
			'name'    => __( 'Evernote', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'facebook'    => array(
			'name'    => __( 'Facebook', 'juiz-social-post-sharer' ),
			'visible' => 1,
		),
		'linkedin'    => array(
			'name'    => __( 'LinkedIn', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'mix'    => array(
			'name'    => __( 'Mix', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'pinterest'   => array(
			'name'    => __( 'Pinterest', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'pocket'   => array(
			'name'    => __( 'Pocket', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'reddit'      => array(
			'name'    => __( 'Reddit', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'twitter'     => array(
			'name'    => __( 'Twitter', 'juiz-social-post-sharer' ),
			'visible' => 1,
		),
		'tumblr'      => array(
			'name'    => __( 'Tumblr', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'viadeo'      => array(
			'name'    => __( 'Viadeo', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'vk'          => array(
			'name'    => __( 'VKontakte', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'weibo'       => array(
			'name'    => __( 'Weibo', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'whatsapp'       => array(
			'name'    => __( 'WhatsApp', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),

		// Some utilities
		'mail'        => array(
			'name'    => __( 'Email', 'juiz-social-post-sharer' ),
			'visible' => 1,
		),
		'bookmark'    => array(
			'name'    => __( 'Bookmark', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'print'       => array(
			'name'    => __( 'Print', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
	);

	return apply_filters( 'jsps_register_core_networks', $core_networks );
}
add_filter( 'jsps_register_core_network', 'jsps_register_core_networks' );
