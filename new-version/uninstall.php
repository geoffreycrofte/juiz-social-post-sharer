<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

/**
 * Cleans Site(s) options.
 */
delete_option( 'juiz_SPS_settings' );
delete_site_option( 'juiz_SPS_settings' );

/**
 * Cleans user options.
 */
$users = get_users(array(
	'role__in' => array( 'administrator' ),
));

if ( is_array( $users ) ) {
	foreach ($users as $user) {
		delete_user_option( $user->ID, 'juiz_SPS_settings', false ); // false stands for blog specific (not global)
	}
}
