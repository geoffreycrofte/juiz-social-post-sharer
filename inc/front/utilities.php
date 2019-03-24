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
