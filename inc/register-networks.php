<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Register default networks.
 * 
 * @param  (array) $core_networks Empty Array of initial Core Networks
 * @return (array)                Filtered array of Core Networks
 *
 * @since  2.0.0
 * @author  Geoffrey Crofte
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
		'shareapi'    => array(
			'name'    => __( 'Share…', 'juiz-social-post-sharer' ),
			'visible' => 1,
		),
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

	/**
	 * Filters the list of Core Networks after setting those.
	 * 
	 * @hook jsps_register_core_networks
	 *
 	 * @since  2.0.0 First version
 	 *
 	 * @param  {array}  custnw  The list of Custom Networks, see {@link jsps_register_core_network} for detail.
 	 *
 	 * @return {array}          The default list of Core Network.
	 */
	return apply_filters( 'jsps_register_core_networks', $core_networks );
}
add_filter( 'jsps_register_core_network', 'jsps_register_core_networks' );
