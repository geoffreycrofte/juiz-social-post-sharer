<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

if ( ! function_exists( 'jsps_load_custom_wp_admin_assets' ) ) {
	/**
	 * Include Admin dedicated scripts.
	 * @return void
	 * @author Geoffrey Crofte
	 * @since 2.0.0
	 */
	function jsps_load_custom_wp_admin_assets() {
		global $current_screen;

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

		wp_localize_script( 'jsps-admin-script', 'jsps', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'networkOrderNonce' => $networkOrderNonce,
			'networkOrderAction' => $network_order_action_name 
		) );
	}

	add_action( 'admin_enqueue_scripts', 'jsps_load_custom_wp_admin_assets' );
}
