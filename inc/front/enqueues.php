<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Enqueue & Dequeue scripts and styles needed.
 *
 * @return void
 * @since  1.0
 * @author Geoffrey Crofte
 */
if ( ! function_exists('jsps_enqueue_scripts') ) {
	function jsps_enqueue_scripts() {
		$prefix = ( defined('WP_DEBUG') && WP_DEBUG === true ) ? '' : '.min';

		// Enqueue Main Script.
		wp_enqueue_script(
			'juiz_sps_scripts',
			JUIZ_SPS_PLUGIN_ASSETS . 'js/' . JUIZ_SPS_SLUG . $prefix . '.js',
			array( 'jquery' ),
			JUIZ_SPS_VERSION,
			true
		);

		// Localize the main script.
		wp_localize_script( 'juiz_sps_scripts', 'jsps', array(
			'modalLoader'			=> '<img src="' . JUIZ_SPS_PLUGIN_ASSETS . 'img/loader.svg" height="22" width="22" alt="">',
			'modalEmailTitle'		=> esc_html__( 'Share by email', 'juiz-social-post-sharer' ),
			'modalEmailInfo'		=> esc_html__( 'We won\'t save or reuse these email addresses., we promise.', 'juiz-social-post-sharer' ),
			'modalEmailNonce'		=> wp_create_nonce('jsps-email-friend'),
			'ajax_url'              => admin_url( 'admin-ajax.php' ),
			'modalEmailName'		=> esc_html__( 'Your name', 'juiz-social-post-sharer' ),
			'modalEmailYourEmail'	=> esc_html__( 'Your email', 'juiz-social-post-sharer' ),
			'modalEmailFriendEmail'	=> esc_html__( 'Recipient\'s email', 'juiz-social-post-sharer' ),
			'modalEmailMessage'		=> esc_html__( 'Personal message', 'juiz-social-post-sharer' ),
			'modalEmailOptional'		=> esc_html__( 'optional', 'juiz-social-post-sharer' ),
			'modalEmailMsgInfo'		=> esc_html__( 'A link to this post will be automatically included in your message.', 'juiz-social-post-sharer' ),
			'modalEmailSubmit'		=> esc_html__( 'Send this article', 'juiz-social-post-sharer' ),
			'modalEmailFooter'		=> apply_filters( 'jsps_show_modal_footer', true) ? sprintf( __( 'Service proposed by %sSocial Post Sharer%s', 'juiz-social-post-sharer' ), '<a href="https://wordpress.org/plugins/juiz-social-post-sharer/" target="_blank">', '</a>' ) : '',
			'modalClose'			=> esc_html__( 'Close', 'juiz-social-post-sharer' ),
			'modalErrorGeneric'		=> esc_html__( 'Sorry. It looks like we\'ve got an error on our side.', 'juiz-social-post-sharer' )
		) );
	}
}

if ( ! function_exists( 'juiz_sps_style_and_script' ) ) {

	function juiz_sps_style_and_script() {

		$juiz_sps_options = jsps_get_option();

		if ( is_array( $juiz_sps_options ) ) {

			$prefix = ( defined('WP_DEBUG') && WP_DEBUG === true ) ? '' : '.min';

			// CSS to add to queue.
			if ( isset( $juiz_sps_options['juiz_sps_style'] ) && apply_filters( 'juiz_sps_use_default_css', true ) ) {

				$core_themes   = jsps_get_core_themes();
				$custom_themes = jsps_get_custom_themes();
				$all_themes    = $core_themes + $custom_themes;
				$current_slug  = $juiz_sps_options['juiz_sps_style'];

				$css_file = isset( $all_themes[ $current_slug ]['css_url'] ) ? $all_themes[ $current_slug ]['css_url'] : JUIZ_SPS_THEMES_FOLDER . $current_slug . '/style' . $prefix . '.css';

				// The CSS file for theme.
				wp_enqueue_style( 'juiz_sps_styles', $css_file, false, JUIZ_SPS_VERSION, 'all' );

				// The CSS file for modal.
				wp_enqueue_style( 'juiz_sps_modal_styles', JUIZ_SPS_PLUGIN_ASSETS . 'css/' . JUIZ_SPS_SLUG . '-modal' . $prefix . '.css', false, JUIZ_SPS_VERSION, 'all' );
			}

			// JS To Add to queue.
			if (
				( is_numeric ( $juiz_sps_options['juiz_sps_counter'] ) && $juiz_sps_options['juiz_sps_counter'] == 1 )
				||
				( isset( $juiz_sps_options['juiz_sps_networks']['print'] ) && $juiz_sps_options['juiz_sps_networks']['print'][0] === 1 )
				||
				( isset( $juiz_sps_options['juiz_sps_networks']['bookmark'] ) && $juiz_sps_options['juiz_sps_networks']['bookmark'][0] === 1 )
				||
				( isset( $juiz_sps_options['juiz_sps_networks']['mail'] ) && $juiz_sps_options['juiz_sps_networks']['mail'][0] === 1 )
			) {
				jsps_enqueue_scripts();
			}
		}
	}
	add_action( 'wp_enqueue_scripts', 'juiz_sps_style_and_script');
}
