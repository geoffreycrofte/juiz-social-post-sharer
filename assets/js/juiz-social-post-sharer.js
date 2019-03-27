/*
Plugin Name: Juiz Social Post Sharer
Plugin URI: http://creativejuiz.fr
Author: Geoffrey Crofte
*/

/* Juiz SPS core */
;jQuery(document).ready(function($){
	jQuery.fn.juiz_get_counters = function(){
		return this.each(function(){
			
			var plugin_url 		= $(this).find('.juiz_sps_info_plugin_url').val(),
				url 			= $(this).find('.juiz_sps_info_permalink').val(),

				$delicious 	= $(this).find('.juiz_sps_link_delicious'),
				$facebook 	= $(this).find('.juiz_sps_link_facebook'),
				$pinterest 	= $(this).find('.juiz_sps_link_pinterest'),
				$google 	= $(this).find('.juiz_sps_link_google'),
				$stumble 	= $(this).find('.juiz_sps_link_stumbleupon'),
				item_class	= '';
				
			if ( $(this).hasClass('counters_total') ) {
				item_class = ' juiz_hidden_counter';
			}

			var delicious_url	= "//feeds.delicious.com/v2/json/urlinfo/data?url=" + url + "&callback=?" ;
			// return : [{"url": "http://www.creativejuiz.fr/blog", "total_posts": 2}]
			var pinterest_url   = "//api.pinterest.com/v1/urls/count.json?callback=?&url=" + url;
			// return : ({"count": 0, "url": "http://stylehatch.co"})
			var facebook_url	= "//graph.facebook.com/fql?q=SELECT%20like_count,%20total_count,%20share_count,%20click_count,%20comment_count%20FROM%20link_stat%20WHERE%20url%20=%20%22"+url+"%22";
			// return : {"data": [{"like_count": 6,"total_count": 25,"share_count": 9,"click_count": 0,"comment_count": 10}]}
			var google_url		= plugin_url+"js/get-noapi-counts.php?nw=google&url=" + url;
			var stumble_url		= plugin_url+"js/get-noapi-counts.php?nw=stumble&url=" + url;

			if ( $pinterest.length ) {
				$.getJSON(pinterest_url)
					.done(function(data){
						$pinterest.prepend('<span class="juiz_sps_counter'+item_class+'">' + data.count + '</span>');
					});
			}
			if ( $google.length ) {
				$.getJSON(google_url)
					.done(function(data){
						var count = data.count;
						$google.prepend('<span class="juiz_sps_counter'+item_class+'">' + count.replace('\u00a0', '') + '</span>');
					})
			}
			if ( $stumble.length ) {
				$.getJSON(stumble_url)
					.done(function(data){
						$stumble.prepend('<span class="juiz_sps_counter'+item_class+'">' + data.count + '</span>');
					})
			}
			if ( $facebook.length ) {
				$.getJSON(facebook_url)
					.done(function(data){
						var facebookdata = 0;
						if ( data.data.length > 0 ) facebookdata = data.data[0].total_count;
						$facebook.prepend('<span class="juiz_sps_counter'+item_class+'">' + facebookdata + '</span>');
					});
			}
			if ( $delicious.length ) {
				$.getJSON(delicious_url)
					.done(function(data){
						$delicious.prepend('<span class="juiz_sps_counter'+item_class+'">' + data[0].total_posts + '</span>');
					});
			}
		});
	}; // juiz_get_counters()

	jQuery.fn.juiz_update_counters = function(){
		return this.each(function() {

			var $group			= $(this);
			var $total_count 	= $group.find('.juiz_sps_totalcount');
			var $total_count_nb = $total_count.find('.juiz_sps_t_nb');
			var total_text = $total_count.attr('title');
			$total_count.prepend('<span class="juiz_sps_total_text">'+total_text+'</span>');

			function count_total() {
				var total = 0;
				$group.find('.juiz_sps_counter').each(function(){
					total += parseInt($(this).text());
				});
				$total_count_nb.text(total);
			}
			setInterval(count_total, 1200);

		});
	}; // juiz_get_counters()

	$('.juiz_sps_links.juiz_sps_counters').juiz_get_counters();
	// only when total or both option is checked
	if ($('.juiz_sps_links.juiz_sps_counters.counters_subtotal').length == 0) {
		$('.juiz_sps_counters .juiz_sps_links_list').juiz_update_counters();
	}

	/**
	 * E-mail button
	 */
	if ( $('.juiz_sps_link_mail').length ) {

		$('.juiz_sps_link_mail').find('a').on('click', function(){

			if ( $('.juiz-sps-modal').length === 0 ) {
				var animation = 400,
					post_id = $(this).closest('.juiz_sps_links').data('post-id'),
					jsps_modal = '<div class="juiz-social-post-sharer-modal juiz-sps-modal" aria-hidden="true" role="dialog" aria-labelledby="juiz-sps-email-title" data-post-id="' + post_id + '">' +
						'<div class="juiz-sps-modal-inside">' +
							'<div class="juiz-sps-modal-header">' +
								'<p id="juiz-sps-email-title" class="juiz-sps-modal-title">' + jsps.modalEmailTitle + '</p>' +
							'</div>' +
							'<div class="juiz-sps-modal-content">' +
								'<p class="juiz-sps-message-info"> ' + jsps.modalEmailInfo + '</p>' +
								'<form id="jsps-email-form" name="jsps-email" action="' + jsps.modalEmailAction + '" method="post">' +
									'<p class="juiz-sps-input-line">' + 
										'<label for="jsps-your-name">' + jsps.modalEmailName + '</label>' +
										'<input type="text" id="jsps-your-name" name="jsps-your-name" value="" autofocus>' +
									'</p>' +
									'<p class="juiz-sps-input-line">' + 
										'<label for="jsps-your-email">' + jsps.modalEmailYourEmail + '</label>' +
										'<input type="email" id="jsps-your-email" name="jsps-your-email" value="">' +
									'</p>' +
									'<p class="juiz-sps-input-line">' + 
										'<label for="jsps-friend-email">' + jsps.modalEmailFriendEmail + '</label>' +
										'<input type="email" id="jsps-friend-email" name="jsps-friend-email" value="">' +
									'</p>' +
									'<p class="juiz-sps-textarea-line">' + 
										'<label for="jsps-message">' + jsps.modalEmailMessage + '</label>' +
										'<textarea id="jsps-message" name="jsps-message" cols="50" rows="7"></textarea>' +
										'<span class="juiz-sps-input-info">' + jsps.modalEmailMsgInfo + '</span>' +
									'</p>' +
									'<p class="juiz-sps-submit-line">' + 
										'<button type="submit" id="jsps-email-submit"><span class="juiz-sps-loader"></span><span class="juiz-sps-submit-txt">' + jsps.modalEmailSubmit + '</span></button>' +
									'</p>' +
								'</form>' +
							'</div>' +
							'<button class="juiz-sps-close" type="button"><i class="juiz-sps-icon-close">×</i><span class="juiz-sps-hidden">' + jsps.modalClose + '</span></button>' +
						'</div>' +
					'</div>';

				$('body').append( jsps_modal );
				$('.juiz-sps-modal').hide().fadeIn( animation ).attr('aria-hidden', 'false')
					.find('input:first').focus();

			}

			$('body').on( 'submit.jsps', '#jsps-email-form', function(e){
				
				var datas = $(this).serializeArray(),
					action = $(this).attr('action'),
					$modal = $(this).closest('.juiz-sps-modal'),
					post_id = $modal.data('post-id'),
					$loader = $(this).find('.juiz-sps-loader');

				$loader.addClass('is-active').html( jsps.modalLoader );

				$.get( action, {
					'id'			: post_id,
					'your-name'		: datas[0].value,
					'your-email'	: datas[1].value,
					'your-friend'	: datas[2].value,
					'message'		: datas[3].value
				}, function(data){
					if ( data.success === true ) {
						$modal.find('form').replaceWith('<div class="juiz-sps-success juiz-sps-message">' + data.data + '</div>');
					}
					else if ( data.success === false ) {
						if ( $modal.find('.juiz-sps-error').length === 0 ) {
							$modal.find('form').prepend('<div class="juiz-sps-error juiz-sps-message">' + data.data[1] + '</div>');
						}
						else {
							$modal.find('.juiz-sps-error').text( data.data[1] );
						}
						
						$loader.removeClass('is-active');
						var temp = setInterval(function(){
							$loader.find('img').remove();
							clearInterval(temp);
						}, 300);
					}
				}, 'json');

				return false;
			})
			.on( 'click.jsps', '.juiz-sps-close', function(){
				var $modal = $('.juiz-sps-modal').fadeOut( animation ).attr('aria-hidden', 'true'),
					temp = setInterval(function(){
						$modal.remove();
					}, animation + 20 );
				return false;
			})

			// accessibility
			$('.juiz-sps-close').on( 'blur', function(){
				$(this).closest('.juiz-sps-modal').find('input:first').focus();
				return false;
			});

			return false;
		});
	}

	/**
	 * Print button
	 */
	if ( window.print ) {
		$('.juiz_sps_link_print').on('click', function(){
			window.print();
			return false;
		});
	}
	else {
		$('.juiz_sps_link_print').remove();
	}
	
	/**
	 * Bookmark button
	 */
	if (
		( 'addToHomescreen' in window && window.addToHomescreen.isCompatible )
		||
		( window.sidebar && window.sidebar.addPanel )
		||
		( (window.sidebar && /Firefox/i.test(navigator.userAgent)) || (window.opera && window.print) )
		||
		( window.external && ('AddFavorite' in window.external) )
		||
		( typeof chrome === 'undefined' )
		||
		( typeof chrome !== 'undefined' )
	) {
		$('.juiz_sps_link_bookmark').find('a').on('click', function(e){
			// Thanks to:
			// https://www.thewebflash.com/how-to-add-a-cross-browser-add-to-favorites-bookmark-button-to-your-website/
			var bookmarkURL = window.location.href;
			var bookmarkTitle = document.title;

			if ( 'addToHomescreen' in window && window.addToHomescreen.isCompatible ) {
				// Mobile browsers
				addToHomescreen({ autostart: false, startDelay: 0 }).show(true);
			}
			else if ( window.sidebar && window.sidebar.addPanel ) {
				// Firefox version < 23
				window.sidebar.addPanel(bookmarkTitle, bookmarkURL, '');
			}
			else if ( ( window.sidebar && /Firefox/i.test(navigator.userAgent) ) || ( window.opera && window.print ) ) {
				// Firefox version >= 23 and Opera Hotlist
				$(this).attr({
					href: bookmarkURL,
					title: bookmarkTitle,
					rel: 'sidebar'
				}).off( e );
				return true;
			}
			else if ( window.external && ( 'AddFavorite' in window.external ) ) {
				// IE Favorite
				window.external.AddFavorite(bookmarkURL, bookmarkTitle);
			}
			else {
				// Other browsers (mainly WebKit - Chrome/Safari)
				command = (/Mac/i.test(navigator.userAgent) ? 'Cmd' : 'Ctrl') + '+D';
				message = $(this).data('alert');
				message = message.replace(/%s/, command);
				alert( message );

				return false;

			}
			return false;
		});
	}
	else {
		$('.juiz_sps_link_bookmark').remove();
	}

});
/*
//var google_url = "https://clients6.google.com/rpc?key=YOUR_API_KEY";
//var digg_url = "http://services.digg.com/2.0/story.getInfo?links=" + url + "&type=javascript&callback=?"; 
*/