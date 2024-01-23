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
			'name'    => _x( 'Diigo', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'evernote'    => array(
			'name'    => _x( 'Evernote', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'facebook'    => array(
			'name'    => _x( 'Facebook', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 1,
		),
		'linkedin'    => array(
			'name'    => _x( 'LinkedIn', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'mix'    => array(
			'name'    => _x( 'Mix', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'pinterest'   => array(
			'name'    => _x( 'Pinterest', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'pocket'   => array(
			'name'    => _x( 'Pocket', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'reddit'      => array(
			'name'    => _x( 'Reddit', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'twitter'     => array(
			'name'    => _x( 'X', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 1,
		),
		'tumblr'      => array(
			'name'    => _x( 'Tumblr', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'viadeo'      => array(
			'name'    => _x( 'Viadeo', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'vk'          => array(
			'name'    => _x( 'VKontakte', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'weibo'       => array(
			'name'    => _x( 'Weibo', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'whatsapp'       => array(
			'name'    => _x( 'WhatsApp', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),

		// Some utilities
		'shareapi'    => array(
			'name'    => _x( 'Share…', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 1,
		),
		'mail'        => array(
			'name'    => _x( 'Email', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'bookmark'    => array(
			'name'    => _x( 'Bookmark', 'Button Name', 'juiz-social-post-sharer' ),
			'visible' => 0,
		),
		'print'       => array(
			'name'    => _x( 'Print', 'Button Name', 'juiz-social-post-sharer' ),
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
