<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

if ( ! function_exists( 'jsps_load_custom_wp_admin_assets' ) ) {
	/**
	 * Include Admin dedicated scripts.
	 * @return void
	 * @author Geoffrey Crofte
	 *
	 * @since 2.2.0 Gutenber JS File loading
	 * @since 2.0.0
	 */
	function jsps_load_custom_wp_admin_assets() {
		global $current_screen;

		// Enqueue Gutenberg JS Files everywhere.
		if (
			method_exists( $current_screen, 'is_block_editor' )
			&& $current_screen->is_block_editor()
			&& 'widgets' !== $current_screen->base
		) {
			$asset_file = include_once( dirname( JUIZ_SPS_FILE ) . '/build/index.asset.php');
			wp_enqueue_script(
			    'nobs-gutenberg-script',
			    JUIZ_SPS_PLUGIN_URL . 'build/index.js',
			    $asset_file['dependencies'],
			    $asset_file['version']
			);
		}

		// if not on the plugin's pages, do not include the CSS and JS files.
		if ( $current_screen->base !== 'settings_page_juiz-social-post-sharer' 
			 &&
			 $current_screen->base !== 'settings_page_juiz-social-post-sharer-welcome' )
		{
			return;
		}
		
		wp_enqueue_script( 'jsps-admin-script', JUIZ_SPS_PLUGIN_ASSETS . 'admin/admin.js', array('jquery'), JUIZ_SPS_VERSION );
		wp_enqueue_style( 'jsps-admin-styles', JUIZ_SPS_PLUGIN_ASSETS . 'admin/admin.css', 
			array(), JUIZ_SPS_VERSION, 'all' );

		// Admin some useful variables
		$network_order_action_name = 'juiz_sps_order_networks';
		$networkOrderNonce = wp_create_nonce( $network_order_action_name );

		$skin_loading_action_name = 'juiz_sps_skin_loading';
		$skinLoadingNonce = wp_create_nonce( $skin_loading_action_name );

		wp_localize_script( 'jsps-admin-script', 'jsps', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'networkOrderNonce'  => $networkOrderNonce,
			'networkOrderAction' => $network_order_action_name, 
			'skinLoadingNonce'   => $skinLoadingNonce,
			'skinLoadingAction'  => $skin_loading_action_name,
			'modalShareAPITitle' => __( 'Share API - Important Info', 'juiz-social-post-sharer' ),
			'modalShareAPIIcon'  => '<i class="jsps-icon-shareapi" role="presentation"></i>',
			'modalShareAPIContent' => sprintf( __( '%1$sNote that in some cases you won’t see this button yourself:%2$s %3$s %5$sIf your website isn’t in HTTPS %7$sbecause this new piece of technology needs secure website%8$s%6$s %5$sIf you watch it on a desktop browser %7$sthis API isn’t supported by most of them yet%8$s%6$s %5$sIf your mobile device if too old %7$sthis API wouldn’t be supported neither%8$s%6$s %4$s %1$sBut no worries, your visitors will be able to see it if website is in HTTPS.%2$s', 'juiz-social-post-sharer'), '<p>', '</p>', '<ul>', '</ul>', '<li>', '</li>', '<span class="small">', '</span>' ),
		) );
	}

	add_action( 'admin_enqueue_scripts', 'jsps_load_custom_wp_admin_assets' );
}
