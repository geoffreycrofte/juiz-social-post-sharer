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

		if ( $updated ) {
			$data['message'] = esc_html__( 'New order saved!', 'juiz-social-post-sharer' ); 
			wp_send_json_success( $data );
			wp_die();
		} else {
			$data['message'] = esc_html__( 'Sorry, can’t save the order. Use the Save Changes button.', 'juiz-social-post-sharer' );
			$data['data'] = $jsps_options;
			wp_send_json_error( $data );
			wp_die();
		}

	} else {
		$data['message'] = esc_html__( 'Sorry, can’t save the order. Use the Save Changes button.', 'juiz-social-post-sharer' );
		$data['data'] = $jsps_options;
		wp_send_json_error( $data );
		wp_die();
	}
}
