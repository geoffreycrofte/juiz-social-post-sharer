<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

// Activation hook
register_activation_hook( JUIZ_SPS_FILE, 'juiz_sps_activation' );

function juiz_sps_activation() {

	$juiz_sps_options = jsps_get_option();

	if ( ! is_array( $juiz_sps_options ) ) {
		
		$default_options = array(
			'juiz_sps_version' 			=> JUIZ_SPS_VERSION, // since 1.4.1
			'juiz_sps_style' 			=> 7,
			'juiz_sps_networks' 		=> array(), // empty since 2.0.0 (see register-networks.php)
			'juiz_sps_order'			=> array(), // since 2.0.0
			'juiz_sps_counter'			=> 0,
			'juiz_sps_counter_option'	=> 'both', // since 1.3.3.7
			'juiz_sps_hide_social_name' => 0,
			'juiz_sps_target_link'		=> 0, // since 1.1.0
			'juiz_sps_twitter_user'		=> 'CreativeJuiz',
			'juiz_sps_display_in_types' => array( 'post' ),
			'juiz_sps_display_where'	=> 'bottom',
			'juiz_sps_write_css_in_html'=> 0,
			'juiz_sps_mail_subject'		=> __( 'Visit this link find on %%siteurl%%', 'juiz-social-post-sharer'),
			'juiz_sps_mail_body'		=> __( 'Hi, I found this information for you : "%%title%%"! This is the direct link: %%permalink%% Have a nice day :)', 'juiz-social-post-sharer' ),
			'juiz_sps_force_pinterest_snif' => 0,
			'juiz_sps_colors' 			=> array(
					'bg_color'	=> '', 
					'txt_color' => ''
				)
		);
		
		jsps_update_option( $default_options );
	}
	else {
		// If was version under 2.0.0
		if ( ! isset( $juiz_sps_options['juiz_sps_version'] ) || ( isset( $juiz_sps_options['juiz_sps_version'] ) && version_compare( $juiz_sps_options['juiz_sps_version'], '2.0.0', '<') ) ) {

			$juiz_sps_options['juiz_sps_version'] = JUIZ_SPS_VERSION; // since 1.4.1
			$juiz_sps_options['juiz_sps_order'] = array(); // since 2.0.0
			
			jsps_update_option( $juiz_sps_options );
		}
	}
}
