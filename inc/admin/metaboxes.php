<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Section for Metabox
 */
if ( ! function_exists( 'juiz_sps_metaboxes' ) ) {
	/**
	 * Add Metaboxes to allow user to choose to display buttons on certain post.
	 * 
	 * @return void
	 * @author Geoffrey Crofte
	 * @since  1.3.0
	 */
	function juiz_sps_metaboxes(){
		$options = jsps_get_option();
		$pts	 = get_post_types( array( 'public'=> true, 'show_ui' => true, '_builtin' => true ) );
		$cpts	 = get_post_types( array( 'public'=> true, 'show_ui' => true, '_builtin' => false ) );

		if ( is_array( $options['juiz_sps_display_in_types'] ) ) {
			foreach ( $pts as $pt ) {
				if ( in_array( $pt, $options['juiz_sps_display_in_types'] ) ) {
					add_meta_box( 'jsps_hide_buttons', __( 'Sharing buttons', 'juiz-social-post-sharer' ), 'jsps_hide_buttons_f', $pt, 'side', 'default' );
				}
			}
			foreach ( $cpts as $cpt ) {
				if ( in_array( $cpt, $options['juiz_sps_display_in_types'] ) ) {
					add_meta_box( 'jsps_hide_buttons', __( 'Sharing buttons', 'juiz-social-post-sharer' ), 'jsps_hide_buttons_f', $cpt, 'side', 'default' );
				}
			}
		}
	}
	add_action( 'add_meta_boxes', 'juiz_sps_metaboxes' );
}
// build the metabox
if ( ! function_exists( 'jsps_hide_buttons_f' ) ) {
	function jsps_hide_buttons_f( $post ){
		$checked = ( get_post_meta( $post->ID, '_jsps_metabox_hide_buttons', true) == 'on') ? ' checked="checked"' : '';
		echo '<input id="jsps_metabox_hide_buttons" type="checkbox"' . $checked . ' name="jsps_metabox_hide_buttons" /> <label for="jsps_metabox_hide_buttons">' . __('Hide sharing buttons for this post.', 'juiz-social-post-sharer' ) . '</label>';
	}
}
// save datas
if ( ! function_exists( 'jsps_save_metabox' ) ) {
	add_action( 'save_post', 'jsps_save_metabox' );
	function jsps_save_metabox( $post_ID ) {
		$data = isset( $_POST['jsps_metabox_hide_buttons'] ) ? 'on' : 'off';
		update_post_meta( $post_ID, '_jsps_metabox_hide_buttons', $data );
	}
}
