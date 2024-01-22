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
			array(),
			JUIZ_SPS_VERSION,
			true
		);

		// Localize the main script.
		wp_localize_script( 'juiz_sps_scripts', 'jsps', array(
			'modalLoader'			=> '<img src="' . JUIZ_SPS_PLUGIN_ASSETS . 'img/loader.svg" height="22" width="22" alt="">',
			'modalEmailTitle'		=> esc_html__( 'Share by email', 'juiz-social-post-sharer' ),
			'modalEmailInfo'		=> esc_html__( 'Promise, emails are not stored!', 'juiz-social-post-sharer' ),
			'modalEmailNonce'		=> wp_create_nonce('jsps-email-friend'),
			'clickCountNonce'		=> wp_create_nonce('jsps-click-count'),
			'getCountersNonce'		=> wp_create_nonce('jsps-get-counters'),
			'ajax_url'              => admin_url( 'admin-ajax.php' ),
			'modalEmailName'		=> esc_html__( 'Your name', 'juiz-social-post-sharer' ),
			'modalEmailAction'		=> admin_url( 'admin-ajax.php' ),
			'modalEmailYourEmail'	=> esc_html__( 'Your email', 'juiz-social-post-sharer' ),
			'modalEmailFriendEmail'	=> esc_html__( 'Recipient\'s email', 'juiz-social-post-sharer' ),
			'modalEmailMessage'		=> esc_html__( 'Personal message', 'juiz-social-post-sharer' ),
			'modalEmailOptional'		=> esc_html__( 'optional', 'juiz-social-post-sharer' ),
			'modalEmailMsgInfo'		=> esc_html__( 'A link to the article is automatically added in your message.', 'juiz-social-post-sharer' ),
			'modalEmailSubmit'		=> esc_html__( 'Send this article', 'juiz-social-post-sharer' ),'modalRecipientNb'		=> esc_html__( '1 recipient', 'juiz-social-post-sharer' ),
			'modalRecipientNbs'		=> sprintf( esc_html__( '%s recipients', 'juiz-social-post-sharer' ), '{number}' ),

			/**
			 * Displays the `Service proposed by Social Post Sharer` footer in the email modal.
			 * 
			 * @hook jsps_show_modal_footer
			 * 
		 	 * @since  2.0.0 First version
		 	 * @param  {boolean}  $is_shown=true `true` to display it, `false` to hide it.
		 	 * @return {boolean} `true` to display it, `false` to hide it, your choice 😊
			 */
			'modalEmailFooter'		=> apply_filters( 'jsps_show_modal_footer', true) ? sprintf( __( 'Free service by %sNobs • Share Buttons%s', 'juiz-social-post-sharer' ), '<a href="https://wordpress.org/plugins/juiz-social-post-sharer/" target="_blank">', '</a>' ) : '',
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
	 * @since  2.2.0 New action using it for Gutenberg styling
	 * @since  2.0.0 Revamp the core/custom skins.
	 * @since  1.0
	 * @author Geoffrey Crofte
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
			// Also deactivate buttons' CSS if option juiz_sps_css_files equal buttons or both
			if ( isset( $juiz_sps_options['juiz_sps_style'] ) && apply_filters( 'juiz_sps_use_default_css', true ) && ! ( isset( $juiz_sps_options['juiz_sps_css_files'] ) && in_array( $juiz_sps_options['juiz_sps_css_files'], array( 'both', 'buttons' ) ) ) ) {

				$core_skins    = jsps_get_core_skins();
				$custom_skins  = jsps_get_custom_skins();
				$all_skins     = $core_skins + $custom_skins;
				$current_slug  = $juiz_sps_options['juiz_sps_style'];

				$css_file = isset( $all_skins[ $current_slug ]['css_url'] ) ? $all_skins[ $current_slug ]['css_url'] : JUIZ_SPS_SKINS_FOLDER . $current_slug . '/style' . $prefix . '.css';

				// The CSS file for theme.
				wp_enqueue_style( 'juiz_sps_styles', $css_file, false, JUIZ_SPS_VERSION, 'all' );
			}

			if (
				( isset( $juiz_sps_options['juiz_sps_networks']['mail'] ) && isset( $juiz_sps_options['juiz_sps_networks']['mail'][0] ) && $juiz_sps_options['juiz_sps_networks']['mail'][0] === 1 ) 
				||
				( isset( $juiz_sps_options['juiz_sps_networks']['mail'] ) && isset( $juiz_sps_options['juiz_sps_networks']['mail']['visible'] ) && $juiz_sps_options['juiz_sps_networks']['mail']['visible'] === 1 )
			) {
				/**
				 * Adds to the `wp_enqueue_style()` the JSPS Modal CSS. 
				 * 
				 * @hook juiz_sps_use_modal_css
				 * 
			 	 * @since  2.0.0
			 	 * @param  {boolean}  $is_used=true `true` to use the CSS, `false` remove it.
			 	 * @return {boolean} `true` to use the CSS, `false` to remove it, and use CSS in your own way.
				 */
				// Also deactivate modal CSS if option juiz_sps_css_files equal modal or both
				if ( apply_filters( 'juiz_sps_use_modal_css', true ) && ! ( isset( $juiz_sps_options['juiz_sps_css_files'] ) && in_array( $juiz_sps_options['juiz_sps_css_files'], array( 'both', 'modal' ) ) ) ) {
					wp_enqueue_style( 'juiz_sps_modal_styles', JUIZ_SPS_PLUGIN_ASSETS . 'css/' . JUIZ_SPS_SLUG . '-modal' . $prefix . '.css', false, JUIZ_SPS_VERSION, 'all' );
				}

			}

			// JS To Add to queue.
			/**
			 * Adds the Nobs JS file to the `wp_enqueue_script()`.
			 * Be careful, removing this file will block counters, counting, and Share API Button won't work anymore. You won't be able to retrieve past sharings later. 
			 * 
			 * @hook juiz_sps_use_main_js
			 * 
		 	 * @since  2.0.0
		 	 * @param  {boolean}  $is_used=true `true` to use the JS file, `false` remove it.
		 	 * @return {boolean} `true` to use the JS, `false` to remove it.
			 */
			if ( apply_filters( 'juiz_sps_use_main_js', true ) ) {
				jsps_enqueue_scripts();
			}
		}
	}
	add_action( 'wp_enqueue_scripts', 'juiz_sps_style_and_script');
	add_action( 'enqueue_block_editor_assets', 'juiz_sps_style_and_script' );
}

if ( ! function_exists( 'juiz_sps_defer_non_critical_css' ) ) {
	/**
	 * Changes the LINK HTML tags to make it load async (deferred)
	 *
	 * @param  string $tag    The complete HTML LINK element.
	 * @param  string $handle The unique ID of the CSS.
	 * @param  string $href   The URL of the CSS.
	 * @param  string $media  The original media attribute value.
	 * @return string         The new LINK + NOSCRIPT TAG.
	 */
	function juiz_sps_defer_non_critical_css( $tag, $handle, $href, $media ) {
		if ( $handle === 'juiz_sps_modal_styles' ) {
			// Replace media value by media print and onload attributes.
			$tag = str_replace( 'media=\''. $media .'\'', 'media="print" onload="this.onload=null;this.media=\'all\'"', $tag );

			// Adds a noscript tags for fallback.
			$tag = str_replace( '>', '><noscript><link rel="stylesheet" media="' . $media . '" href="' . $href . '"></noscript>', $tag );
		}
		return $tag;
	}
	add_filter( 'style_loader_tag', 'juiz_sps_defer_non_critical_css', 10, 4);
}

if ( ! function_exists( 'juiz_sps_asycn_javascript' ) ) {
	/**
	 * Adds (Async too buggy) Defer Attributes to load scripts later and optimize performance.
	 *
	 * @param  string $tag    The complete HTML Script element.
	 * @param  string $handle The unique ID of the script.
	 * @param  string $src    The URL of the script.
	 * 
	 * @return string         The modified HTML Script.
	 * @author Geoffrey Crofte
	 * @since  2.0.0
	 */
	function juiz_sps_asycn_javascript($tag, $handle, $src) {
		if ( $handle === 'juiz_sps_scripts' ) {
			/*if ( false === stripos( $tag, 'async' ) ) {
				$tag = str_replace(' src', ' async="async" src', $tag);
			}*/
			if ( false === stripos( $tag, 'defer' ) ) {	
				$tag = str_replace('<script ', '<script defer ', $tag);
			}
		}
		return $tag;
	}
	add_filter('script_loader_tag', 'juiz_sps_asycn_javascript', 10, 3);
}
