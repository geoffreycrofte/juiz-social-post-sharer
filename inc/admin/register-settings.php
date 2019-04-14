<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

// Activation hook
register_activation_hook( JUIZ_SPS_FILE, 'juiz_sps_activation' );

function juiz_sps_activation() {

	$juiz_sps_options = jsps_get_option();

	if ( ! is_array( $juiz_sps_options ) ) {
		
		$default_array = array(
			'juiz_sps_version' 			=> JUIZ_SPS_VERSION,
			'juiz_sps_style' 			=> 7,
			'juiz_sps_networks' 		=> array(
				'delicious'		=>	array( 0, __( 'Delicious', 'juiz-social-post-sharer' ) ), // new 1.4.1
				'digg'	 		=>	array( 0, __( 'Digg', 'juiz-social-post-sharer' ) ),
				'facebook'		=>	array( 1, __( 'Facebook', 'juiz-social-post-sharer' ) ), 
				'google'		=>	array( 0, __( 'Google+', 'juiz-social-post-sharer' ) ),
				'linkedin' 		=>	array( 0, __( 'LinkedIn', 'juiz-social-post-sharer' ) ),
				'pinterest' 	=>	array( 0, __( 'Pinterest', 'juiz-social-post-sharer' ) ),
				'reddit'		=>	array( 0, __( 'Reddit', 'juiz-social-post-sharer' ) ), // new 1.4.1
				'stumbleupon'	=>	array( 0, __( 'StumbleUpon', 'juiz-social-post-sharer' ) ),
				'twitter'		=>	array( 1, __( 'Twitter', 'juiz-social-post-sharer' ) ), 
				'tumblr'		=>	array( 0, __( 'Tumblr', 'juiz-social-post-sharer' ) ), // new 1.4.1
				'viadeo' 		=>	array( 0, __( 'Viadeo', 'juiz-social-post-sharer' ) ),
				'vk'			=>	array( 0, __( 'VKontakte', 'juiz-social-post-sharer' ) ), // new 1.3.0
				'weibo'			=>	array( 0, __( 'Weibo', 'juiz-social-post-sharer' ) ), // new 1.2.0
				'mail'			=>	array( 1, __( 'Email', 'juiz-social-post-sharer' ) ),
				'bookmark'		=>	array( 0, __( 'Bookmark', 'juiz-social-post-sharer' ) ), // new 1.4.2
				'print'			=>	array( 0, __( 'Print', 'juiz-social-post-sharer' ) ) // new 1.4.2
										),
			'juiz_sps_order'			=> array(), // new 1.5.0
			'juiz_sps_counter'			=> 0,
			'juiz_sps_counter_option'	=> 'both', // new 1.3.3.7
			'juiz_sps_hide_social_name' => 0,
			'juiz_sps_target_link'		=> 0, // new 1.1.0
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
		
		jsps_update_option( $default_array );
	}
	else {
		// if was version under 1.2.3
		if ( ! isset( $juiz_sps_options['juiz_sps_force_pinterest_snif'] ) ) {
			$new_options = array (
				'juiz_sps_force_pinterest_snif' => 0
			);

			$updated_array = array_merge( $juiz_sps_options, $new_options );
			jsps_update_option( $updated_array );
		}

		// if was version under 1.3.0
		if ( ! isset( $juiz_sps_options['juiz_sps_networks']['vk'] ) ) {

			$juiz_sps_options['juiz_sps_networks']['vk'] = array( 0, __( 'VKontakte', 'juiz-social-post-sharer' ) );
			$juiz_sps_options['juiz_sps_colors'] = array( 'bg_color' => '', 'txt_color' => ''); // for next update

			jsps_update_option( $juiz_sps_options );
		}

		// if was version under 1.3.3.7
		if ( ! isset( $juiz_sps_options['juiz_sps_counter_option'] ) ) {

			$juiz_sps_options['juiz_sps_counter_option'] = 'both';

			jsps_update_option( $juiz_sps_options );
		}

		// if was version under 1.4.1
		if ( ! isset( $juiz_sps_options['juiz_sps_version'] ) ) {
			
			$juiz_sps_options['juiz_sps_version'] = JUIZ_SPS_VERSION;
			$juiz_sps_options['juiz_sps_networks']['tumblr'] 	= array( 0, __( 'Tumblr', 'juiz-social-post-sharer' ) );
			$juiz_sps_options['juiz_sps_networks']['delicious'] = array( 0, __( 'Delicious', 'juiz-social-post-sharer' ) );
			$juiz_sps_options['juiz_sps_networks']['reddit'] 	= array( 0, __( 'Reddit', 'juiz-social-post-sharer' ) );

			jsps_update_option( $juiz_sps_options );
		}

		// if was version under 1.4.2
		if ( ! isset( $juiz_sps_options['juiz_sps_version'] ) || ( isset( $juiz_sps_options['juiz_sps_version'] ) && version_compare( $juiz_sps_options['juiz_sps_version'], '1.4.2', '<') ) ) {
			
			$juiz_sps_options['juiz_sps_version'] = JUIZ_SPS_VERSION;
			$juiz_sps_options['juiz_sps_networks']['bookmark'] 	= array( 0, __( 'Bookmark', 'juiz-social-post-sharer' ) );
			$juiz_sps_options['juiz_sps_networks']['print'] = array( 0, __( 'Print', 'juiz-social-post-sharer' ) );

			jsps_update_option( $juiz_sps_options );
		}

		// if was version under 1.5.0
		if ( ! isset( $juiz_sps_options['juiz_sps_version'] ) || ( isset( $juiz_sps_options['juiz_sps_version'] ) && version_compare( $juiz_sps_options['juiz_sps_version'], '1.5.0', '<') ) ) {
			$juiz_sps_options['juiz_sps_order'] = array();
			jsps_update_option( $juiz_sps_options );
		}
	}
}
