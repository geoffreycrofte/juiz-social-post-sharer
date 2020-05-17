<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Action on Network ordering in theming you buttons bar.
 *
 * @return void
 * @since  1.5
 * @author Geoffrey Crofte
 */

add_action( 'wp_ajax_juiz_sps_order_networks', 'juiz_sps_AJAX_order_networks' );
add_action( 'wp_ajax_nopriv_juiz_sps_order_networks', 'juiz_sps_AJAX_order_networks' );

function juiz_sps_AJAX_order_networks() {
	if ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'juiz_sps_order_networks' ) ) {
		$jsps_options = jsps_get_option();
		$jsps_options['juiz_sps_order'] = $_POST['order'];

		$updated = jsps_update_option( $jsps_options );
		$jsps_options_new = jsps_get_option();

		if ( $updated ) {
			$data['datasent'] = $jsps_options;
			$data['db'] = $jsps_options_new;
			wp_send_json_success( $data );
			wp_die();
		} else {
			wp_send_json_error( $jsps_options );
			wp_die();
		}

	} else {
		echo 'Didn’t work';
		wp_die();
	}
}
