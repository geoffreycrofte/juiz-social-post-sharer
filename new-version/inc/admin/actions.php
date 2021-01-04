<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Action on Network ordering in theming your buttons bar.
 *
 * @return void
 * @since  2.0.0
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

/**
 * Action on Notice update removal.
 *
 * @return void
 * @since  2.0.0
 * @author Geoffrey Crofte
 */
add_action( 'wp_ajax_juiz_sps_notice_removal', 'juiz_sps_AJAX_notice_removal' );
add_action( 'wp_ajax_nopriv_juiz_sps_notice_removal', 'juiz_sps_AJAX_notice_removal' );

function juiz_sps_AJAX_notice_removal() {
	if ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'juiz_sps_notice_removal' ) && isset( $_POST['notice_id'] ) ) {

		$jsps_user_options = jsps_get_user_options();
		$key = 'notice-' . esc_html( $_POST['notice_id'] );

		if ( is_array( $jsps_user_options ) ) {
			$jsps_user_options[ $key ] = 'removed';	
		} else {
			$jsps_user_options = array( $key => 'removed' );
		}

		$updated_options = jsps_update_user_options( $jsps_user_options );

		if ( $updated_options ) {
			$data['message'] = esc_html__( 'Notice Removed', 'juiz-social-post-sharer' ); 
			wp_send_json_success( $data );
			wp_die();
		} else {
			$data['message'] = esc_html__( 'Sorry, can’t save the notice removal. Try again?', 'juiz-social-post-sharer' );
			$data['data'] = $jsps_user_options;
			wp_send_json_error( $data );
			wp_die();
		}

	} else {
		$data['message'] = esc_html__( 'Your session has expired. Reload the page and try again please :)', 'juiz-social-post-sharer' );
		$data['data'] = $jsps_user_options;
		wp_send_json_error( $data );
		wp_die();
	}
}

/**
 * Action on Counter Recovery
 * TODO: write this part and the JS part too.
 * @return void
 * @since  2.0.0
 * @author Geoffrey Crofte
 */
add_action( 'wp_ajax_juiz_sps_count_recovery', 'juiz_sps_AJAX_count_recovery' );
add_action( 'wp_ajax_nopriv_juiz_sps_count_recovery', 'juiz_sps_AJAX_count_recovery' );

function juiz_sps_AJAX_count_recovery() {
	if ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'juiz_sps_count_recovery' ) && isset( $_POST['post_id'] ) ) {

		$url = get_permalink( (int) $_POST['post_id'] );

		// TODO :
		// With HTTP and HTTPS to full recovery
		$facebook = 'https://www.shareaholic.net/api/share_counts/v1/counts?api_key=ca08ce11cc98198581a18ece230ab4e2&ttl=1&service=facebook&url=' . urlencode( $_POST['url'] ). '&_=1609703469341';
		$pinterest = 'https://www.shareaholic.net/api/share_counts/v1/counts?api_key=ca08ce11cc98198581a18ece230ab4e2&ttl=1&service=pinterest&url=' . urlencode( $_POST['url'] ). '&_=1609703469341';
		$reddit = 'https://www.shareaholic.net/api/share_counts/v1/counts?api_key=ca08ce11cc98198581a18ece230ab4e2&ttl=1&service=reddit&url=' . urlencode( $_POST['url'] ). '&_=1609703469341';
		$buffer = 'https://www.shareaholic.net/api/share_counts/v1/counts?api_key=ca08ce11cc98198581a18ece230ab4e2&ttl=1&service=buffer&url=' . urlencode( $_POST['url'] ). '&_=1609703469341';


		// Global, but seems to be only for ShareThis users.
		// Maybe propose this as recovery.
		// http://count-server.sharethis.com/v2.0/get_counts?url=https://localhost

		//Headers: TE=Trailers
		// Update post

		$data['message'] = esc_html__( 'Notice Removed', 'juiz-social-post-sharer' ); 
		wp_send_json_success( $data );
		wp_die();

	} else {
		$data['message'] = esc_html__( 'Your session has expired. Reload the page and try again please :)', 'juiz-social-post-sharer' );
		$data['data'] = $jsps_user_options;
		wp_send_json_error( $data );
		wp_die();
	}
}

/**
 * Action on Skin Loading Action.
 *
 * @return void
 * @since  2.0.0
 * @author Geoffrey Crofte
 */
add_action( 'wp_ajax_juiz_sps_skin_loading', 'juiz_sps_AJAX_skin_loading' );
add_action( 'wp_ajax_nopriv_juiz_sps_skin_loading', 'juiz_sps_AJAX_skin_loading' );

function juiz_sps_AJAX_skin_loading() {
	if ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'juiz_sps_skin_loading' ) ) {

		// If we have something in cache, get it.
		if ( $markup = get_transient( JUIZ_SPS_SLUG . '-skin-shop-markup' ) ) {
			$data['message'] = esc_html__( 'Success', 'juiz-social-post-sharer' ); 
			$data['data'] = 'from-transient';
			$data['html'] = $markup;
			wp_send_json_success( $data );
			wp_die();
		}

		sleep(1); // Just in case…

		$api_url = 'https://sharebuttons.social/api/license-manager/v1';
		$license_manager = new Nobs_License_Manager_Client( array( 'api_url' => $api_url ) );
		$list = $license_manager->get_all_products();
		
		// If API Call fail.
		if ( $list === false ) {
			$data['message'] = esc_html__( 'The call to our last skins failed, sorry for that. It’s temporary, please come back later.', 'juiz-social-post-sharer' );
			wp_send_json_error( $data );
			wp_die();
		}

		// Build HTML from it.
		$markup = '';

		if ( is_array( $list ) && ! empty( $list ) ) {
			$markup .= '<ul class="jsps-skin-list">';

			foreach ( $list as $k => $v ) {
				$markup .= '
					<li id="theme-' . $k . '" style="--bg: url(' . $v -> banner_low . '); --bg2x: url(' . $v -> banner_high . ');">
						<article>
							<header role="banner" class="jsps-skin-item-banner"></header>
							<div class="jsps-skin-item-content">
								<h1 class="jsps-skin-item-name">' . $v -> name . '</h1>
								<p class="jsps-skin-item-excerpt">' . $v -> short_desc . '</p>
								<div class="jsps-skin-item-description jsps-hidden" aria-hidden="true">' . $v -> description . '</div>
							</div>
							<div class="jsps-skin-item-actions">
								<button disabled type="button" class="button jsps-button" data-version="' . $v -> version . '" data-tested="' . $v -> tested . '" data-url="' . $v -> description_url . '" data-price="' . $v -> price .'">' . esc_html__( 'Available Soon', 'juiz-social-post-sharer' ) . '</button>
							</div>
						</article>
					</li>';
			}

			$markup .= '</ul>
			<p class="jsps-more-to-come">' . esc_html__( 'and way more to come 😜', 'juiz-social-post-sharer' ) . '</p>';
		}

		// Store it into a transient.
		set_transient( JUIZ_SPS_SLUG . '-skin-shop-markup', $markup,  60*60*24*2 ); // 2 days

		//if ( $updated_options ) {
		$data['message'] = esc_html__( 'Success', 'juiz-social-post-sharer' ); 
		$data['data'] = $list;
		$data['html'] = $markup;
		wp_send_json_success( $data );
		wp_die();

	} else {
		$data['message'] = esc_html__( 'Your session has expired. Reload the page and try again please :)', 'juiz-social-post-sharer' );
		wp_send_json_error( $data );
		wp_die();
	}
}
