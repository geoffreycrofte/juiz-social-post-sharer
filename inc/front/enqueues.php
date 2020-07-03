<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

if ( ! function_exists('jsps_enqueue_scripts') ) {

	/**
	 * Enqueue & Dequeue scripts function (prepare).
	 *
	 * @return void
	 *
	 * @since  1.0
	 * @version Updated: 2.0.0
	 * @author Geoffrey Crofte
	 */
	function jsps_enqueue_scripts() {
		$prefix = ( defined('WP_DEBUG') && WP_DEBUG === true ) ? '' : '.min';

		// Enqueue Main Script.
		wp_enqueue_script(
			'juiz_sps_scripts',
			JUIZ_SPS_PLUGIN_ASSETS . 'js/' . JUIZ_SPS_SLUG . $prefix . '.js',
			array( 'jquery' ), // TODO: remove dependency. (rewrite JS)
			JUIZ_SPS_VERSION,
			true
		);

		// Localize the main script.
		wp_localize_script( 'juiz_sps_scripts', 'jsps', array(
			'modalLoader'			=> '<img src="' . JUIZ_SPS_PLUGIN_ASSETS . 'img/loader.svg" height="22" width="22" alt="">',
			'modalEmailTitle'		=> esc_html__( 'Share by email', 'juiz-social-post-sharer' ),
			'modalEmailInfo'		=> esc_html__( 'We won\'t save or reuse these email addresses, we promise.', 'juiz-social-post-sharer' ),
			'modalEmailNonce'		=> wp_create_nonce('jsps-email-friend'),
			'ajax_url'              => admin_url( 'admin-ajax.php' ),
			'modalEmailName'		=> esc_html__( 'Your name', 'juiz-social-post-sharer' ),
			'modalEmailYourEmail'	=> esc_html__( 'Your email', 'juiz-social-post-sharer' ),
			'modalEmailFriendEmail'	=> esc_html__( 'Recipient\'s email', 'juiz-social-post-sharer' ),
			'modalEmailMessage'		=> esc_html__( 'Personal message', 'juiz-social-post-sharer' ),
			'modalEmailOptional'		=> esc_html__( 'optional', 'juiz-social-post-sharer' ),
			'modalEmailMsgInfo'		=> esc_html__( 'A link to the article is automatically added in your message.', 'juiz-social-post-sharer' ),
			'modalEmailSubmit'		=> esc_html__( 'Send this article', 'juiz-social-post-sharer' ),

			/**
			 * Displays the `Service proposed by Social Post Sharer` footer in the email modal.
			 * 
			 * @hook jsps_show_modal_footer
			 * 
		 	 * @since  2.0.0 First version
		 	 * @param  {boolean}  $is_shown=true `true` to display it, `false` to hide it.
		 	 * @return {boolean} `true` to display it, `false` to hide it, your choice 😊
			 */
			'modalEmailFooter'		=> apply_filters( 'jsps_show_modal_footer', true) ? sprintf( __( 'Service proposed by %sSocial Post Sharer%s', 'juiz-social-post-sharer' ), '<a href="https://wordpress.org/plugins/juiz-social-post-sharer/" target="_blank">', '</a>' ) : '',
			'modalClose'			=> esc_html__( 'Close', 'juiz-social-post-sharer' ),
			'modalErrorGeneric'		=> esc_html__( 'Sorry. It looks like we\'ve got an error on our side.', 'juiz-social-post-sharer' )
		) );
	}
}

if ( ! function_exists( 'juiz_sps_style_and_script' ) ) {

	/**
	 * Enqueue & Dequeue scripts and styles needed
	 * depending on the theme and options selected.
	 *
	 * @return void
	 *
	 * @since   2.0.0 Revamp the core/custom skins.
	 * @since   1.0
	 * @author  Geoffrey Crofte
	 */
	function juiz_sps_style_and_script() {

		$juiz_sps_options = jsps_get_option();

		if ( is_array( $juiz_sps_options ) ) {

			$prefix = ( defined('WP_DEBUG') && WP_DEBUG === true ) ? '' : '.min';

			/**
			 * Adds to the `wp_enqueue_style()` the JSPS CSS. 
			 * 
			 * @hook juiz_sps_use_default_css
			 * 
		 	 * @since  1.3.3.7 First version
		 	 * @param  {boolean}  $is_used=true `true` to use the CSS, `false` remove it.
		 	 * @return {boolean} `true` to use the CSS, `false` to remove it, and use CSS in your own way. Know that your can create a folder `juiz-sps/` in your WP Theme to add the button's skin into the available skins within the administration. See the tutorial "{@tutorial create-buttons-skin}"
		 	 * @tutorial create-buttons-skin
			 */
			if ( isset( $juiz_sps_options['juiz_sps_style'] ) && apply_filters( 'juiz_sps_use_default_css', true ) ) {

				$core_skins   = jsps_get_core_skins();
				$custom_skins = jsps_get_custom_skins();
				$all_skins    = $core_skins + $custom_skins;
				$current_slug  = $juiz_sps_options['juiz_sps_style'];

				$css_file = isset( $all_skins[ $current_slug ]['css_url'] ) ? $all_skins[ $current_slug ]['css_url'] : JUIZ_SPS_SKINS_FOLDER . $current_slug . '/style' . $prefix . '.css';

				// The CSS file for theme.
				wp_enqueue_style( 'juiz_sps_styles', $css_file, false, JUIZ_SPS_VERSION, 'all' );

				// The CSS file for modal.
				wp_enqueue_style( 'juiz_sps_modal_styles', JUIZ_SPS_PLUGIN_ASSETS . 'css/' . JUIZ_SPS_SLUG . '-modal' . $prefix . '.css', false, JUIZ_SPS_VERSION, 'all' );
			}

			// JS To Add to queue.
			if (
				( is_numeric ( $juiz_sps_options['juiz_sps_counter'] ) && $juiz_sps_options['juiz_sps_counter'] == 1 )
				||
				( isset( $juiz_sps_options['juiz_sps_networks']['print'] ) && isset( $juiz_sps_options['juiz_sps_networks']['print'][0] ) && $juiz_sps_options['juiz_sps_networks']['print'][0] === 1 )
				||
				( isset( $juiz_sps_options['juiz_sps_networks']['bookmark'] ) && isset( $juiz_sps_options['juiz_sps_networks']['bookmark'][0] ) && $juiz_sps_options['juiz_sps_networks']['bookmark'][0] === 1 )
				||
				( isset( $juiz_sps_options['juiz_sps_networks']['mail'] ) && isset( $juiz_sps_options['juiz_sps_networks']['mail'][0] ) && $juiz_sps_options['juiz_sps_networks']['mail'][0] === 1 )

				// version 2.0.0 new format
				||
				( isset( $juiz_sps_options['juiz_sps_networks']['print'] ) && isset( $juiz_sps_options['juiz_sps_networks']['print']['visible'] ) && $juiz_sps_options['juiz_sps_networks']['print']['visible'] === 1 )
				||
				( isset( $juiz_sps_options['juiz_sps_networks']['bookmark'] ) && isset( $juiz_sps_options['juiz_sps_networks']['bookmark']['visible'] ) && $juiz_sps_options['juiz_sps_networks']['bookmark']['visible'] === 1 )
				||
				( isset( $juiz_sps_options['juiz_sps_networks']['mail'] ) && isset( $juiz_sps_options['juiz_sps_networks']['mail']['visible'] ) && $juiz_sps_options['juiz_sps_networks']['mail']['visible'] === 1 )
			) {
				jsps_enqueue_scripts();
			}
		}
	}
	add_action( 'wp_enqueue_scripts', 'juiz_sps_style_and_script');
}
