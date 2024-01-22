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
	 * @param  string  $url_to_share        'permalink' | 'siteurl' | &lt;custom_url&gt;
	 * @return string                       Return the markup for the buttons.
	 *
	 * @author  Geoffrey Crofte
	 * @since   1.0
	 * @version 2.0.0
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
		}

		$url = $post ? get_permalink() : juiz_sf_get_current_url( 'raw' );
		$url = ( $url_needed_by_user == false ) ? $url : $url_needed_by_user;

		if ( $is_current_page_url &&  $url_needed_by_user == false ) {
			$url = juiz_sf_get_current_url( 'raw' );
		}

		/**
		 * Sets the URL to be shared on all the networks activated.
		 * Another hook arrives later for each specific network called {@link juiz_sps_the_shared_permalink_for_*} where * is the network Slug.
		 * 
		 * @hook juiz_sps_the_shared_permalink
		 * 
	 	 * @since  1.0.0 First version
	 	 * 
	 	 * @param  {string}  $url                  The shared URL.
	 	 * @param  {int}     $is_current_page_url  Is the current page or custom to share?
	 	 * @param  {string}  $url_to_share         `permalink` | `siteurl` | &lt;custom_url&gt;
	 	 *
	 	 * 
	 	 * @return {string} The URL to be shared. You don't need to sanitize or urlencode it, it'll be done later in the process.
	 	 *
		 */
		$url = apply_filters( 'juiz_sps_the_shared_permalink', $url, $is_current_page_url, $url_to_share );

		// Texts and Image to share
		$text    = wp_strip_all_tags( esc_attr( rawurlencode( $post->post_title ) ) );
		$excerpt = rawurlencode( wp_strip_all_tags( $post->post_excerpt ) );
		$excerpt = empty( $excerpt ) ? rawurlencode( jsps_get_excerpt( $post ) . '…' ) : $excerpt;
		
		$image = has_post_thumbnail( $post->ID ) ? wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ) : '';

		/**
		 * Sets the word to be used next to the total when counters are activated.
		 * 
		 * @hook juiz_sps_total_count_word
		 * 
	 	 * @since  1.0.0 First version
	 	 * @param  {string}  $text="Total: "        The text to be used.
	 	 * @return {string}  The translated word "Total: " or another one.
		 */
		$total_word = apply_filters( 'juiz_sps_total_count_word', __( 'Total: ', 'juiz-social-post-sharer' ) );

		/**
		 * Sets the word to be used below the total when counters are activated.
		 * 
		 * @hook juiz_sps_shares_word
		 * 
	 	 * @since  1.0.0 First version
	 	 * @param  {string}  $text="Shares"  The text to be used.
	 	 * @return {string} The translated word "Shares" or another one.
		 */
		$shares_word = apply_filters( 'juiz_sps_shares_word', __( 'Shares', 'juiz-social-post-sharer' ) );

		/**
		 * Decides if the intro text before the buttons should be printed or not.
		 * 
		 * @hook juiz_sps_hide_intro
		 * 
	 	 * @since  1.0.0 First version
	 	 * @param  {boolean}  $is_hidden=false  Set to false if you want to print it.
	 	 * @return {boolean} The true or false value, nothing else.
		 */
		$hide_intro_phrase = apply_filters( 'juiz_sps_hide_intro', false );

		/**
		 * Prints the text "Share the post" in introduction before the buttons if `juiz_sps_hide_intro`returns true.
		 * 
		 * @hook juiz_sps_intro_phrase_text
		 * 
	 	 * @since  1.0.0 First version
	 	 * @param  {string}  $text="Share·the·post"  The Text printed before the buttons.
	 	 * @return {string} The Text printed before the buttons.
		 */
		$share_the_post_sentence = apply_filters( 'juiz_sps_intro_phrase_text', __('Share the post', 'juiz-social-post-sharer') );

		/**
		 * Prints something before the `.juiz_sps_links` element.
		 * This is not an action hook ;)
		 * 
		 * @hook juiz_sps_before_the_sps
		 * 
	 	 * @since  1.0.0 First version
	 	 * @param  {string}  $text=""  The text or HTML code printed before the content.
	 	 * @return {string} The text or HTML code printed before the content.
		 */
		$before_the_sps_content = apply_filters( 'juiz_sps_before_the_sps', '' );

		/**
		 * Prints something after the `.juiz_sps_links` element, just before the other filter `juiz_sps_content` applies.
		 * This is not an action hook ;)
		 * 
		 * @hook juiz_sps_after_the_sps
		 * 
	 	 * @since  1.0.0 First version
	 	 * @param  {string}  $text=""  The text or HTML code printed after the content.
	 	 * @return {string} The text or HTML code printed after the content.
		 */
		$after_the_sps_content = apply_filters( 'juiz_sps_after_the_sps', '' );

		/**
		 * Prints something just before the `.juiz_sps_links_list` element.
		 * This is not an action hook ;)
		 * 
		 * @hook juiz_sps_before_the_list
		 * 
	 	 * @since  1.0.0 First version
	 	 * @param  {string}  $text=""  The text or HTML code printed before the content.
	 	 * @return {string} The text or HTML code printed before the content.
		 */
		$before_the_list = apply_filters( 'juiz_sps_before_the_list', '' );

		/**
		 * Prints something just after the `.juiz_sps_links_list` element.
		 * This is not an action hook ;)
		 * 
		 * @hook juiz_sps_after_the_list
		 * 
	 	 * @since  1.0.0 First version
	 	 * @param  {string}  $text=""  The text or HTML code printed after the content.
	 	 * @return {string} The text or HTML code printed after the content.
		 */
		$after_the_list = apply_filters( 'juiz_sps_after_the_list', '' );

		/**
		 * Prints something just before the first network markup (before the `li` element by default)
		 * This is not an action hook ;)
		 * 
		 * @hook juiz_sps_before_first_item
		 * 
	 	 * @since  1.0.0 First version
	 	 * @param  {string}  $text=""  The text or HTML code printed before the item.
	 	 * @return {string} The text or HTML code printed before the item.
		 */
		$before_first_i = apply_filters( 'juiz_sps_before_first_item', '' );

		/**
		 * Prints something just after the last network markup (after the last `li` element by default)
		 * This is not an action hook ;)
		 * 
		 * @hook juiz_sps_after_last_item
		 * 
	 	 * @since  1.0.0 First version
	 	 * @param  {string}  $text=""  The text or HTML code printed after the item.
	 	 * @return {string} The text or HTML code printed after the item.
		 */
		$after_last_i = apply_filters( 'juiz_sps_after_last_item', '' );

		/**
		 * Sets new classes to the container.
		 * 
		 * @hook juiz_sps_container_classes
		 * 
	 	 * @since  1.0.0 First version
	 	 * @param  {string}  $classes=""  A text representing your new `class` attribute value.
	 	 * @return {string} The class(es) added to the existing `class` attribute.
		 */
		$container_classes = apply_filters( 'juiz_sps_container_classes', '' );

		/**
		 * Adds the `rel=""nofollow` attribute to every links/buttons.
		 * Pro tips, you can use this hook to add attributes to every link.
		 * 
		 * @hook juiz_sps_links_nofollow
		 * 
	 	 * @since  1.0.0 First version
	 	 * @param  {string} $attr='rel="nofollow"'  The attribute and its value.
	 	 * @return {string} The attribute and its value.
		 */
		$rel_nofollow = apply_filters( 'juiz_sps_links_nofollow', 'rel="nofollow"' );

		/**
		 * Marks up the container as a DIV by default.
		 * 
		 * @hook juiz_sps_container_tag
		 * 
	 	 * @since  1.0.0 First version
	 	 * @param  {string} $element='div'  The element name of the container.
	 	 * @return {string} The element name of the container.
		 */
		$div = apply_filters( 'juiz_sps_container_tag', 'div' );

		/**
		 * Marks up the intro phrase as a P by default.
		 * 
		 * @hook juiz_sps_phrase_tag
		 * 
	 	 * @since  1.0.0 First version
	 	 * @param  {string} $element='p'  The element name of the intro phrase.
	 	 * @return {string} The element name of the intro phrase.
		 */
		$p = apply_filters( 'juiz_sps_phrase_tag', 'p' );

		/**
		 * Marks up the list container as a UL by default.
		 * 
		 * @hook juiz_sps_list_container_tag
		 * 
	 	 * @since  1.0.0 First version
	 	 * @param  {string} $element='ul'  The element name of the list container.
	 	 * @return {string} The element name of the list container.
		 */
		$ul = apply_filters( 'juiz_sps_list_container_tag', 'ul' ); 

		/**
		 * Marks up the list item as a LI by default.
		 * 
		 * @hook juiz_sps_list_of_item_tag
		 * 
	 	 * @since  1.0.0 First version
	 	 * @param  {string} $element='li'  The element name of the list item.
	 	 * @return {string} The element name of the list item.
		 */
		$li = apply_filters( 'juiz_sps_list_of_item_tag', 'li' );


		// Get the plugin options.
		$juiz_sps_options = jsps_get_option();

		// Classes and attributes options.
		$juiz_sps_target_link = ( isset( $juiz_sps_options['juiz_sps_target_link'] ) && $juiz_sps_options['juiz_sps_target_link'] == 1 ) ? ' target="_blank"' : '';
		$juiz_sps_hidden_name_class = ( isset( $juiz_sps_options['juiz_sps_hide_social_name'] ) && $juiz_sps_options['juiz_sps_hide_social_name'] == 1 ) ? ' juiz_sps_hide_name' : '';
		$juiz_sps_compact_name_class = ( isset( $juiz_sps_options['juiz_sps_compact_display'] ) && $juiz_sps_options['juiz_sps_compact_display'] == 1 ) ? ' juiz_sps_compact' : '';
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
		$juiz_sps_content .= "\n\t" . '<' . $ul . ' class="juiz_sps_links_list' . esc_attr( $juiz_sps_hidden_name_class ) . esc_attr( $juiz_sps_compact_name_class ) . '">';
		$juiz_sps_content .= $before_first_i;


		// Networks to display
		// 2 differents results by: 
		// -- using hook (options from admin panel)
		// -- using shortcode/template-function (the array $networks in parameter of this function)
		$juiz_sps_networks = array();
		$all_networks = jsps_get_all_registered_networks();

		if ( ! empty( $networks ) ) {
			foreach( $all_networks as $k => $v ) {
				$juiz_sps_networks[ $k ] = $v;
				$juiz_sps_networks[ $k ]['visible'] = (int) in_array( $k, $networks ); // set its visible value to 1 (visible)
			}
		} else {
			$juiz_sps_networks = $juiz_sps_options['juiz_sps_networks'];
		}

		// Set network order
		$order = ! empty( $networks ) ? $networks : $juiz_sps_options['juiz_sps_order'];
		
		// Get displayable (not necessarily displayed) Buttons
		$sorted_networks = jsps_get_displayable_networks( $juiz_sps_networks, $order );

		// Each links (come from options or manual array)
		foreach( $sorted_networks as $k => $v ) {
			
			// If not visible, got to next item.
			if ( ! isset( $v['visible'] ) || ( isset( $v['visible'] ) && $v['visible'] === 0 ) ) {
				continue;
			}

			$api_link = get_permalink( $post->ID );
			$api_text = '';

			/**
			 * Sets the URL to be shared on a specific network, where `*` is the network shortname.
			 * 
			 * @hook juiz_sps_the_shared_permalink_for_*
			 * 
		 	 * @since  2.0.0 Adds `$is_current_page_url` and `$url_to_share` arguments.
		 	 * @since  1.0.0 First version
		 	 * 
		 	 * @param  {string}  $url                  The shared URL.
		 	 * @param  {int}     $is_current_page_url  Is the current page or custom to share?
		 	 * @param  {string}  $url_to_share         `permalink` | `siteurl` | &lt;custom_url&gt;
		 	 *
		 	 * 
		 	 * @return {string} The URL to be shared on the specific network. You don't need to sanitize or urlencode it, the after that.
		 	 *
			 */
			$url = apply_filters( 'juiz_sps_the_shared_permalink_for_' . $k, $url, $is_current_page_url, $url_to_share );

			$nw_name  = isset( $v['name'] ) ? $v['name'] : $k;

			/**
			 * The name of a specific network display on the button.
			 * 
			 * @hook juiz_sps_share_name_for_*
			 * 
		 	 * @since  1.0.0 First version
		 	 * @param  {string}  $nw_name  The original name of the network.
		 	 * @return {string}  The string returned after the renaming.
			 */
			$nw_name  = apply_filters( 'juiz_sps_share_name_for_' . $k, $nw_name );

			/**
			 * The tooltip for a specific network. Default one is `Share this on %s`.
			 * 
			 * @hook juiz_sps_share_text_for_*
			 * 
		 	 * @since  1.0.0 First version
		 	 * @param  {string}  $text      The original text in the tooltip.
		 	 * @param  {string}  $nw_name   The original name —or modified name if you filtered {@link juiz_sps_share_name_for_*}— of the network.
		 	 * @return {string}  The string returned text in the tooltip for this specific network.
			 */
			$api_text = apply_filters( 'juiz_sps_share_text_for_' . $k, sprintf( __( 'Share this article on %s', 'juiz-social-post-sharer' ), $nw_name ) );

			/**
			 * The type of HTML element you want to use for a specific network.
			 * It produces automatically a `a href` or a `button type="button"` if it detects of the `a` or `button` options.
			 * 
			 * @hook juiz_sps_button_element_type_for_*
			 * 
		 	 * @since  2.0.0 First version
		 	 * @param  {string}  $element   It's a link par default, so `a`
		 	 * @return {string}  The string of HTML element you want.
			 */
			$button = apply_filters( 'juiz_sps_button_element_type_for_' . $k, 'a' );

			// Some conditional used variable for content adjustment.
			// Most of them are empty and tweakable with filters later.
			$more_link_attr = $more_item_attr = $code_before_end_li = $code_before_end_button = $code_between_icon_text = $code_before_icon = '';

			switch ( $k ) {
				case 'twitter' :
					/**
					 * Edits the default Twitter nickname mentionned in the URL (via option) and override the option in the admin.<br>
					 * **The option have to not to be empty in the admin for the hook to work.**
					 * 
					 * @hook juiz_sps_twitter_nickname
					 * 
				 	 * @since  1.4.9 First version
				 	 * @param  {string}  $text      The Twitter nickname set in the admin option.
				 	 * @return {string}  The new Twitter nickname to be mentionned.
					 */
					$twitter_user = isset( $juiz_sps_options['juiz_sps_twitter_user'] ) && $juiz_sps_options['juiz_sps_twitter_user'] != '' ? '&amp;related=' . apply_filters( 'juiz_sps_twitter_nickname', $juiz_sps_options['juiz_sps_twitter_user'] ) . '&amp;via=' . apply_filters( 'juiz_sps_twitter_nickname', $juiz_sps_options['juiz_sps_twitter_user'] ) : '';

					$api_link = 'https://twitter.com/intent/tweet?source=webclient&amp;original_referer=' . urlencode( $url ) . '&amp;text=' . $text . '&amp;url=' . urlencode( $url ) . $twitter_user;
					break;

				case 'facebook' :
					$api_link = 'https://www.facebook.com/sharer.php?u=' . urlencode( $url );
					break;

				case 'pinterest' :
					if ( $image != '' && $force_pinterest_snif == 0 ) {
						$api_link = 'https://pinterest.com/pin/create/bookmarklet/?url=' . urlencode( $url ) . '&amp;media=' . $image[0];
						//$api_link = 'https://pinterest.com/pin/create/button/?media=' . $image[0] . '&amp;url=' . urlencode( $url ) . '&amp;title=' . get_the_title() . '&amp;description=' . $excerpt;
					}
					else {
						$api_link = "javascript:void((function(){var%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','//assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)})());";
					}
					$api_text = apply_filters( 'juiz_sps_share_text_for_' . $k, __( 'Share an image of this article on Pinterest', 'juiz-social-post-sharer' ) );
					break;

				case 'viadeo' :
					$api_link = 'https://www.viadeo.com/en/widgets/share/preview?url=' . urlencode( $url ) . '&amp;comment=' . $text;
					break;

				case 'linkedin':
					$api_link = 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode( $url );
					break;

				case 'tumblr':
					$api_link = 'https://www.tumblr.com/widgets/share/tool?canonicalUrl=' . urlencode( $url );
					break;

				case 'reddit':
					$api_link = 'https://www.reddit.com/submit?url=' . urlencode( $url ) . '&amp;title=' . $text;
					break;
				
				case 'mix':
					$api_link = 'https://mix.com/mixit?su=juiz-social-post-sharer&amp;url=' . urlencode( $url );
					break;

				case 'whatsapp':
					$api_link = 'https://wa.me/?text=' . urlencode( '"' ) . $text . urlencode( '": ' . $url );
					break;

				case 'pocket':
					$api_link = 'https://getpocket.com/edit?url=' . urlencode( $url );
					break;

				case 'evernote':
					$api_link = 'https://www.addtoany.com/add_to/evernote?linkurl=' . urlencode( $url ) . '&amp;linkname=' . $text . '&amp;linknote=' . $excerpt;
					break;

				case 'diigo':
					$api_link = 'https://www.diigo.com/post?url=' . urlencode( $url ) . '&amp;title='. $text .'&amp;desc=' . $excerpt . '&amp;client=juis-social-post-sharer'; // client=simplelet
					break;

				case 'weibo':
					// title tips by Aili (thank you ;p)
					$simplecontent = $text . esc_attr( urlencode( " : " . mb_substr( strip_tags( $post->post_content ), 0, 90, 'utf-8') ) );
					$api_link = 'http://service.weibo.com/share/share.php?title=' . $simplecontent . '&amp;url=' . urlencode( $url );
					break;

				case 'vk':
					$api_link = 'https://vkontakte.ru/share.php?url=' . urlencode( $url );
					break;

				case 'mail' :
					if ( strpos( $juiz_sps_options['juiz_sps_mail_body'], '%%' ) || strpos( $juiz_sps_options['juiz_sps_mail_subject'], '%%' ) ) {
						$api_link = 'mailto:?subject=' . $juiz_sps_options['juiz_sps_mail_subject'] . '&amp;body=' . $juiz_sps_options['juiz_sps_mail_body'];
						$api_link = jsps_render_mail_content( $api_link );
					}
					else {
						$api_link = 'mailto:?subject=' . $juiz_sps_options['juiz_sps_mail_subject'] . '&amp;body=' . $juiz_sps_options['juiz_sps_mail_body'] . ":" . urlencode( $url );
					}
					$api_text = apply_filters( 'juiz_sps_share_text_for_' . $k, __( 'Share this article with a friend (email)', 'juiz-social-post-sharer') );
					break;

				case 'bookmark' :
					$api_text = apply_filters( 'juiz_sps_share_text_for_' . $k, __( 'Bookmark this article (on your browser)', 'juiz-social-post-sharer') );
					$more_link_attr = 'data-alert="' . esc_attr( __( 'Press %s to bookmark this page.', 'juiz-social-post-sharer' ) ) . '"';
					break;

				case 'print' :
					$button = 'button';
					$api_text = apply_filters( 'juiz_sps_share_text_for_' . $k, __( 'Print this page', 'juiz-social-post-sharer') );
					break;

				case 'shareapi' :
					$button = 'button';
					$api_text = apply_filters( 'juiz_sps_share_text_for_' . $k, __( 'Share on your favorite apps', 'juiz-social-post-sharer') );
					$more_item_attr = 'style="display:none;"';
					
					$code_before_end_li = '<script>
					window.addEventListener("DOMContentLoaded", function(){
						if ( navigator.share ) {
							let shareurl = document.location.href;
							let btns = document.querySelectorAll(".juiz_sps_link_shareapi button:not([data-bound])");
							const canon = document.querySelector("link[rel=canonical]");

							if (canon !== null) {
								shareurl = canon.href;
							}

							btns.forEach(function(el) {
								el.closest(".juiz_sps_link_shareapi").removeAttribute( "style" );
								el.setAttribute( "data-bound", "true" );
								el.addEventListener("click", async () => {
									try {
										await navigator.share({
											title: "' . esc_js ( esc_attr( $post->post_title ) ) . '",
											text: "' . esc_js( esc_attr( $post->post_title ) ) . ' - ' . esc_js( esc_attr( jsps_get_excerpt( $post ) ) ) . '",
											url: shareurl,
										});
										console.info("Nobs: Successful share");
									} catch(err) {
										console.warn("Nobs: Error sharing", error);
									}
								});
							});
						}
					});
					</script>';

					break;

				default:
					if ( ! isset( $v['api_url'] ) ) break;
					
					$api_link = jsps_render_api_link( array(
						'api'   => $v['api_url'],
						'desc'  => $excerpt,
						'title' => $text,
						'url'   => urlencode( $url )
					) );

					$api_text = isset( $v['title'] ) ? $v['title'] : '';
			}

			/**
			 * Edits the API URL at the end. You can use it to add parameters like UTM.
			 * <br> To edit a specific API URL, see {@link juiz_sps_*_url_params}
			 * 
			 * @hook juiz_sps_url_params
			 * 
		 	 * @since  2.0.0 First version
		 	 * @param  {string}  $params=''   Empty by default.
		 	 * @return {string}  The new parameters you added. Don't forget to start with a `&amp;amp;`
			 */
			/**
			 * Edits the specific API URL at the end. You can use it to add parameters like UTM.
			 * 
			 * @hook juiz_sps_*_url_params
			 * 
		 	 * @since  2.0.0 First version
		 	 * @param  {string}  $params=''   Empty by default.
		 	 * @return {string}  The new parameters you added. Don't forget to start with a `&amp;amp;`
			 */
			$api_link = $api_link . apply_filters( 'juiz_sps_' . $k . '_url_params', apply_filters( 'juiz_sps_url_params', '' ) );

			/**
			 * Edits the item (LI by default) HTML attributes before printing it.
			 * <br> To edit a specific item attributes, see {@link juiz_sps_*_item_attr}
			 * 
			 * @hook juiz_sps_items_attr
			 * 
		 	 * @since  2.0.0 First version
		 	 * @param  {string}  $attrs=''   Empty most of the time, but not for Share API.
		 	 * @return {string}  The new attributes you added.
			 */
			/**
			 * Edits the item (LI by default) HTML attributes before printing it for a precise network/button.
			 * 
			 * @hook juiz_sps_*_item_attr
			 * 
		 	 * @since  2.0.0 First version
		 	 * @param  {string}  $attrs=''   Empty most of the time, but not for Share API.
		 	 * @return {string}  The new attributes you added.
			 */
			$more_item_attr = apply_filters( 'juiz_sps_' . $k . '_item_attr', apply_filters( 'juiz_sps_items_attr', $more_item_attr ) );

			/**
			 * Edits the link (A by default) HTML attributes before printing it.
			 * <br> To edit a specific link attributes, see {@link juiz_sps_*_link_attr}
			 * 
			 * @hook juiz_sps_links_attr
			 * 
		 	 * @since  2.0.0 First version
		 	 * @param  {string}  $attrs=''   Empty most of the time, but not Bookmark button.
		 	 * @return {string}  The new attributes you added.
			 */
			/**
			 * Edits the link (A by default) HTML attributes before printing it for a precise network/button.
			 * 
			 * @hook juiz_sps_*_link_attr
			 * 
		 	 * @since  2.0.0 First version
		 	 * @param  {string}  $attrs=''   Empty most of the time, but not Bookmark button.
		 	 * @return {string}  The new attributes you added.
			 */
			$more_link_attr = apply_filters( 'juiz_sps_' . $k . '_link_attr', apply_filters( 'juiz_sps_links_attr', $more_link_attr ) );

			$item_content = '<' . $li . '' . ( isset( $more_item_attr ) && ! empty( $more_item_attr ) ? ' ' . $more_item_attr : '' ) . ' class="juiz_sps_item juiz_sps_link_' . esc_attr( $k ) . '"' . ( isset( $v['color'] ) ? ' style="--jsps-custom-color:' . esc_attr( $v['color'] ) . ';' . ( isset( $v['hcolor'] ) ? '--jsps-custom-hover-color:' . esc_attr( $v['hcolor'] ) . ';' : '' ) . '"' : '' ) . '>';

			$btn_el = '';
			if ( $button === 'a' ) {
				$btn_el = 'a href="' . esc_url( $api_link ) . '" ' . $rel_nofollow . ' ' . $juiz_sps_target_link;
			} elseif ( $button === 'button' ) {
				$btn_el = 'button type="button" data-api-link="' . esc_url( $api_link ) . '"';
			} else {
				$btn_el = $button;
			}

			$item_content .= '<' . $btn_el . ' ' . ( isset( $more_link_attr ) && ! empty( $more_link_attr ) ? ' ' . $more_link_attr : '' )  . ' title="' . esc_attr( $api_text ) . '" class="juiz_sps_button" data-nobs-key="' . esc_attr( $k ) . '">';

			/**
			 * Adds code content before the .juiz_sps_icon element.
			 * 
			 * @hook juiz_sps_code_before_icon
			 * 
		 	 * @since  2.0.0 First version
		 	 * @param  {string}  $code=''   Empty by default.
		 	 * @return {string}  Your own code.
			 */
			$item_content .= apply_filters( 'juiz_sps_code_before_icon', $code_before_icon );

			$item_content .= '<span class="juiz_sps_icon jsps-' . esc_attr( $k ) . '">' . jsps_get_network_html_icon( $k, $v, true ) . '</span>';

			/**
			 * Adds code content between the .juiz_sps_icon and .juiz_sps_network_name elements.
			 * 
			 * @hook juiz_sps_code_between_icon_text
			 * 
		 	 * @since  2.0.0 First version
		 	 * @param  {string}  $code=''   Empty by default.
		 	 * @return {string}  Your own code.
			 */
			$item_content .= apply_filters( 'juiz_sps_code_between_icon_text', $code_between_icon_text );

			$item_content .= '<span class="juiz_sps_network_name">' . esc_html( $nw_name ) . '</span>';

			/**
			 * Adds code content before the end of the link or button element .juiz_sps_button
			 * 
			 * @hook juiz_sps_code_before_end_button
			 * 
		 	 * @since  2.0.0 First version
		 	 * @param  {string}  $code=''   Empty by default.
		 	 * @return {string}  Your own code.
			 */
			$item_content .= apply_filters( 'juiz_sps_code_before_end_button', $code_before_end_button );

			$item_content .= '</' . $button . '>';

			/**
			 * Adds code content before the end of the item .juiz_sps_item
			 * 
			 * @hook juiz_sps_code_before_end_li
			 * 
		 	 * @since  2.0.0 First version
		 	 * @param  {string}  $code=''   Empty by default, except for ShareAPI button.
		 	 * @return {string}  Your own code.
			 */
			$item_content .= apply_filters( 'juiz_sps_code_before_end_li', $code_before_end_li );

			$item_content .= '</' . $li . '>';

			/**
			 * Edits the HTML code for every item including the LI and A elements.
			 * <br> To edit the same code for a specific network, prefer the hook {@link juiz_sps_after_*_network_item}
			 * 
			 * @hook juiz_sps_after_each_network_item
			 * 
		 	 * @since  1.4.3 First version
		 	 *
		 	 * @param  {string}  $item_content  The link content.
		 	 * @param  {string}  $k  			The network shortname.
		 	 * @param  {string}  $nw_name		The display-name of the network.
		 	 * @param  {array}   $v				The information relative for a specific network.
		 	 *
		 	 * @return {string}  The modified HTML code.
			 */
			$item_content = apply_filters( 'juiz_sps_after_each_network_item', $item_content, $k, $nw_name, $v );

			/**
			 * Edits the HTML code for a specific network item including the LI and A elements
			 * 
			 * @hook juiz_sps_after_*_network_item
			 * 
		 	 * @since  1.4.3 First version
		 	 *
		 	 * @param  {string}  $item_content  The link content.
		 	 * @param  {string}  $k  			The network shortname.
		 	 * @param  {string}  $nw_name		The display-name of the network.
		 	 * @param  {array}   $v				The information relative for a specific network.
		 	 *
		 	 * @return {string}  The modified HTML code.
			 */
			$item_content = apply_filters( 'juiz_sps_after_' . $k . '_network_item', $item_content, $k, $nw_name, $v );

			$juiz_sps_content .= $item_content;

		} // end of FOREACH

		$general_counters = ( isset( $juiz_sps_options['juiz_sps_counter'] ) && $juiz_sps_options['juiz_sps_counter'] == 1 ) ? 1 : 0;

		// no data-* attribute if user markup is not HTML5 :/
		$hidden_info = '<input type="hidden" class="juiz_sps_info_plugin_url" value="' . JUIZ_SPS_PLUGIN_URL . '" /><input type="hidden" class="juiz_sps_info_permalink" value="' . get_permalink( $post -> ID ) . '" /><input type="hidden" class="juiz_sps_info_post_id" value="' . $post -> ID . '" />';

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
				 '<' . $li . ' class="juiz_sps_item juiz_sps_totalcount_item"><span class="juiz_sps_totalcount" title="' . esc_attr( $total_word ) . '"><span class="juiz_sps_total_share_text">' . esc_html( $shares_word ) . '</span></span></' . $li . '>'
				 : '';
			}
		}
		$juiz_sps_content .= '</' . $ul . '>' . "\n\t";
		$juiz_sps_content .= $after_the_list;
		$juiz_sps_content .= ( ( $general_counters == 1 && intval( $counters ) == 1 ) || ( $general_counters == 0 && intval( $counters ) == 1 ) )  ? $hidden_info : '';
		$juiz_sps_content .= '</' . $div . '>' . "\n\n";
		$juiz_sps_content .= $after_the_sps_content;

		// Final markup
		/**
		 * The overall HTML content used to display everything in front.
		 * Use it if no other hook works for you and you want to edit its HTML.
		 * 
		 * @hook juiz_sps_content
		 * 
	 	 * @since  1.4.3 First version
	 	 *
	 	 * @param  {string}  $juiz_sps_content  The HTML Content.
	 	 * @param  {array}   $juiz_sps_options  All the plugin options to avoid a request ;p
	 	 *
	 	 * @return {string}  The new HTML code.
		 */
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

/**
 * By default the buttons are display only into the content, not the excerpts.
 * Set to `true` if you want to change this behavior. 
 * 
 * @hook juiz_sps_buttons_in_excerpt
 * 
 * @since  1.3.3.7 First version
 * @param  {boolean}  $in_excerpt=false  Set to true if you want buttons in excerpt. 
 * @return Void. 
 */
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

				/**
				 * Allows you to do something before the concatenation with the post content. 
				 * 
				 * @hook juiz_sps_before_content_concat
				 *
				 * @since  2.0.0 First version
				 *
				 * @param  {string} $jsps_links              The HTML for all the front buttons.
				 * @param  {string} $juiz_sps_display_where  A keyword among `top`, `bottom` and `both`.
				 * @param  {string} $juiz_sps_options        All the plugin's options.
				 */
				do_action( 'juiz_sps_before_content_concat', $jsps_links, $juiz_sps_display_where, $juiz_sps_options );

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
