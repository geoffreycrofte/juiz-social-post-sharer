<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}


if ( ! function_exists( 'juiz_sf_get_current_url' ) ) {
	/**
	 * Get the real current page URL.
	 *
	 * @param  (string) $mode  The mode of the URL.
	 * @return (string)        The current page URL.
	 *
	 * @since  2.0.0
	 * @author BoiteAWeb.fr, Geoffrey Crofte
	 * @see http://boiteaweb.fr/sf_get_current_url-obtenir-url-courante-fonction-semaine-5-6765.html
	 */
	function juiz_sf_get_current_url( $mode = 'base' ) {
		
		$url = 'http' . ( is_ssl() ? 's' : '' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		
		switch ( $mode ) {
			case 'raw' :
				return $url;
				break;
			case 'base' :
				return reset( explode( '?', $url ) );
				break;
			case 'uri' :
				$exp = explode( '?', $url );
				return trim( str_replace( home_url(), '', reset( $exp ) ), '/' );
				break;
			default:
				return false;
		}
	}
}

if ( ! function_exists( 'juiz_sps_get_ordered_networks') ) {
	/**
	 * Get the user-ordered list of networks.
	 *
	 * @param  (array) $networks A formated list of network (backward compatible before 2.0.0)
	 * @param  (array) $order    A list of network slugs.
	 * 
	 * @return (array)           An exploitable ordered list of networks.
	 * 
	 * @since  2.0.0
	 * @author Geoffrey Crofte
	 */
	function juiz_sps_get_ordered_networks( $networks, $order ) {
		$order = isset( $order ) && is_array( $order ) ? $order : null;

		// Sort networks by user choice order.
		// @see: https://stackoverflow.com/questions/348410/sort-an-array-by-keys-based-on-another-array/9098675#9098675
		return $order !== null ? array_replace( array_flip( $order ), $networks ) : $networks;
	}
}

if ( ! function_exists('juiz_sps_get_notification_markup') ) {
	/**
	 * Print the HTML code needed for JS notifications.
	 * 
	 * @return void
	 *
	 * @since  2.0.0
	 * @author Geoffrey Crofte
	 */
	function juiz_sps_get_notification_markup() {
		?>

		<div class="juiz-sps-notif">
			<div class="juiz-sps-notif-icon">
				<i class="dashicons" role="presentation"></i>
			</div>
			<p class="juiz-sps-notif-text"></p>
		</div>

		<?php
	}
}

if ( ! function_exists('juiz_sps_get_skin_css_name') ) {
	/**
	 * Return the name of the CSS file name for Button Skins
	 * 
	 * @return (string) The name of the file.
	 *
	 * @since  2.0.0
	 * @author Geoffrey Crofte
	 */
	function juiz_sps_get_skin_css_name() {
		return 'styles.css';
	}
}

if ( ! function_exists('juiz_sps_get_skin_img_name') ) {
	/**
	 * Return the name of the image name for Button Skins
	 * 
	 * @return (string) The name of the file.
	 *
	 * @since  2.0.0
	 * @author Geoffrey Crofte
	 */
	function juiz_sps_get_skin_img_name() {
		return 'screenshot.png';
	}
}

if ( ! function_exists('juiz_sps_remove_old_networks') ) {
	/**
	 * Return an array of networks without the old ones.
	 * 
	 * @return (array) The cleaned array
	 *
	 * @since  2.0.0
	 * @author Geoffrey Crofte
	 */
	function juiz_sps_remove_old_networks( $networks, $clean_removed = false ) {
		unset( $networks['delicious']   );
		unset( $networks['digg']        );
		unset( $networks['stumbleupon'] );
		unset( $networks['google']      );

		// Check unvalid network, oftentime the one added by a dev
		// and removed from the added theme or plugin.
		// Usually looks like $networks['buffer'] => (int) 0
		if ( $clean_removed ) {
			foreach ( $networks as $key => $value ) {
				if ( ! is_array( $value ) ) {
					unset( $networks[ $key ] );
				}
			}
		}

		return $networks;
	}
}

if ( ! function_exists( 'jsps_get_all_registered_networks' ) ) {
	/**
	 * Return a merged array of custom and core networks.
	 * 
	 * @return (array) The merged array.
	 *
	 * @since  2.0.0
	 * @author Geoffrey Crofte
	 */
	function jsps_get_all_registered_networks() {
		$custom_networks = jsps_get_custom_networks();
		$core_networks   = jsps_get_core_networks();

		return array_merge( $custom_networks, $core_networks );
	}
}

if ( ! function_exists( 'jsps_get_displayable_networks' ) ) {
	/**
	 * Get an array of exploitable ordered list of networks for front and admin purpose
	 *
	 * @param  (array) $networks A formated list of network (backward compatible before 2.0.0)
	 * @param  (array) $order    A list of network slugs.
	 * 
	 * @return (array)           An exploitable ordered list of networks.
	 * 
	 * @since  2.0.0
	 * @author Geoffrey Crofte
	 */
	function jsps_get_displayable_networks( $networks, $order ) {
		
		$core_networks   = jsps_get_core_networks();
		$custom_networks = jsps_get_custom_networks();

		// Backward compatibility / Conversion
		$reformated_old_list = array();
		if ( isset( $networks['google'] ) ) {
			foreach ( $networks as $k => $v ) {
				$reformated_old_list[ $k ] = array(
					'name' => $v[1],
					'visible' => $v[0],
				);
			}
			
			$reformated_old_list = juiz_sps_remove_old_networks( $reformated_old_list );
		}

		// Merge old list with the new registered core networks
		// To keep options of the users.
		$merged_core_networks = array_replace( $core_networks, $reformated_old_list );

		// Merge Custom and Core Networks, Core has the priority here.
		$merged_networks = array_merge( $custom_networks, $core_networks );

		// Merge Options registered by user with complete list
		if ( ! isset( $networks['google'] ) ) {
			$merged_networks = array_merge( $merged_networks, $networks );
		}

		$dnetworks = juiz_sps_get_ordered_networks( $merged_networks, $order );
		$dnetworks = juiz_sps_remove_old_networks( $dnetworks, true );

		/**
		 * Gets an ordered list of networks.
		 *
		 * This filter comes at the end of the aponymous function that insures the backward compatibility with options (before 2.0.0), merges the users options and orders the networks regarding the user's preferences.
		 * 
		 * @hook jsps_get_displayable_networks
		 * 
	 	 * @since  2.0.0 First version
	 	 * 
	 	 * @param  {array}  dnetworks              - The merges and ordered array of networks.
	 	 * @param  {array}  dnetworks.slug         - An array of data for the `slug` network.
	 	 * @param  {string} dnetworks.slug.name    - The name of the network.
	 	 * @param  {int}    dnetworks.slug.visible - Is the network visible at plugin install? (`1` for yes, `0` for no)
	 	 * @param  {string} dnetworks.slug.api_url - The API URL for custom (not core) network.
	 	 * @param  {string} dnetworks.slug.icon    - An URL to a SVG file, or a classname.
	 	 * @param  {string} dnetworks.slug.title   - The text for the link tooltip (title attribute)
	 	 * @param  {string} dnetworks.slug.color   - The main color of this network (`color` valid value)
	 	 * @param  {string} dnetworks.slug.hcolor  - The :hover color of this network (`color` valid value)
	 	 * 
	 	 * @param  {array} $networks    The array of networks before cleaning up.
	 	 * @param  {array} $order       A simple array of the network slugs (e.i `array('weibo', 'twitter')`) in the order decided by the user.
	 	 *
	 	 * 
	 	 * @return {array} The array of network ready to be used. See $dnetworks for the structure.
	 	 *
		 */
		return apply_filters( 'jsps_get_displayable_networks', $dnetworks, $networks, $order );
	}
}

if ( ! function_exists('jsps_get_excerpt') ) {
	/**
	 * Get an excerpt from the post_content
	 * @param  (object) $post   The WP Post object.
	 * @param  (int)    $count  The number of letter needed.
	 * @return (string)         The excerpt.
	 *
	 * @since  2.0.0
	 * @author Geoffrey Crofte
	 */
	function jsps_get_excerpt( $post, $count = 80 ) {

		/**
		 * **Before altering the content** and getting an excerpt from the `$post->post_content`.
	 	 * 
	 	 * @hook jsps_before_get_excerpt
		 * 
	 	 * @since  2.0.0
		 * 
	 	 * @param  {object} $post        The WP Post object.
	 	 * @param  {int=}   [$count=80]  The number of letters needed.
	 	 *
		 */
		do_action( 'jsps_before_get_excerpt', $post, $count );

		/**
		 * Filters the number of letters before truncate the excerpt.
	 	 * 
	 	 * @hook jsps_get_excerpt_letter_count
		 *
		 * @since  2.1.3 Uses post_excerpt before post_content
	 	 * @since  2.0.0
		 * 
	 	 * @param  {int} $count=80 The number of letters needed.
	 	 * @return                 The filtered number of letters.
		 */
		$count = apply_filters( 'jsps_get_excerpt_letter_count', $count );
		$text  = wordwrap( wp_strip_all_tags( $post->post_excerpt ), (int) $count, "***", true );
		$tcut  = explode("***", $text);

		/**
		 * Gets an excerpt from the `$post->post_excerpt` based on the number of letter requested (`$count`), mostly used for Share APIs that use long text.
		 * 
		 * @hook jsps_get_excerpt
		 * 
	 	 * @since  2.0.0 First version
	 	 * 
	 	 * @param  {object} $content The post_content shortened.
	 	 * @param  {int}    $count=80   The number of letters requested.
	 	 *
	 	 * @return {string}          The shortened content used as excerpt
	 	 *
		 */
		return apply_filters( 'jsps_get_excerpt', $tcut[0], $count );
	}
}

if ( ! function_exists('jsps_get_network_html_icon') ) {
	/**
	 * Get the HTML code for the icon. Used in admin and front.
	 *
	 * @param  (string)  $slug         The network shortname (e.i `twitter`).
	 * @param  (array)   $network_info The info regarding the network.
	 * @param  (boolean) $is_front     Is it used for front or admin? (default: false)
	 * @return (string)                Return void if front and not custom network. Return default admin icon `&lt;i&gt;` tag, or `&lt;svg&gt;` icon if custom network with file, return `&lt;i&gt;` with custom class if it's a custom network with class.
	 *
	 * @since  2.0.0
	 * @author Geoffrey Crofte
	 */
	function jsps_get_network_html_icon($slug, $network_info, $is_front = false) {
		
		if ( $is_front && ! isset( $network_info['icon'] ) ) return;

		$icon = '<i class="jsps-icon-' . esc_attr( $slug ) . '" role="presentation"></i>';
		
		if ( ! isset( $network_info['icon'] ) ) return $icon;
			
		if ( filter_var( $network_info['icon'], FILTER_VALIDATE_URL ) ) {
			$svg_file = file_get_contents( $network_info['icon'] );
			$svg_file = substr( $svg_file, strpos( $svg_file, '<svg' ) );

			// focusable="false" prevents double focus on SVG elements on IE.
			$icon = $is_front ? $svg_file : '<i>' . preg_replace( '#<svg#', '<svg focusable="false"', $svg_file ) . '</i>';

		} else {
			$icon = '<i class="jsps-icon-' . esc_attr( $slug ) . ' ' . esc_attr( $network_info['icon'] ) . '" role="presentation"></i>';
		}

		/**
		 * Gets a HTML of the icon used in the admin and front.
		 * It takes into account custom and core networks including SVG file linking.
		 * 
		 * @hook jsps_get_network_html_icon
		 * 
	 	 * @since  2.0.0 First version
	 	 * 
	 	 * @param  {string}  $icon             The HTML Code generated.
	 	 * @param  {string}  $slug             The Network slug.
	 	 * @param  {array}   $network_info     An array of the usual Network information.
	 	 * @param  {boolean} $is_front=false Is it an icon for front or admin?       
	 	 *
	 	 * 
	 	 * @return {string} The HTML Code generated. Return default admin icon `&lt;i&gt;` tag, or `&lt;svg&gt;` icon if custom network with file, return `&lt;i&gt;` with custom class if it's a custom network with class.
	 	 *
		 */
		return apply_filters( 'jsps_get_network_html_icon', $icon, $slug, $network_info, $is_front );
	}
}

if ( ! function_exists('jsps_get_notif') ) {
	/**
	 * Get the HTML markup for a notification (here an inline one.)
	 * 
	 * @param  string $type is-success or is-error
	 * @param  string $text The message being displayed
	 * @return string       The entire HTML markup.
	 *
	 * @since  2.0.0
	 * @author Geoffrey Crofte
	 */
	function jsps_get_notif( $type = '', $text = '' ) {
		$notif = '<div class="juiz-sps-notif is-inline ' . esc_attr( $type ) . '"><div class="juiz-sps-notif-icon">';

		$icon = 'dashicons-info-outline';
		if ( $type === 'is-error' ) {
			$icon = 'dashicons-warning';
		} elseif ($type === 'is-success' ) {
			$icon = 'dashicons-yes-alt';
		}
						
		$notif .= '<i class="dashicons ' . $icon . '" role="presentation"></i></div><p class="juiz-sps-notif-text">' . $text . '</p></div>';

		return $notif;
	}
}

if ( ! function_exists( 'jsps_render_api_link' ) ) {
	/**
	 * Format the custom network API link for front usage.
	 * %%title%%, %%excerpt%% and %%url%% variables within the API URL will be replaced by correspondig post-to-be-shared element.
	 * 
	 * @param  (array)  $infos Information about the post and network (api, url, title, desc)
	 * @return (string)        Return the formatted URL.
	 *
	 * @since  2.0.0
	 * @author Geoffrey Crofte
	 */
	function jsps_render_api_link( $infos ) {

		// Need at least the API and URL infos.
		if ( ! isset( $infos['api'], $infos['url'] ) ) return;

		// If we don't find %% within the API URL, return it like it is.
		if ( ! strpos( $infos['api'], '%%' ) ) return $infos['api'];

		// Otherwise replace %% variables.
		$url = preg_replace(
			array(
				'#%%title%%#',
				'#%%excerpt%%#',
				'#%%url%%#'
			),
			array(
				isset( $infos['title'] ) ? $infos['title'] : '',
				isset( $infos['desc'] )  ? $infos['desc']  : '',
				isset( $infos['url'] )   ? $infos['url']   : ''
			),
			$infos['api']
		);

		/**
		 * Gets a populated Share API URL from the `$infos['api']` row link. It replaces `%%title%%`, `%%excerpt%%` and `%%url%%` with the corresponding values to be shared by the network.
		 *
		 * Mostly used for a Custom Network declared.
		 * 
		 * @hook jsps_render_api_link
		 * 
	 	 * @since  2.0.0 First version
	 	 * 
	 	 * @param  {string}  $url          The URL Generated by a `preg_replace()`
	 	 * @param  {array}   infos         The array of information needed to generate the URL.
	 	 * @param  {string}  infos.title - The title of the post, text to be shared.
	 	 * @param  {string}  infos.desc  - The excerpt to be share as longtext (if available)
	 	 * @param  {string}  infos.url   - The post URL to be shared.
	 	 * @param  {string}  infos.api   - The raw URL including the `%%VAR%%`s
	 	 *
	 	 * 
	 	 * @return {string} The URL being put into the `href` attribute of a button.
	 	 *
		 */
		return apply_filters( 'jsps_render_api_link', $url, $infos );
	}
}

/**
 * Format the content to replace placeholders with the expected values.
 * 
 * @param  (string) $content The content to be formatted
 * @param  (object) $post    A WordPress Post object if necessary
 * @return (string)          The formatted content.
 * 
 * @since  2.3.2
 * @author Geoffrey Crofte
 */
function jsps_render_mail_content( $content, $post = null ) {

	/**
	 * Filters the URL to be shared on a mail content.
	 * 
	 * @hook juiz_sps_the_shared_permalink_for_mail
	 * 
 	 * @param  {string}  $url                  The shared URL.
 	 * @return {string} The URL to be shared on the specific network. You don't need to sanitize or urlencode it, the after that.
 	 *
 	 * @since  2.3.2 First version
	 */
	$url = apply_filters( 'juiz_sps_the_shared_permalink_for_mail', ( $post !== null ? get_permalink( $post -> ID ) : get_permalink() ) );

	return preg_replace(
		array(
			'#%%title%%#',
			'#%%siteurl%%#',
			'#%%permalink%%#',
			'#%%url%%#'
		), 
		array( 
			$post !== null ? get_the_title( $post -> ID ) : get_the_title(),
			get_site_url(),
			$post !== null ? get_permalink( $post -> ID ) : get_permalink(),
			urlencode( $url )
		),
		$content
	);
}

/**
 * Get the URL of Nobs plugin public website.
 * 
 * @param  string $page The inner page where to point out.
 * @param  array  $utm  The array of source, medium and campaing
 *
 * @return string The composed URL.
 * @since  2.0.0
 *
 * @author  Geoffrey Crofte
 */
function jsps_get_public_website( $page= null, $utm = array() ) {
	$url = 'https://sharebuttons.social/';
	$url = is_string( $page ) ? $url . ltrim( $page, '/' ) : $url;
	if ( ! empty( $utm ) ) {
		$url = $url . ( isset( $utm['source'] ) ? '?utm_source=' . $utm['source'] : '' );
		$url = $url . ( isset( $utm['medium'] ) ? '&amp;utm_medium=' . $utm['medium'] : '' );
		$url = $url . ( isset( $utm['campaign'] ) ? '&amp;utm_campaign=' . $utm['campaign'] : '' );
	}
	return $url;
}

function jsps_get_settings_url() {
	return get_admin_url( null, 'options-general.php?page=' . JUIZ_SPS_SLUG );
}

function jsps_get_welcome_url() {
	return get_admin_url( null, 'options-general.php?page=' . JUIZ_SPS_SLUG . '-welcome' );	
}

function jsps_get_roadmap_url() {
	return 'https://github.com/geoffreycrofte/juiz-social-post-sharer/projects/1';
}

function jsps_get_github_url() {
	return 'https://github.com/geoffreycrofte/juiz-social-post-sharer/';
}

function jsps_get_paypal_url() {

	$currency = 'EUR';

	switch ( get_locale() ) {
		case 'en_US':
			$currency = 'USD';
			break;
		case 'en_CA':
			$currency = 'CAD';
			break;
		case 'en_GB':
			$currency = 'GBP';
			break;

		default:
			$currency = 'EUR';
	} 

	return 'https://www.paypal.com/donate/?cmd=_donations&amp;business=P39NJPCWVXGDY&amp;lc=FR&amp;item_name=Nobs%20Share%20Buttons%20-%20WP%20Plugin&amp;item_number=%23wp-nobs&amp;currency_code=' . $currency . '&amp;bn=PP-DonationsBF%3abtn_donate_SM.gif%3aNonHosted';
}

function jsps_get_issue($int = null) {
	return jsps_get_github_url() . 'issues' . ( $int !== null ? '/' . (int) $int : '' );
}

/**
 * A function that return the old network array option (before v2.0.0)
 * Used for debug purpose.
 *
 * @return (array) The old array of network options
 * 
 * @since  2.0.0
 * @author Geoffrey Crofte
 */
function jsps_get_old_network_array() {
	return array(
		'delicious'		=>	array( 0, 'Delicious' ), // new 1.4.1
		'digg'			=>	array( 0, 'Digg' ),
		'facebook'		=>	array( 1, 'Facebook' ), 
		'google'		=>	array( 0, 'Google+' ),
		'linkedin'		=>	array( 0, 'LinkedIn' ),
		'pinterest'		=>	array( 0, 'Pinterest' ),
		'reddit'		=>	array( 0, 'Reddit' ), // new 1.4.1
		'stumbleupon'	=>	array( 0, 'StumbleUpon' ),
		'twitter'		=>	array( 1, 'Twitter' ), 
		'tumblr'		=>	array( 0, 'Tumblr' ), // new 1.4.1
		'viadeo'		=>	array( 0, 'Viadeo' ),
		'vk'			=>	array( 0, 'VKontakte' ), // new 1.3.0
		'weibo'			=>	array( 0, 'Weibo' ), // new 1.2.0
		'mail'			=>	array( 1, 'Email' ),
		'bookmark'		=>	array( 0, 'Bookmark' ), // new 1.4.2
		'print'			=>	array( 0, 'Print' ) // new 1.4.2
	);
}

/**
 * Return the initial settings for the plugin before the 2.0.0.
 * Used for debug purpose.
 *
 * @since 1.4.11
 * @since 2.0.0 Uses the jsps_get_old_network_array() function.
 * @return (array) 
 */
function jsps_get_initial_old_settings() {
	$default_settings = array(
		'juiz_sps_version' 			=> JUIZ_SPS_VERSION,
		'juiz_sps_style' 			=> 7,
		'juiz_sps_networks' 		=> jsps_get_old_network_array(),
		'juiz_sps_counter'			=> 0,
		'juiz_sps_counter_option'	=> 'both', // new 1.3.3.7
		'juiz_sps_hide_social_name' => 0,
		'juiz_sps_target_link'		=> 0, // new 1.1.0
		'juiz_sps_twitter_user'		=> 'CreativeJuiz',
		'juiz_sps_display_in_types' => array( 'post' ),
		'juiz_sps_display_where'	=> 'bottom',
		'juiz_sps_write_css_in_html'=> 0,
		'juiz_sps_mail_subject'		=> __( 'Visit this link found on %%siteurl%%', 'juiz-social-post-sharer'),
		'juiz_sps_mail_body'		=> __( 'Hi, I found this information for you : "%%title%%"! This is the direct link: %%permalink%% Have a nice day :)', 'juiz-social-post-sharer' ),
		'juiz_sps_force_pinterest_snif' => 0,
		'juiz_sps_colors' 			=> array(
			'bg_color'	=> '', 
			'txt_color' => ''
		)
	);

	return $default_settings;
}

/**
 * A utility to remove existing options for Nobs plugin.
 * Used for debugging purpose, or support purpose.
 * (in options-general.php?page=juiz-social-post-sharer add &action=reset-options)
 *
 * @since 2.0.0
 */
function jsps_delete_plugin_options() {
	delete_option( 'juiz_SPS_settings' );
	delete_site_option( 'juiz_SPS_settings' );
}

/**
 * War Dump mode
 */
if ( ! function_exists( 'war_dump' ) ) {
	function war_dump( $content ) {
		echo '<pre>';
		var_dump( $content );
		echo '</pre>';
	}
}
