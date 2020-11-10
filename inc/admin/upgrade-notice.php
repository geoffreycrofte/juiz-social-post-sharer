<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin’ uh?' );
}

/**
 * Simply return a string for upgrade notice.
 * 
 * @return string
 */
function jsps_get_major_upgrade_notice() {
	return sprintf(
		__( 'Includes a lot of improvements. It has been tested on our side and should be ok. But WordPress is a big universe, it might be breaking changes for your specific website. If you can: double check on a safe (test) environment that everything is going to be ok for your website. Don’t forget to %sbackup your website%s.', 'juiz-social-post-sharer'),
		'<a style="color:var(--errorbg);" href="https://wordpress.org/plugins/updraftplus/" target="_blank">',
		'</a>'
	);
}

/**
 * The function to put upgrage notice into single site.
 *
 * @param  array $plugin_data The array of data from Readme (name, plugin_url, version, description, author, author_url, text_domain, domain_path, network, title, author_name, update)
 * @param  object $new_data Some other info about the plugin (id, slug, new_version, url, package)
 *
 * @return void
 *
 * @author Geoffrey Crofte
 * @since  1.4.11
 */
function jsps_plugin_update_message( $plugin_data, $new_data ) {

	if ( ! isset( $plugin_data['new_version'] ) && ! isset( $plugin_data['Version'] ) ) {
		return false;
	}

	var_dump( $plugin_data );

	$new  = explode( '.', $plugin_data['new_version'] );
	$curr = explode( '.', $plugin_data['Version'] );

	if ( isset( $plugin_data['update'] ) && $plugin_data['update'] && $new[0] > $curr[0] ) {
		printf(
			'<div style="background:var(--error);padding:8px 16px;" class="jsps-warning"><style>.jsps-warning p::before{content:none;}.jsps-warning+p:empty{display:none}.jsps-warning{margin:16px 0}</style><p style="color:var(--errorbg);font-weight:bold;"><strong>%s</strong>: %s</p></div>',
			$new_data -> new_version,
			jsps_get_major_upgrade_notice()
		);
	}

}
add_action( 'in_plugin_update_message-juiz-social-post-sharer/juiz-social-post-sharer.php', 'jsps_plugin_update_message', 10, 2 );

/**
 * The function to put upgrage notice into Multi-site.
 *
 * @param  array $file   The file path loading the plugin, from the plugin directory.
 * @param  array $plugin Seems to be the same as $plugin_data in in_plugin_update_message hook.
 *
 * @return void
 *
 * @author Geoffrey Crofte
 * @since  1.4.11
 */
function jsps_ms_plugin_update_message( $file, $plugin, $status ) {
	if ( ! is_multisite() ) {
		return false;
	}

	if ( $file !== 'juiz-social-post-sharer/juiz-social-post-sharer.php' ) {
		return false;
	}

	$new  = explode( '.', $plugin['new_version'] );
	$curr = explode( '.', $plugin['Version'] );

	if ( $new[0] > $curr[0] ) {
		$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );

		printf(
			'<tr class="plugin-update-tr"><td colspan="%s" class="plugin-update update-message notice inline notice-warning notice-alt"><div class="update-message"><h4 style="margin: 0; font-size: 14px;">%s</h4><p>%s</p></div></td></tr>',
			$wp_list_table->get_column_count(),
			$plugin['Name'],
			jsps_get_major_upgrade_notice()
		);
	}
}
add_action( 'after_plugin_row', 'jsps_ms_plugin_update_message', 10, 3 );
