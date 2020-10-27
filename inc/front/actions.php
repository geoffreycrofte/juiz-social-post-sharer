<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Admin AJAX for Email To a Friend action.
 *
 * @since  2.0.0
 * @author Geoffrey Crofte
 */

add_action( 'wp_ajax_jsps-email-friend', 'jsps_ajax_email_friend' );
add_action( 'wp_ajax_nopriv_jsps-email-friend', 'jsps_ajax_email_friend' );

function jsps_ajax_email_friend() {

	if ( isset( $_GET['jsps-email-friend-nonce'] ) && wp_verify_nonce( $_GET['jsps-email-friend-nonce'], 'jsps-email-friend' ) ) {

		$post = '';

		if ( isset( $_GET['id'] ) && $post = get_post( $_GET['id'] ) ) {
			
			if ( ! is_email( $_GET['email'] ) ) {
				wp_send_json_error( array( 3 , esc_html__( 'Your email address is invalid.', 'juiz-social-post-sharer' ) ) );
			}

			if ( ! isset( $_GET['friend'] ) ) {
				wp_send_json_error( array( 6, esc_html__( 'No recipient email address.', 'juiz-social-post-sharer' ) ) );
			}

			// check is multiple recipients
			$recipients = false;
			if ( preg_match( '#([,;/n])#', $_GET['friend'], $matches ) ) {
				$recipients = explode( $matches[0], $_GET['friend'] );
				$newrecips = array();

				foreach ( $recipients as $recip ) {
					$newrecips[] = sanitize_email( $recip );
				}

				$recipients = $newrecips;
			}

			if ( $recipients === false && ! is_email( $_GET['friend'] ) ) {
				wp_send_json_error( array( 4, esc_html__( 'The recipient email address is invalid.', 'juiz-social-post-sharer' ) ) );
			}

			$permalink = get_permalink( $post -> ID );
			$title     = esc_html( $post -> post_title );
			$message   = esc_html( $_GET['message'] ) . "\n\n" . $permalink;
			$from      = isset( $_GET['name'] ) && ! empty( $_GET['name'] ) ? sanitize_text_field( $_GET['name'] ) . ' <' . sanitize_email( $_GET['email'] ) . '>' : sanitize_email( $_GET['email'] );
			$headers   = array( 'From' => $from );

			if ( $recipients !== false ) {
				$headers['Bcc'] = $recipients;
			}

			$email_sent = wp_mail( 
				$recipients !== false ? $from : sanitize_email( $_GET['friend'] ),
				$title,
				$message,
				$headers
			);

			if ( $email_sent ) {
				// update share meta
				$nb = (int) get_post_meta( $post -> ID, '_jsps_email_shares', true );
				update_post_meta( $post -> ID, '_jsps_email_shares', ++$nb );

				// sent successful result
				wp_send_json_success( esc_html__( 'Post successfully sent!', 'juiz-social-post-sharer' ) ) ;

			} else {
				wp_send_json_error( array( 5, esc_html__( 'Error trying to send your message. Sorry for that.', 'juiz-social-post-sharer' ), $headers ) );
			}
		} else {
			wp_send_json_error( array( 2, esc_html__( 'Seems like the post ID you tried to share is not good.', 'juiz-social-post-sharer' ) ) );
		}
	} else {
		wp_send_json_error( array( 1, esc_html__( 'Your session is finished. Sorry. Retry after reloading the page.', 'juiz-social-post-sharer' ) ) );
	}
}