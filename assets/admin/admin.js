;jQuery( document ).ready( function( $ ){
	/**
	 * On big screen create tabs for the options.
	 * @since 1.5
	 * @author Geoffrey Crofte
	 */
	function juiz_maybe_build_tabs() {
		var $titles = $('.jsps-main-body h2'),
			i = 0,
			tabs = '';

		if ( window.matchMedia('(min-width: 860px)').matches && $('#jsps-all-tabs').length === 0 ) {
			$titles.each(function(){
				var $siblings = $(this).nextUntil('h2'),
					curri = jsps_get_current_tab_selected();

				i++;
				
				// Add a new future tab.
				tabs = tabs + '<li><a data-index="' + i + '" role="tab" href="#jsps-tab-container-' + i + '" aria-controls="jsps-tab-container-' + i + '" aria-selected="' + ( i ===curri ? 'true' : 'false' ) + '" tabindex="-1">' + $(this).text() + '</a></li>';

				// Put the h2 into a tab container.
				$(this).wrap('<div role="tabpanel" tabindex="0" id="jsps-tab-container-' + i + '" class="jsps-tab-container"' + ( i ===curri ? '' : ' hidden="hidden"' ) + '>');
				// Put its siblings into that container.
				$('#jsps-tab-container-' + i ).append( $siblings );
			});

			// Regroup all the tab containers
			$('[id^="jsps-tab-container-"]').wrapAll('<div id="jsps-all-tabs">');

			// Build the tabs menu
			$('#jsps-all-tabs').before('<div class="jsps-tabs"><ul role="tablist"></ul></div>');
			$('.jsps-tabs ul').append( tabs );

			// Bind all tabs.
			$('.jsps-tabs a').on('click', function(){
				var $_this = $(this),
					tindex = $_this.data('index');

				// Attributes the right current class.
				$('.jsps-tabs a').removeClass('jsps-is-current').attr('aria-selected', 'false');
				$_this.addClass('jsps-is-current').attr('aria-selected', 'true');

				// Show the right tab content
				$('[id^="jsps-tab-container-"]').removeClass('jsps-is-current').attr('hidden', 'hidden');
				$('#jsps-tab-container-' + tindex).addClass('jsps-is-current').removeAttr('hidden')

				jsps_set_current_tab( tindex );
				return false;
			});
		} else {
			console.log('unbuild tabs');
		}
	}

	juiz_maybe_build_tabs();

	$(window).on('resize', function(){
		window.requestAnimationFrame( juiz_maybe_build_tabs );
	});

	/**
	 * Return the current tab index.
	 * @since  1.5
	 */
	function jsps_get_current_tab_selected() {
		if ( typeof( Storage ) === "undefined") {
		    return 1;
		}

		if ( window.localStorage.getItem('jsps-current-tab') ) {
			return parseInt( window.localStorage.getItem('jsps-current-tab') );
		}

		return 1;
	}

	/**
	 * Set the current tab index.
	 * @since  1.5
	 */
	function jsps_set_current_tab( value ) {
		if ( typeof( Storage ) !== "undefined") {
			window.localStorage.setItem('jsps-current-tab', value);
		}
	}

	/**
	 * Other scripts I don't remember what I do with.
	 * @since  1.0
	 * @lastupdate 1.5
	 */

	$('input[disabled]').closest('tr').addClass('juiz_disabled');
	$('#jsps_counter_both').closest('tr').addClass('juiz_hide juiz_counter_option');

	$('input[name="juiz_SPS_settings[juiz_sps_counter]"]').on('change', juiz_on_change_visibility);

	function juiz_on_change_visibility() {
		if ( $('#jsps_counter_y').filter(':checked').length == 1) {
			$('.juiz_counter_option').fadeIn(300);
		}
		else {
			$('.juiz_counter_option').fadeOut(300);
		}
	}
	juiz_on_change_visibility();
});