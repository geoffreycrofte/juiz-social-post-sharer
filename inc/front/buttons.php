<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

if ( ! function_exists( 'get_juiz_sps' ) ) {

	/**
	 * Get the HTML for buttons to share on Networks.
	 *
	 * @param  array   $networks            This list of networks/buttons.
	 * @param  integer $counters            Should counter be displayed?
	 * @param  integer $is_current_page_url Is the current page or custom to share?
	 * @param  integer $is_shortcode        Is the shortcode calling this function?
	 * @param  string  $url_to_share        'permalink' | 'siteurl' | <custom_url>
	 * @return string                       Return the markup for the buttons.
	 *
	 * @author Geoffrey Crofte
	 * @since  1.0
	 */
	function get_juiz_sps( $networks = array(), $counters = 0, $is_current_page_url = 0, $is_shortcode = 0, $url_to_share = NULL ) {
		global $post;

		$show_me = ( get_post_meta( $post->ID, '_jsps_metabox_hide_buttons', true) == 'on' ) ? false : true;
		$show_me = $is_shortcode ? true : $show_me;

		// Show buttons only if post meta doesn't ask to hide it and if it's not a shortcode.
		if ( ! $show_me ) {
			return;
		}

		// URL requested by user can be custom, permalink or siteurl (or null)
		$url_needed_by_user = ( $url_to_share != NULL ) ? $url_to_share : false;

		if ( in_array( $url_needed_by_user, array( 'permalink', 'siteurl' ) ) ) {
			switch( $url_needed_by_user ) {
				case 'permalink' :
					$url_needed_by_user = get_permalink();
					break;
				case 'siteurl' :
					$url_needed_by_user = get_bloginfo('url');
					break;
			}
		} else {
			$url_needed_by_user = esc_url( $url_needed_by_user );
		}

		// Texts, URL and Image to share
		$text = wp_strip_all_tags( esc_attr( rawurlencode( $post->post_title ) ) );
		$url  = $post ? get_permalink() : juiz_sf_get_current_url( 'raw' );
		$url  = ( $url_needed_by_user == false ) ? $url : $url_needed_by_user;

		if ( $is_current_page_url &&  $url_needed_by_user == false ) {
			$url = juiz_sf_get_current_url( 'raw' );
		}

		$url = apply_filters( 'juiz_sps_the_shared_permalink', $url );
		$image = has_post_thumbnail( $post->ID ) ? wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ) : '';

		// Some text filters.
		$total_word = apply_filters( 'juiz_sps_total_count_word', __( 'Total: ', 'juiz-social-post-sharer' ) );

		// Some markup filters.
		$hide_intro_phrase 			= apply_filters( 'juiz_sps_network_name', false );
		$share_the_post_sentence 	= apply_filters( 'juiz_sps_intro_phrase_text', __('Share the post', 'juiz-social-post-sharer') );
		$before_the_sps_content 	= apply_filters( 'juiz_sps_before_the_sps', '' );
		$after_the_sps_content 		= apply_filters( 'juiz_sps_after_the_sps', '' );
		$before_the_list 			= apply_filters( 'juiz_sps_before_the_list', '' );
		$after_the_list 			= apply_filters( 'juiz_sps_after_the_list', '' );
		$before_first_i 			= apply_filters( 'juiz_sps_before_first_item', '' );
		$after_last_i 				= apply_filters( 'juiz_sps_after_last_item', '' );
		$container_classes 			= apply_filters( 'juiz_sps_container_classes', '' );
		$rel_nofollow 				= apply_filters( 'juiz_sps_links_nofollow', 'rel="nofollow"' );

		// Markup filters.
		$div = apply_filters( 'juiz_sps_container_tag', 'div' );
		$p 	 = apply_filters( 'juiz_sps_phrase_tag', 'p' );
		$ul  = apply_filters( 'juiz_sps_list_container_tag', 'ul' ); 
		$li  = apply_filters( 'juiz_sps_list_of_item_tag', 'li' );


		// Get the plugin options.
		$juiz_sps_options = jsps_get_option();

		// Classes and attributes options.
		$juiz_sps_target_link = ( isset( $juiz_sps_options['juiz_sps_target_link'] ) && $juiz_sps_options['juiz_sps_target_link'] == 1 ) ? ' target="_blank"' : '';
		$juiz_sps_hidden_name_class = ( isset( $juiz_sps_options['juiz_sps_hide_social_name'] ) && $juiz_sps_options['juiz_sps_hide_social_name'] == 1 ) ? ' juiz_sps_hide_name' : '';
		$container_classes .= ( intval( $counters ) == 1 ) ? ' juiz_sps_counters' : '';
		$container_classes .= ( isset( $juiz_sps_options['juiz_sps_counter_option'] ) ) ? ' counters_' . $juiz_sps_options['juiz_sps_counter_option'] : ' counters_both';

		// Other options.
		$juiz_sps_display_where = isset( $juiz_sps_options['juiz_sps_display_where'] ) ? $juiz_sps_options['juiz_sps_display_where'] : '';
		$force_pinterest_snif = isset( $juiz_sps_options['juiz_sps_force_pinterest_snif'] ) ? intval( $juiz_sps_options['juiz_sps_force_pinterest_snif'] ) : 0;

		// Starting markup.
		$juiz_sps_content = $before_the_sps_content;
		$juiz_sps_content .= "\n" . '<' . $div . ' class="juiz_sps_links ' . esc_attr( $container_classes ) . ' juiz_sps_displayed_' . $juiz_sps_display_where . '" data-post-id="' . $post -> ID . '">';
		$juiz_sps_content .= $hide_intro_phrase ? '' : "\n" . '<' . $p . ' class="screen-reader-text juiz_sps_maybe_hidden_text">' . $share_the_post_sentence . ' "' . wp_strip_all_tags( get_the_title() ) . '"</' . $p . '>' . "\n";
		$juiz_sps_content .= $before_the_list;
		$juiz_sps_content .= "\n\t" . '<' . $ul . ' class="juiz_sps_links_list' . esc_attr( $juiz_sps_hidden_name_class ) . '">';
		$juiz_sps_content .= $before_first_i;

		// networks to display
		// 2 differents results by: 
		// -- using hook (options from admin panel)
		// -- using shortcode/template-function (the array $networks in parameter of this function)
		$juiz_sps_networks = array();

		if ( ! empty( $networks ) ) {
			// compare $juiz_sps_options['juiz_sps_networks'] array and $networks array and conserve from the first one all that correspond to the second one's keys
			$juiz_sps_networks = array();

			foreach( $juiz_sps_options['juiz_sps_networks'] as $k => $v ) {
				if ( in_array( $k, $networks ) ) {
					$juiz_sps_networks[ $k ] = $v;
					$juiz_sps_networks[ $k ][0] = 1; // set its visible value to 1 (visible)
				}
			}

		} else {
			$juiz_sps_networks = $juiz_sps_options['juiz_sps_networks'];
		}

		//TODO: make it dynamic.
		$order = array(
			'twitter',
			'pinterest',
			'facebook',
			'digg',
			'linkedin',
			'bookmark',
			'mail',
		);
		var_dump( jsps_get_core_networks() );
		var_dump( $order );
		var_dump( $networks );
		var_dump( $juiz_sps_options['juiz_sps_networks'] );
		// Sort networks by user choice order.
		// @see: https://stackoverflow.com/questions/348410/sort-an-array-by-keys-based-on-another-array/9098675#9098675
		$sorted_networks = array_replace( array_flip( $order ), $juiz_sps_networks );


		// each links (come from options or manual array)
		foreach( $juiz_sps_networks as $k => $v ) {
			if ( $v[0] == 1 ) {
				$api_link = $api_text = '';
				$url  = apply_filters( 'juiz_sps_the_shared_permalink_for_' . $k, $url );
				$network_name = isset( $v[1] ) ? $v[1] : $k;
				$network_name = apply_filters( 'juiz_sps_share_name_for_' . $k, $network_name );

				$twitter_user = $juiz_sps_options['juiz_sps_twitter_user'] != '' ? '&amp;related=' . $juiz_sps_options['juiz_sps_twitter_user'] . '&amp;via=' . $juiz_sps_options['juiz_sps_twitter_user'] : '';

				$api_text = apply_filters( 'juiz_sps_share_text_for_' . $k, sprintf( __( 'Share this article on %s', 'juiz-social-post-sharer' ), $network_name ) );

				$more_attr = $juiz_sps_target_link;

				switch ( $k ) {
					case 'twitter' :
						$api_link = 'https://twitter.com/intent/tweet?source=webclient&amp;original_referer=' . $url . '&amp;text=' . $text . '&amp;url=' . $url . $twitter_user;
						break;

					case 'facebook' :
						$api_link = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
						break;

					case 'google' :
						$api_link = 'https://plus.google.com/share?url=' . $url;
						break;

					case 'pinterest' :
						if ( $image != '' && $force_pinterest_snif == 0 ) {
							$api_link = 'https://pinterest.com/pin/create/bookmarklet/?media=' . $image[0] . '&amp;url=' . $url . '&amp;title=' . get_the_title() . '&amp;description=' . $post->post_excerpt;
						}
						else {
							$api_link = "javascript:void((function(){var%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','//assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)})());";
							$more_attr = '';
						}
						$api_text = apply_filters( 'juiz_sps_share_text_for_' . $k, __( 'Share an image of this article on Pinterest', 'juiz-social-post-sharer' ) );
						break;

					case 'viadeo' :
						$api_link = 'https://www.viadeo.com/?url=' . $url . '&amp;title=' . $text;
						break;

					case 'linkedin':
						$api_link = 'https://www.linkedin.com/shareArticle?mini=true&amp;ro=true&amp;trk=JuizSocialPostSharer&amp;title=' . $text . '&amp;url=' . $url;
						break;

					case 'digg':
						$api_link = 'https://digg.com/submit?phase=2%20&amp;url=' . $url . '&amp;title=' . $text;
						break;

					case 'stumbleupon':
						$api_link = 'https://www.stumbleupon.com/badge/?url=' . $url;
						break;

					case 'tumblr':
						$api_link = 'https://www.tumblr.com/widgets/share/tool?canonicalUrl=' . $url;
						break;

					case 'reddit':
						$api_link = 'https://www.reddit.com/submit?url=' . $url . '&amp;title=' . $text;
						break;

					case 'delicious':
						$api_link = 'https://delicious.com/save?v=5&amp;provider=JSPS&amp;url=' . $url . '&amp;title=' . $text;
						break;

					case 'weibo':
						// title tips by Aili (thank you ;p)
						$simplecontent = $text . esc_attr( urlencode( " : " . mb_substr( strip_tags( $post->post_content ), 0, 90, 'utf-8') ) );
						$api_link = 'http://service.weibo.com/share/share.php?title=' . $simplecontent . '&amp;url=' . $url;
						break;

					case 'vk':
						$api_link = 'https://vkontakte.ru/share.php?url=' . $url;
						break;

					case 'mail' :
						if ( strpos( $juiz_sps_options['juiz_sps_mail_body'], '%%' ) || strpos( $juiz_sps_options['juiz_sps_mail_subject'], '%%' ) ) {
							$api_link = 'mailto:?subject=' . $juiz_sps_options['juiz_sps_mail_subject'] . '&amp;body=' . $juiz_sps_options['juiz_sps_mail_body'];
							$api_link = preg_replace( array('#%%title%%#', '#%%siteurl%%#', '#%%permalink%%#', '#%%url%%#'), array( get_the_title(), get_site_url(), get_permalink(), $url ), $api_link );
						}
						else {
							$api_link = 'mailto:?subject=' . $juiz_sps_options['juiz_sps_mail_subject'] . '&amp;body=' . $juiz_sps_options['juiz_sps_mail_body'] . ":" . $url;
						}
						$api_text = apply_filters( 'juiz_sps_share_text_for_' . $k, __( 'Share this article with a friend (email)', 'juiz-social-post-sharer') );
						break;

					case 'bookmark' :
						$api_link = $url;
						$api_text = apply_filters( 'juiz_sps_share_text_for_' . $k, __( 'Bookmark this article (on your browser)', 'juiz-social-post-sharer') );
						$more_attr = ' data-alert="' . esc_attr( __( 'Press %s to bookmark this page.', 'juiz-social-post-sharer' ) ) . '"';
						break;

					case 'print' :
						$api_link = '#';
						$api_text = apply_filters( 'juiz_sps_share_text_for_' . $k, __( 'Print this page', 'juiz-social-post-sharer') );
						$more_attr = '';
						break;
				}

				$future_link_content = '<' . $li . ' class="juiz_sps_item juiz_sps_link_' . esc_attr( $k ) . '"><a href="' . wp_strip_all_tags( esc_attr( $api_link ) ) . '" ' . $rel_nofollow . '' . $more_attr . ' title="' . esc_attr( $api_text ) . '"><span class="juiz_sps_icon jsps-' . esc_attr( $k ) . '"></span><span class="juiz_sps_network_name">' . esc_html( $network_name ) . '</span></a></' . $li . '>';

				apply_filters( 'juiz_sps_after_each_network_item', $future_link_content, $k, $network_name );
				apply_filters( 'juiz_sps_after_' . $k . '_network_item', $future_link_content, $k, $network_name );

				$juiz_sps_content .= $future_link_content;

			}
		}

		$general_counters = ( isset( $juiz_sps_options['juiz_sps_counter'] ) && $juiz_sps_options['juiz_sps_counter'] == 1 ) ? 1 : 0;

		// no data-* attribute if user markup is not HTML5 :/
		$hidden_info = '<input type="hidden" class="juiz_sps_info_plugin_url" value="' . JUIZ_SPS_PLUGIN_URL . '" /><input type="hidden" class="juiz_sps_info_permalink" value="' . $url . '" />';

		$juiz_sps_content .= $after_last_i;

		// show total counter only when "both" or "total" is selected
		if ( isset( $juiz_sps_options['juiz_sps_counter_option'] ) ) {
			if ( $juiz_sps_options['juiz_sps_counter_option'] == 'both' || $juiz_sps_options['juiz_sps_counter_option'] == 'total' ) {
				$juiz_sps_content .= ( 
										( $general_counters == 1 && intval( $counters ) == 1)
										||
										( $general_counters == 0 && intval( $counters ) == 1 )
									 )
									 ?
									 '<' . $li . ' class="juiz_sps_item juiz_sps_totalcount_item"><span class="juiz_sps_totalcount" title="' . esc_attr( $total_word ) . '"><span class="juiz_sps_t_nb"></span></span></' . $li . '>'
									 : '';
			}
		}
		$juiz_sps_content .= '</' . $ul . '>' . "\n\t";
		$juiz_sps_content .= $after_the_list;
		$juiz_sps_content .= ( ( $general_counters == 1 && intval( $counters ) == 1 ) || ( $general_counters == 0 && intval( $counters ) == 1 ) )  ? $hidden_info : '';
		$juiz_sps_content .= '</' . $div . '>' . "\n\n";
		$juiz_sps_content .= $after_the_sps_content;

		// final markup
		return apply_filters( 'juiz_sps_content', $juiz_sps_content, $juiz_sps_options );

	}

}


// just a little alias
if ( ! function_exists( 'juiz_sps' ) ) {
	function juiz_sps( $networks = array(), $counters = 0, $current_page = 0, $is_shortcode = 0, $url_to_share = NULL ) {
		echo get_juiz_sps( $networks, $counters, $current_page, $is_shortcode, $url_to_share );
	}
}

// write buttons in content
add_filter( 'the_content', 'juiz_sps_print_links', 10, 1 );

if ( apply_filters( 'juiz_sps_buttons_in_excerpt', false ) ) {
	add_filter( 'the_excerpt', 'juiz_sps_print_links', 10, 1 );
}

if ( ! function_exists( 'juiz_sps_print_links' ) ) {
	function juiz_sps_print_links( $content ) {
		
		$juiz_sps_options = jsps_get_option();

		if ( isset( $juiz_sps_options['juiz_sps_display_in_types'] ) ) {

			// write buttons only if administrator checked this type
			$is_all_lists = in_array( 'all_lists', $juiz_sps_options['juiz_sps_display_in_types'] );
			$singular_options = $juiz_sps_options['juiz_sps_display_in_types'];
			unset( $singular_options['all_lists'] );

			$is_lists_authorized = ( is_archive() || is_front_page() || is_search() || is_tag() || is_post_type_archive() || is_home()) && $is_all_lists ? true : false;
			$is_singular = is_singular( $singular_options );

			if ( $is_singular || $is_lists_authorized ) {

				$need_counters = $juiz_sps_options['juiz_sps_counter'] ? 1 : 0;

				$jsps_links = get_juiz_sps( array(), $need_counters );

				$juiz_sps_display_where = isset( $juiz_sps_options['juiz_sps_display_where'] ) ? $juiz_sps_options['juiz_sps_display_where'] : '';

				do_action( 'juiz_sps_before_content_contatenation', $jsps_links, $juiz_sps_display_where, $juiz_sps_options );

				if ( 'top' == $juiz_sps_display_where || 'both' == $juiz_sps_display_where ) {
					$content = $jsps_links . $content;
				}
				if ( 'bottom' == $juiz_sps_display_where || 'both' == $juiz_sps_display_where ) {
					$content = $content . $jsps_links;
				}

				return $content;
			}
			else { 
				return $content;
			}
		}
		else {
			return $content;
		}

	} // end function
} // end if function exists
