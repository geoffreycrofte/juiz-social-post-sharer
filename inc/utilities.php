<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Get the real current page URL.
 *
 * @param (string) $mode The mode of the URL.
 * @return (string) The current page URL.
 *
 * @since 1.5
 * @author  BoiteAWeb.fr, Geoffrey Crofte
 * @see http://boiteaweb.fr/sf_get_current_url-obtenir-url-courante-fonction-semaine-5-6765.html
 */
if ( ! function_exists( 'juiz_sf_get_current_url' ) ) {
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
	function juiz_sps_get_ordered_networks( $order, $networks ) {
		$order = isset( $order ) && is_array( $order ) ? $order : null;

		// Sort networks by user choice order.
		// @see: https://stackoverflow.com/questions/348410/sort-an-array-by-keys-based-on-another-array/9098675#9098675
		return $order !== null ? array_replace( array_flip( $order ), $networks ) : $networks;
	}
}

if ( ! function_exists('juiz_sps_get_notification_markup') ) {
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
	function juiz_sps_get_skin_css_name() {
		return 'styles.css';
	}
}

if ( ! function_exists('juiz_sps_get_skin_img_name') ) {
	function juiz_sps_get_skin_img_name() {
		return 'screenshot.png';
	}
}

if ( ! function_exists('juiz_sps_remove_old_networks') ) {
	function juiz_sps_remove_old_networks( $networks ) {
		unset( $networks['delicious']   );
		unset( $networks['digg']        );
		unset( $networks['stumbleupon'] );
		unset( $networks['google']      );

		return $networks;
	}
}

if ( ! function_exists( 'jsps_get_all_registered_networks' ) ) {
	function jsps_get_all_registered_networks() {
		$custom_networks = jsps_get_custom_networks();
		$core_networks   = jsps_get_core_networks();

		return array_merge( $custom_networks, $core_networks );
	}
}
