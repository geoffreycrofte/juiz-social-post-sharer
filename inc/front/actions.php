<?php

defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

/**
 * Admin AJAX for Email To a Friend action
 */

add_action( 'wp_ajax_nopriv_jsps-email-friend', 'jsps_ajax_email_friend' );
add_action( 'wp_ajax_jsps-email-friend', 'jsps_ajax_email_friend' );

function jsps_ajax_email_friend() {

	if ( isset( $_GET['jsps-email-friend-nonce'] ) && wp_verify_nonce( $_GET['jsps-email-friend-nonce'], 'jsps-email-friend' ) ) {

		$post = '';

		if ( isset( $_GET['id'] ) && $post = get_post( $_GET['id'] ) ) {
			
			if ( ! is_email( $_GET['your-email'] ) ) {
				wp_send_json_error( array( 3 , esc_html__( 'Your email is invalid.', 'juiz-social-post-sharer' ) ) );
			}
			if ( ! is_email( $_GET['your-friend'] ) ) {
				wp_send_json_error( array( 4, esc_html__( 'Your friend email is invalid.', 'juiz-social-post-sharer' ) ) );
			}

			$permalink = get_permalink( $post -> ID );
			$title = esc_html( $post -> post_title );
			$message = esc_html( $_GET['message'] ) . "\n\n" . $permalink;
			
			$from = isset( $_GET['your-name'] ) && ! empty( $_GET['your-name'] ) ? $_GET['your-name'] . ' <' . $_GET['your-email'] . '>' : $_GET['your-email'];

			$headers = array(
				'From:' + $from,
				//'Bcc:' will be multiple in BCC, later…
			);

			$email_sent = wp_mail( $_GET['your-friend'], $title, $message, $headers );

			if ( $email_sent ) {
				// update share meta
				$nb = (int) get_post_meta( $post -> ID, '_jsps_email_shares', true );
				update_post_meta( $post -> ID, '_jsps_email_shares', ++$nb );

				// sent successful result
				wp_send_json_success( esc_html__( 'Message successfully sent!', 'juiz-social-post-sharer' ) );
			}
			else {
				wp_send_json_error( array( 5, esc_html__( 'Error trying to send your message. Sorry for that.', 'juiz-social-post-sharer' ) ) );
			}
		}
		else {
			wp_send_json_error( array( 2, esc_html__( 'Bad Post ID received.', 'juiz-social-post-sharer' ) ) );
		}
	}
	else {
		wp_send_json_error( array( 1, esc_html__( 'You take to long to write this message. Please retry.', 'juiz-social-post-sharer' ) ) );
	}
}