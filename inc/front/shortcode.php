<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

if ( ! function_exists( 'sc_4_jsps' ) ) {

	/**
	 * Declare the JSPS Shortcode.
	 *
	 * @param (array) $atts The attributes of the shortcode.
	 * @return (void)
	 *
	 * @since  1.0
	 * @author Geoffrey Crofte
	 */
	function sc_4_jsps( $atts ) {
		
		$atts = shortcode_atts( array(
			'buttons' 	=> 'facebook,twitter,mail,google,stumbleupon,linkedin,pinterest,viadeo,digg,weibo',
			'counters'	=> 0,
			'current'	=> 1,
			'url'		=> NULL
		), $atts );
		
		// Buttons become array ("digg,mail", "digg ,mail", "digg, mail", "digg , mail", are right syntaxes)
		$jsps_networks 		= preg_split( '#[\s+,\s+]#', $atts['buttons'] );
		$jsps_counters 		= intval( $atts['counters'] );
		$jsps_current_page 	= intval( $atts['current'] );
		$jsps_url 			= $atts['url'];

		if ( $jsps_current_page == 1 ) {
			jsps_enqueue_scripts();
		}
		
		ob_start();
		juiz_sps( $jsps_networks, $jsps_counters, $jsps_current_page, 1, $jsps_url ); //do an echo
		$jsps_sc_output = ob_get_contents();
		ob_end_clean();
		

		return apply_filters( 'juiz_sps_should_display_shortcode', $jsps_sc_output, $atts );
	}

	add_shortcode( 'juiz_social','sc_4_jsps' );
	add_shortcode( 'juiz_sps'   ,'sc_4_jsps' );

}
