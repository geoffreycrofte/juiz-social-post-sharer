<?php

// uninstall hook
register_uninstall_hook( JUIZ_SPS_FILE, 'juiz_sps_uninstaller' );
function juiz_sps_uninstaller() {
	delete_option( JUIZ_SPS_SETTING_NAME );
}

// activation hook
register_activation_hook( JUIZ_SPS_FILE, 'juiz_sps_activation' );
function juiz_sps_activation() {

	$juiz_sps_options = get_option ( JUIZ_SPS_SETTING_NAME );

	if ( !is_array($juiz_sps_options) ) {
		
		$default_array = array(
			'juiz_sps_style' 			=> 1,
			'juiz_sps_networks' 		=> array(
											"facebook"		=>	array(1, "Facebook"), 
											"twitter"		=>	array(1, "Twitter"), 
											"google"		=>	array(0, "Google+"),
											"pinterest" 	=>	array(0, "Pinterest"),
											"viadeo" 		=>	array(0, "Viadeo"),
											"linkedin" 		=>	array(0, "LinkedIn"),
											"digg"	 		=>	array(0, "Digg"),
											"stumbleupon"	=>	array(0, "StumbleUpon"),
											"weibo"			=>	array(0, "Weibo"), // new 1.2.0
											"vk"			=>	array(0, "VKontakte"), // new 1.3.0
											"mail"			=>	array(1, "E-mail")
										),
			'juiz_sps_counter'			=> 0,
			'juiz_sps_counter_option'	=> 'both', // new 1.3.3.7
			'juiz_sps_hide_social_name' => 0,
			'juiz_sps_target_link'		=> 0, // new 1.1.0
			'juiz_sps_twitter_user'		=> 'CreativeJuiz',
			'juiz_sps_display_in_types' => array('post'),
			'juiz_sps_display_where'	=> 'bottom',
			'juiz_sps_write_css_in_html'=> 0,
			'juiz_sps_mail_subject'		=> __('Visit this link find on %%siteurl%%',JUIZ_SPS_LANG),
			'juiz_sps_mail_body'		=> __('Hi, I found this information for you : "%%title%%"! This is the direct link: %%permalink%% Have a nice day :)',JUIZ_SPS_LANG),
			'juiz_sps_force_pinterest_snif' => 0,
			'juiz_sps_colors' 			=> array(
											"bg_color"	=> '', 
											"txt_color" => ''
										)
		);
		
		update_option( JUIZ_SPS_SETTING_NAME , $default_array);
	}
	else {

		// if is version under 1.1.0
		if ( !isset($juiz_sps_options['juiz_sps_display_in_types']) ) {
			$new_options = array(
				'juiz_sps_twitter_user'		=> 'CreativeJuiz',
				'juiz_sps_display_in_types' => array('post'),
				'juiz_sps_display_where'	=> 'bottom',
				'juiz_sps_write_css_in_html'=> 0,
				'juiz_sps_mail_subject'		=> __('Visit this link find on %%siteurl%%',JUIZ_SPS_LANG),
				'juiz_sps_mail_body'		=> __('Hi, I found this information for you : "%%title%%"! This is the direct link: %%permalink%% Have a nice day :)',JUIZ_SPS_LANG),
				'juiz_sps_force_pinterest_snif' => 0
			);

			$updated_array = array_merge($juiz_sps_options, $new_options);
			update_option( JUIZ_SPS_SETTING_NAME , $updated_array);
		}

		// if was version under 1.2.3
		if ( !isset($juiz_sps_options['juiz_sps_force_pinterest_snif']) ) {
			$new_options = array (
				'juiz_sps_force_pinterest_snif' => 0
			);

			$updated_array = array_merge($juiz_sps_options, $new_options);
			update_option( JUIZ_SPS_SETTING_NAME , $updated_array);
		}

		// if was version under 1.3.0
		if ( !isset($juiz_sps_options['juiz_sps_networks']['vk']) ) {

			$juiz_sps_options['juiz_sps_networks']['vk'] = array(0, "VKontakte");
			$juiz_sps_options['juiz_sps_colors'] = array("bg_color"=> '', "txt_color" => ''); // for next update

			update_option( JUIZ_SPS_SETTING_NAME ,$juiz_sps_options);
		}

		// if was version under 1.3.3.7
		if ( !isset($juiz_sps_options['juiz_sps_counter_option']) ) {

			$juiz_sps_options['juiz_sps_counter_option'] = 'both';

			update_option( JUIZ_SPS_SETTING_NAME ,$juiz_sps_options);
		}
	}
}

// description setting page
if (!function_exists('juiz_sps_plugin_action_links')) {
	add_filter( 'plugin_action_links_'.plugin_basename( JUIZ_SPS_FILE ), 'juiz_sps_plugin_action_links',  10, 2);
	function juiz_sps_plugin_action_links( $links, $file ) {
		$links[] = '<a href="'.admin_url('options-general.php?page='.JUIZ_SPS_SLUG).'">' . __('Settings') .'</a>';
		return $links;
	}
}


/*
 * Options page
 */
 
 
// Settings page in admin menu
if (!function_exists('add_juiz_sps_settings_page')) {
	add_action('admin_menu', 'add_juiz_sps_settings_page');
	function add_juiz_sps_settings_page() {
		add_submenu_page( 
			'options-general.php', 
			__('Social Post Sharer', JUIZ_SPS_LANG),
			__('Social Post Sharer', JUIZ_SPS_LANG),
			'administrator',
			JUIZ_SPS_SLUG ,
			'juiz_sps_settings_page' 
		);
	}
}

// Some styles for settings page in admin
if ( !function_exists('juiz_sps_custom_admin_header')) {
	add_action( 'admin_head-settings_page_'.JUIZ_SPS_SLUG, 'juiz_sps_custom_admin_header');
	function juiz_sps_custom_admin_header() {
		include_once ('jsps-admin-styles-scripts.php');
	}
}

/*
 *****
 ***** Section for Metabox
 *****
 */
if ( !function_exists('juiz_sps_metaboxes')) {
	add_action('add_meta_boxes','juiz_sps_metaboxes');
	function juiz_sps_metaboxes(){

		$options = get_option( JUIZ_SPS_SETTING_NAME );
		$pts	 = get_post_types( array('public'=> true, 'show_ui' => true, '_builtin' => true) );
		$cpts	 = get_post_types( array('public'=> true, 'show_ui' => true, '_builtin' => false) );

		if (is_array($options['juiz_sps_display_in_types'])) {
			foreach ( $pts as $pt ) {
				if (in_array($pt, $options['juiz_sps_display_in_types'])) {
					add_meta_box('jsps_hide_buttons', __('Sharing buttons', JUIZ_SPS_LANG), 'jsps_hide_buttons_f', $pt, 'side', 'default');
				}
			}
			foreach ( $cpts as $cpt ) {
				if (in_array($cpt, $options['juiz_sps_display_in_types'])) {
					add_meta_box('jsps_hide_buttons', __('Sharing buttons', JUIZ_SPS_LANG), 'jsps_hide_buttons_f', $cpt, 'side', 'default');
				}
			}
		}
	}
}
// build the metabox
if ( !function_exists('jsps_hide_buttons_f')) {
	function jsps_hide_buttons_f($post){
		$checked = (get_post_meta($post->ID,'_jsps_metabox_hide_buttons',true)=='on') ? ' checked="checked"' : '';
		echo '<input id="jsps_metabox_hide_buttons" type="checkbox"'.$checked.' name="jsps_metabox_hide_buttons" /> <label for="jsps_metabox_hide_buttons">'. __('Hide sharing buttons for this post.', JUIZ_SPS_LANG) .'</label>';
	}
}
// save datas
if ( !function_exists('jsps_save_metabox')) {
	add_action('save_post','jsps_save_metabox');
	function jsps_save_metabox($post_ID) {
		$data = isset($_POST['jsps_metabox_hide_buttons']) ? 'on' : 'off';
		update_post_meta($post_ID,'_jsps_metabox_hide_buttons', $data);
	}
}

/*
 *****
 ***** Sections and fields for settings
 *****
 */

function add_juiz_sps_plugin_options() {
	// all options in single registration as array
	register_setting( JUIZ_SPS_SETTING_NAME, JUIZ_SPS_SETTING_NAME, 'juiz_sps_sanitize' );
	
	add_settings_section('juiz_sps_plugin_main', __('Main settings',JUIZ_SPS_LANG), 'juiz_sps_section_text', JUIZ_SPS_SLUG);
	add_settings_field('juiz_sps_style_choice', __('Choose a style to display', JUIZ_SPS_LANG), 'juiz_sps_setting_radio_style_choice', JUIZ_SPS_SLUG, 'juiz_sps_plugin_main');
	add_settings_field('juiz_sps_network_selection', __('Display those following social networks:', JUIZ_SPS_LANG) , 'juiz_sps_setting_checkbox_network_selection', JUIZ_SPS_SLUG, 'juiz_sps_plugin_main');
	add_settings_field('juiz_sps_twitter_user', __('What is your Twitter user name to be mentioned?', JUIZ_SPS_LANG) , 'juiz_sps_setting_input_twitter_user', JUIZ_SPS_SLUG, 'juiz_sps_plugin_main');
	add_settings_field('juiz_sps_temp_submit_1', get_submit_button(__('Save Changes'), 'secondary'), create_function('','return "";'), JUIZ_SPS_SLUG, 'juiz_sps_plugin_main');


	add_settings_section('juiz_sps_plugin_display_in', __('Display settings',JUIZ_SPS_LANG), 'juiz_sps_section_text_display', JUIZ_SPS_SLUG);
	add_settings_field('juiz_sps_display_in_types', __('What type of content must have buttons?',JUIZ_SPS_LANG), 'juiz_sps_setting_checkbox_content_type', JUIZ_SPS_SLUG, 'juiz_sps_plugin_display_in');
	add_settings_field('juiz_sps_display_where', __('Where do you want to display buttons?',JUIZ_SPS_LANG), 'juiz_sps_setting_radio_where', JUIZ_SPS_SLUG, 'juiz_sps_plugin_display_in');
	add_settings_field('juiz_sps_temp_submit_2', get_submit_button(__('Save Changes'), 'secondary'), create_function('','return "";'), JUIZ_SPS_SLUG, 'juiz_sps_plugin_display_in');


	add_settings_section('juiz_sps_plugin_advanced', __('Advanced settings',JUIZ_SPS_LANG), 'juiz_sps_section_text_advanced', JUIZ_SPS_SLUG);
	add_settings_field('juiz_sps_hide_social_name', __('Show only social icon?', JUIZ_SPS_LANG).'<br /><em>('.__('hide text, show it on mouse over or focus', JUIZ_SPS_LANG).')</em>', 'juiz_sps_setting_radio_hide_social_name', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');
	add_settings_field('juiz_sps_target_link', __('Open links in a new window?', JUIZ_SPS_LANG).'<br /><em>('.sprintf(__('adds a %s attribute', JUIZ_SPS_LANG), '<code>target="_blank"</code>').')</em>', 'juiz_sps_setting_radio_target_link', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');
	add_settings_field('juiz_sps_force_pinterest_snif', __('Force Pinterest button sniffing all images of the page?', JUIZ_SPS_LANG).'<br /><em>('.__('need JavaScript', JUIZ_SPS_LANG).')</em>', 'juiz_sps_setting_radio_force_snif', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');
	add_settings_field('juiz_sps_counter', __('Display counter of sharing?',JUIZ_SPS_LANG).'<br /><em>('.__('need JavaScript',JUIZ_SPS_LANG).')</em> <strong>BETA</strong>', 'juiz_sps_setting_radio_counter', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');
	add_settings_field('juiz_sps_counter_option', __('For this counter, you want to display:',JUIZ_SPS_LANG), 'juiz_sps_setting_radio_counter_option', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');
	add_settings_field('juiz_sps_write_css_in_html', __('Write CSS code in HTML head?', JUIZ_SPS_LANG).'<br /><em>('.__('good thing for performance on mobile', JUIZ_SPS_LANG).')</em>', 'juiz_sps_setting_radio_css_in_html', JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');
	add_settings_field('juiz_sps_temp_submit_3', get_submit_button(__('Save Changes'), 'secondary'), create_function('','return "";'), JUIZ_SPS_SLUG, 'juiz_sps_plugin_advanced');


	add_settings_section('juiz_sps_plugin_mail_informations', __('Customize mail texts',JUIZ_SPS_LANG), 'juiz_sps_section_text_mail', JUIZ_SPS_SLUG);
	add_settings_field('juiz_sps_mail_subject', __('Mail subject:',JUIZ_SPS_LANG), 'juiz_sps_setting_input_mail_subject', JUIZ_SPS_SLUG, 'juiz_sps_plugin_mail_informations');
	add_settings_field('juiz_sps_mail_body', __('Mail body:',JUIZ_SPS_LANG), 'juiz_sps_setting_textarea_mail_body', JUIZ_SPS_SLUG, 'juiz_sps_plugin_mail_informations');


}
add_filter('admin_init','add_juiz_sps_plugin_options');


// sanitize posted data

function juiz_sps_sanitize($options) {
	
	if( is_array( $options['juiz_sps_networks'] ) ) {
		
		$temp_array = array('facebook'=>0, 'twitter'=>0, 'google'=>0, 'pinterest'=>0, 'viadeo'=>0, 'linkedin'=>0, 'digg'=>0, 'stumbleupon'=>0, 'weibo'=>0, 'mail' => 0, 'vk' => 0);
		$juiz_sps_opt = get_option ( JUIZ_SPS_SETTING_NAME );

		// new option (1.2.0)
		if ( !in_array('weibo', $juiz_sps_opt['juiz_sps_networks']) ) $juiz_sps_opt['juiz_sps_networks']['weibo'] = array(0, "Weibo");
		// new option (1.3.0)
		if ( !in_array('vk', $juiz_sps_opt['juiz_sps_networks']) ) $juiz_sps_opt['juiz_sps_networks']['vk'] = array(0, "VKontakte");

		foreach( $options['juiz_sps_networks'] as $nw )
			$temp_array[$nw]=1;

		foreach($temp_array as $k => $v)
			$juiz_sps_opt['juiz_sps_networks'][$k][0] = $v;

		$newoptions['juiz_sps_networks'] = $juiz_sps_opt['juiz_sps_networks'];
	}


	$newoptions['juiz_sps_style'] = $options['juiz_sps_style']>=1 && $options['juiz_sps_style']<=6 ? (int)$options['juiz_sps_style'] : 1;
	$newoptions['juiz_sps_hide_social_name'] = (int)$options['juiz_sps_hide_social_name']==1 ? 1 : 0;
	$newoptions['juiz_sps_target_link'] = (int)$options['juiz_sps_target_link']==1 ? 1 : 0;
	$newoptions['juiz_sps_counter'] = (int)$options['juiz_sps_counter']==1 ? 1 : 0;

	// new options (1.1.0)
	$newoptions['juiz_sps_write_css_in_html'] = (int)$options['juiz_sps_write_css_in_html']==1 ? 1 : 0;
	$newoptions['juiz_sps_twitter_user'] = preg_replace( "#@#", '', sanitize_key( $options['juiz_sps_twitter_user'] ) );
	$newoptions['juiz_sps_mail_subject'] = sanitize_text_field( $options['juiz_sps_mail_subject'] );
	$newoptions['juiz_sps_mail_body'] = sanitize_text_field( $options['juiz_sps_mail_body'] );

	if ( is_array($options['juiz_sps_display_in_types']) && count($options['juiz_sps_display_in_types']) > 0 ) {
		$newoptions['juiz_sps_display_in_types'] = $options['juiz_sps_display_in_types'];
	}
	else {
		wp_redirect( admin_url('options-general.php?page='.JUIZ_SPS_SLUG.'&message=1337') );
		exit;
	}
	$newoptions['juiz_sps_display_where'] = in_array($options['juiz_sps_display_where'], array('bottom', 'top', 'both', 'nowhere')) ? $options['juiz_sps_display_where'] : 'bottom';
	

	// new options (1.2.5)
	$newoptions['juiz_sps_force_pinterest_snif'] = (int)$options['juiz_sps_force_pinterest_snif']==1 ? 1 : 0;

	// new options (1.3.3.7)
	$newoptions['juiz_sps_counter_option'] = in_array($options['juiz_sps_counter_option'], array('both', 'total', 'subtotal')) ? $options['juiz_sps_counter_option'] : 'both';
	
	return $newoptions;
}

// first section text
if( !function_exists('juiz_sps_section_text')) {
function juiz_sps_section_text() {
	echo '<p class="juiz_sps_section_intro">'. __('Here, you can modify default settings of the SPS plugin', JUIZ_SPS_LANG) .'</p>';
}
}

// radio fields styles choice
if( !function_exists('juiz_sps_setting_radio_style_choice')) {
function juiz_sps_setting_radio_style_choice() {

	$options = get_option( JUIZ_SPS_SETTING_NAME );
	if ( is_array($options) ) {
		$n1 = $n2 = $n3 = $n4 = $n5 = $n6 = "";
		${'n'.$options['juiz_sps_style']} = " checked='checked'";
	
		echo '<p class="juiz_sps_styles_options">
					<input id="jsps_style_1" value="1" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_style]" type="radio" '.$n1.' />
					<label for="jsps_style_1"><span class="juiz_sps_demo_styles"></span><br /><span class="juiz_sps_style_name">'. __('Juizy Light Tone', JUIZ_SPS_LANG) . '</span></label>
				</p>
				<p class="juiz_sps_styles_options">
					<input id="jsps_style_2" value="2" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_style]" type="radio" '.$n2.' />
					<label for="jsps_style_2"><span class="juiz_sps_demo_styles"></span><br /><span class="juiz_sps_style_name">'. __('Juizy Light Tone Reverse', JUIZ_SPS_LANG) . '</span></label>
				</p>
				<p class="juiz_sps_styles_options">
					<input id="jsps_style_3" value="3" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_style]" type="radio" '.$n3.' />
					<label for="jsps_style_3"><span class="juiz_sps_demo_styles"></span><br /><span class="juiz_sps_style_name">'. __('Blue Metro Style', JUIZ_SPS_LANG) . '</span></label>
				</p>
				<p class="juiz_sps_styles_options">
					<input id="jsps_style_4" value="4" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_style]" type="radio" '.$n4.' />
					<label for="jsps_style_4"><span class="juiz_sps_demo_styles"></span><br /><span class="juiz_sps_style_name">'. __('Gray Metro Style', JUIZ_SPS_LANG) . '</span></label>
				</p>
				<p class="juiz_sps_styles_options">
					<input id="jsps_style_5" value="5" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_style]" type="radio" '.$n5.' />
					<label for="jsps_style_5"><span class="juiz_sps_demo_styles"></span><br /><span class="juiz_sps_style_name">'. __('Modern Style', JUIZ_SPS_LANG) . ' '.__('by', JUIZ_SPS_LANG).' <a href="http://tonytrancard.fr" target="_blank">Tony Trancard</a></span></label>
				</p>
				<p class="juiz_sps_styles_options">
					<input id="jsps_style_6" value="6" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_style]" type="radio" '.$n6.' />
					<label for="jsps_style_6"><span class="juiz_sps_demo_styles"></span><br /><span class="juiz_sps_style_name">'. __('Black', JUIZ_SPS_LANG) . ' '.__('by', JUIZ_SPS_LANG).' <a href="http://fandia.w.pw" target="_blank">Fandia</a></span></label>
				</p>';
	}
}
}


// checkboxes fields for networks
if( !function_exists('juiz_sps_setting_checkbox_network_selection')) {
function juiz_sps_setting_checkbox_network_selection() {
	$y = $n = '';
	$options = get_option( JUIZ_SPS_SETTING_NAME );
	if ( is_array($options) ) {
		foreach($options['juiz_sps_networks'] as $k => $v) {

			$is_checked = ($v[0] == 1) ? ' checked="checked"' : '';
			$is_js_test = ($k == 'pinterest') ? ' <em>('.__('uses JavaScript to work',JUIZ_SPS_LANG).')</em>' : '';
			$network_name = isset($v[1]) ? $v[1] : $k;

			echo '<p class="juiz_sps_options_p">
					<input id="jsps_network_selection_'.$k.'" value="'.$k.'" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_networks][]" type="checkbox"
				'.$is_checked.' />
			  		<label for="jsps_network_selection_'.$k.'"><span class="jsps_demo_icon jsps_demo_icon_'.$k.'"></span>'.$network_name.''.$is_js_test.'</label>
			  	</p>';
		}
		if ( !is_array($options['juiz_sps_networks']['weibo']) ) echo '<p class="juiz_sps_options_p"><input id="jsps_network_selection_weibo" value="weibo" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_networks][]" type="checkbox"> <label for="jsps_network_selection_weibo"><span class="jsps_demo_icon jsps_demo_icon_weibo"></span>Weibo</label> <em class="jsps_new">('.__('New social network!', JUIZ_SPS_LANG).')</em></p>';
	}
}
}

// input for twitter username
if( !function_exists('juiz_sps_setting_input_twitter_user')) {
function juiz_sps_setting_input_twitter_user() {
	$options = get_option( JUIZ_SPS_SETTING_NAME );
	if ( is_array($options) ) {
		$username = isset($options['juiz_sps_twitter_user']) ? $options['juiz_sps_twitter_user'] : '';
	echo '<p class="juiz_sps_options_p">
			<input id="juiz_sps_twitter_user" value="'.esc_attr($username).'" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_twitter_user]" type="text"> <em>('.__('Username without "@"', JUIZ_SPS_LANG).')</em>
	  	</p>';
	}
}
}


// Advanced section text
if( !function_exists('juiz_sps_section_text_display')) {
function juiz_sps_section_text_display() {
	echo '<p class="juiz_sps_section_intro">'. __('You can choose precisely the types of content that will benefit from the sharing buttons.', JUIZ_SPS_LANG) .'</p>';
}
}
// checkbox for type of content
if( !function_exists('juiz_sps_setting_checkbox_content_type')) {
function juiz_sps_setting_checkbox_content_type() {
	$pts	= get_post_types( array('public'=> true, 'show_ui' => true, '_builtin' => true) );
	$cpts	= get_post_types( array('public'=> true, 'show_ui' => true, '_builtin' => false) );
	$options = get_option( JUIZ_SPS_SETTING_NAME );
	$all_lists_icon = '<img class="jsps_icon" alt="&#8226; " src="'.JUIZ_SPS_PLUGIN_URL.'img/icon-list.png"/>';
	$all_lists_selected = '';
	if (is_array($options['juiz_sps_display_in_types'])) {
		$all_lists_selected = in_array('all_lists', $options['juiz_sps_display_in_types']) ? 'checked="checked"': '';
	}

	if( is_array($options) && isset($options['juiz_sps_display_in_types']) && is_array($options['juiz_sps_display_in_types'])) {
		
		global $wp_post_types;
		$no_icon = '<span class="jsps_no_icon">&#160;</span>';

		// classical post type listing
		foreach ( $pts as $pt ) {

			$selected = in_array($pt, $options['juiz_sps_display_in_types']) ? 'checked="checked"' : '';

			$icon = isset($wp_post_types[$pt]->menu_icon) && $wp_post_types[$pt]->menu_icon ? '<img alt="&#8226; " src="'.esc_url($wp_post_types[$pt]->menu_icon).'"/>' : $no_icon;
			echo '<p><input type="checkbox" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_display_in_types][]" id="'.$pt.'" value="'.$pt.'" '.$selected.'> <label for="'.$pt.'">'.$icon.' '.$wp_post_types[$pt]->label . '</label></p>';
		}

		// custom post types listing
		if ( is_array($cpts) && !empty($cpts) ) {
			foreach ( $cpts as $cpt ) {

				$selected = in_array($cpt, $options['juiz_sps_display_in_types']) ? 'checked="checked"' : '';

				$icon = isset($wp_post_types[$cpt]->menu_icon) && $wp_post_types[$cpt]->menu_icon ? '<img alt="&#8226; " src="'.esc_url($wp_post_types[$cpt]->menu_icon).'"/>' : $no_icon;
				echo '<p><input type="checkbox" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_display_in_types][]" id="'.$cpt.'" value="'.$cpt.'" '.$selected.'> <label for="'.$cpt.'">'.$icon.' '.$wp_post_types[$cpt]->label . '</label></p>';
			}
		}
	}
	echo '<p><input type="checkbox" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_display_in_types][]" id="all_lists" value="all_lists" '.$all_lists_selected.'> <label for="all_lists">'.$all_lists_icon.' '. sprintf(__('Lists of articles %s(blog, archives, search results, etc.)%s',JUIZ_SPS_LANG), '<em>','</em>') . '</label></p>';
}
}

// where display buttons
// radio fields styles choice
if( !function_exists('juiz_sps_setting_radio_where')) {
function juiz_sps_setting_radio_where() {

	$options = get_option( JUIZ_SPS_SETTING_NAME );

	$w_bottom = $w_top = $w_both = $w_nowhere = "";
	if ( is_array($options) && isset($options['juiz_sps_display_where']) )
		${'w_'.$options['juiz_sps_display_where']} = " checked='checked'";
	
	echo '	<input id="jsps_w_b" value="bottom" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_display_where]" type="radio" '.$w_bottom.' />
			<label for="jsps_w_b">'. __('Content bottom', JUIZ_SPS_LANG) . '</label>
			
			<input id="jsps_w_t" value="top" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_display_where]" type="radio" '.$w_top.' />
			<label for="jsps_w_t">'. __('Content top', JUIZ_SPS_LANG) . '</label>
			
			<input id="jsps_w_2" value="both" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_display_where]" type="radio" '.$w_both.' />
			<label for="jsps_w_2">'. __('Both', JUIZ_SPS_LANG) . '</label>

			<input id="jsps_w_0" value="nowhere" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_display_where]" type="radio" '.$w_nowhere.' />
			<label for="jsps_w_0">'. __("I'm a ninja, I want to use the shortcode only!", JUIZ_SPS_LANG) . '</label>';
			// nowhere option, new in 1.2.2
}
}



// Advanced section text
if( !function_exists('juiz_sps_section_text_advanced')) {
function juiz_sps_section_text_advanced() {
	echo '<p class="juiz_sps_section_intro">'. __('Modify advanced settings of the plugin. Some of them needs JavaScript (only one file loaded)', JUIZ_SPS_LANG) .'</p>';
}
}


// radio fields Y or N for hide text
if( !function_exists('juiz_sps_setting_radio_hide_social_name')) {
function juiz_sps_setting_radio_hide_social_name() {
	$y = $n = '';
	$options = get_option( JUIZ_SPS_SETTING_NAME );

	if ( is_array($options) )
		(isset($options['juiz_sps_hide_social_name']) AND $options['juiz_sps_hide_social_name']==1) ? $y = " checked='checked'" : $n = " checked='checked'";
	
	echo '	<input id="jsps_hide_name_y" value="1" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_hide_social_name]" type="radio" '.$y.' />
			<label for="jsps_hide_name_y">'. __('Yes', JUIZ_SPS_LANG) . '</label>
			
			<input id="jsps_hide_name_n" value="0" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_hide_social_name]" type="radio" '.$n.' />
			<label for="jsps_hide_name_n">'. __('No', JUIZ_SPS_LANG) . '</label>

			<span class="juiz_sps_demo_hide"></span>';
}
}

// radio fields Y or N for target _blank
if( !function_exists('juiz_sps_setting_radio_target_link')) {
function juiz_sps_setting_radio_target_link() {
	$y = $n = '';
	$options = get_option( JUIZ_SPS_SETTING_NAME );

	if ( is_array($options) )
		(isset($options['juiz_sps_target_link']) AND $options['juiz_sps_target_link']==1) ? $y = " checked='checked'" : $n = " checked='checked'";
	
	echo "	<input id='jsps_target_link_y' value='1' name='".JUIZ_SPS_SETTING_NAME."[juiz_sps_target_link]' type='radio' ".$y." />
			<label for='jsps_target_link_y'>". __('Yes', JUIZ_SPS_LANG) . "</label>
			
			<input id='jsps_target_link_n' value='0' name='".JUIZ_SPS_SETTING_NAME."[juiz_sps_target_link]' type='radio' ".$n." />
			<label for='jsps_target_link_n'>". __('No', JUIZ_SPS_LANG) . "</label>";
}
}

// radio fields Y or N for pinterest sniffing
if( !function_exists('juiz_sps_setting_radio_force_snif')) {
function juiz_sps_setting_radio_force_snif() {
	$y = $n = '';
	$options = get_option( JUIZ_SPS_SETTING_NAME );

	if ( is_array($options) )
		(isset($options['juiz_sps_force_pinterest_snif']) AND $options['juiz_sps_force_pinterest_snif']==1) ? $y = " checked='checked'" : $n = " checked='checked'";
	
	echo '	<input id="jsps_forcer_snif_y" value="1" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_force_pinterest_snif]" type="radio" '.$y.' />
			<label for="jsps_forcer_snif_y">'. __('Yes', JUIZ_SPS_LANG) . '</label>
			
			<input id="jsps_forcer_snif_n" value="0" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_force_pinterest_snif]" type="radio" '.$n.' />
			<label for="jsps_forcer_snif_n">'. __('No', JUIZ_SPS_LANG) . '</label>';
}
}

// radio fields Y or N for counter
if( !function_exists('juiz_sps_setting_radio_counter')) {
function juiz_sps_setting_radio_counter() {

	$y = $n = '';
	$options = get_option( JUIZ_SPS_SETTING_NAME );

	if ( is_array($options) )
		(isset($options['juiz_sps_counter']) AND $options['juiz_sps_counter']==1) ? $y = ' checked="checked"' : $n = ' checked="checked"';
	
	echo '	<input id="jsps_counter_y" value="1" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_counter]" type="radio" '.$y.' />
			<label for="jsps_counter_y">'. __('Yes', JUIZ_SPS_LANG) . '</label>
			
			<input id="jsps_counter_n" value="0" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_counter]" type="radio" '.$n.' />
			<label for="jsps_counter_n">'. __('No', JUIZ_SPS_LANG) . '</label>';
}
}

// radio fields for what to display as counter
if( !function_exists('juiz_sps_setting_radio_counter_option')) {
function juiz_sps_setting_radio_counter_option() {

	$options 	= get_option( JUIZ_SPS_SETTING_NAME );
	if ( is_array($options) ) {
		$both 		= (isset($options['juiz_sps_counter_option']) && $options['juiz_sps_counter_option'] == 'both') ? ' checked="checked"' : '';
		$total 		= (isset($options['juiz_sps_counter_option']) && $options['juiz_sps_counter_option'] == 'total') ? ' checked="checked"' : '';
		$subtotal 	= (isset($options['juiz_sps_counter_option']) && $options['juiz_sps_counter_option'] == 'subtotal') ? ' checked="checked"' : '';

		if ($both == '' && $total == '' && $subtotal == '') $both = 'checked="checked"';
	}
	
	echo '	<input id="jsps_counter_both" value="both" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_counter_option]" type="radio" '.$both.' />
			<label for="jsps_counter_both">'. __('Total &amp; Sub-totals', JUIZ_SPS_LANG) . '</label>
			
			<input id="jsps_counter_total" value="total" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_counter_option]" type="radio" '.$total.' />
			<label for="jsps_counter_total">'. __('Only Total', JUIZ_SPS_LANG) . '</label>

			<input id="jsps_counter_subtotal" value="subtotal" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_counter_option]" type="radio" '.$subtotal.' />
			<label for="jsps_counter_subtotal">'. __('Only Sub-totals', JUIZ_SPS_LANG) . '</label>';
}
}

// radio to display CSS in html head or not
if( !function_exists('juiz_sps_setting_radio_css_in_html')) {
function juiz_sps_setting_radio_css_in_html() {
	$y = $n = '';
	$options = get_option( JUIZ_SPS_SETTING_NAME );

	if ( is_array($options) )
		(isset($options['juiz_sps_write_css_in_html']) AND $options['juiz_sps_write_css_in_html']==1) ? $y = " checked='checked'" : $n = " checked='checked'";
	
	echo '	<em style="color:#777;">' . __('This option will be enabled for a next version',JUIZ_SPS_LANG) . '</em><br />
			<input id="jsps_target_link_y" value="1" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_write_css_in_html]" type="radio" '.$y.' disabled="disabled" />
			<label style="color:#777;" for="jsps_target_link_y">'. __('Yes', JUIZ_SPS_LANG) . '</label>
			
			<input id="jsps_target_link_n" value="0" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_write_css_in_html]" type="radio" '.$n.' disabled="disabled" />
			<label style="color:#777;" for="jsps_target_link_n">'. __('No', JUIZ_SPS_LANG) . '</label>';
}
}


// Mail section text
if( !function_exists('juiz_sps_section_text_mail')) {
function juiz_sps_section_text_mail() {
	echo '<p class="juiz_sps_section_intro">'. __('You can customize texts to display when visitors share your content by mail button', JUIZ_SPS_LANG) .'</p>';
	echo '<p class="juiz_sps_section_intro">'. sprintf(__('To perform customization, you can use %s%%%%title%%%%%s, %s%%%%siteurl%%%%%s or %s%%%%permalink%%%%%s variables.', JUIZ_SPS_LANG), '<code>', '</code>', '<code>', '</code>', '<code>', '</code>') .'</p>';
}
}
if( !function_exists('juiz_sps_setting_input_mail_subject')) {
function juiz_sps_setting_input_mail_subject() {
	$options = get_option( JUIZ_SPS_SETTING_NAME );
	if(isset($options['juiz_sps_mail_subject']))
		echo '<input id="juiz_sps_mail_subject" value="'.esc_attr($options['juiz_sps_mail_subject']).'" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_mail_subject]" type="text">';
}
}
if( !function_exists('juiz_sps_setting_textarea_mail_body')) {
function juiz_sps_setting_textarea_mail_body() {
	$options = get_option( JUIZ_SPS_SETTING_NAME );
	if(isset($options['juiz_sps_mail_body']))
		echo '<textarea id="juiz_sps_mail_body" name="'.JUIZ_SPS_SETTING_NAME.'[juiz_sps_mail_body]">'.esc_textarea($options['juiz_sps_mail_body']).'</textarea>';
}
}

// The page layout/form
if( !function_exists('juiz_sps_settings_page')) {
function juiz_sps_settings_page() {
	?>
	<div id="juiz-sps" class="wrap">
		<div id="icon-options-general" class="icon32">&nbsp;</div>
		<h2><?php _e('Manage Juiz Social Post Sharer', JUIZ_SPS_LANG) ?> <small>v. <?php echo JUIZ_SPS_VERSION; ?></small></h2>

		<?php if ( isset($_GET['message']) && $_GET['message'] = '1337') { ?>
		<div class="error settings-error">
			<p>
				<strong><?php echo sprintf(__('You must chose at least one %stype of content%s.', JUIZ_SPS_LANG), '<a href="#post">', '</a>'); ?></strong><br>
				<em><?php _e('Is you don\'t want to use this plugin more longer, go to Plugins section and deactivate it.',JUIZ_SPS_LANG); ?></em></p>
		</div>
		<?php } ?>
		<p class="jsps_info">
			<?php echo sprintf(__('You can use %s[juiz_sps]%s or %s[juiz_social]%s shortcode with an optional attribute "buttons" listing the social networks you want.',JUIZ_SPS_LANG), '<code>','</code>', '<code>','</code>'); ?>
			<br />
			<?php _e('Example with all the networks available:',JUIZ_SPS_LANG) ?>
			<code>[juiz_sps buttons="facebook, twitter, google, pinterest, digg, weibo, linkedin, viadeo, stumbleupon, vk, mail"]</code>
		</p>
		<form method="post" action="options.php">
			<?php
				settings_fields( JUIZ_SPS_SETTING_NAME );
				do_settings_sections( JUIZ_SPS_SLUG );
				submit_button();
			?>
		</form>

		<p class="juiz_bottom_links">
			<em><?php _e('Like it? Support this plugin! Thank you.', JUIZ_SPS_LANG) ?></em>
			<a class="juiz_paypal" target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=P39NJPCWVXGDY&amp;lc=FR&amp;item_name=Juiz%20Social%20Post%20Sharer%20%2d%20WP%20Plugin&amp;item_number=%23wp%2djsps&amp;currency_code=EUR&amp;bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted"><?php _e('Donate', JUIZ_SPS_LANG) ?></a>
			<a class="juiz_twitter" target="_blank" href="https://twitter.com/intent/tweet?source=webclient&amp;hastags=WordPress,Plugin&amp;text=Juiz%20Social%20Post%20Sharer%20is%20an%20awesome%20WordPress%20plugin%20to%20share%20content!%20Try%20it!&amp;url=http://wordpress.org/extend/plugins/juiz-social-post-sharer/&amp;related=geoffrey_crofte&amp;via=geoffrey_crofte"><?php _e('Tweet it', JUIZ_SPS_LANG) ?></a>

			<a class="juiz_rate" target="_blank" href="http://wordpress.org/support/view/plugin-reviews/juiz-social-post-sharer"><?php _e('Rate it', JUIZ_SPS_LANG) ?></a>
			<a href="https://flattr.com/submit/auto?user_id=CreativeJuiz&amp;url=http://wordpress.org/plugins/juiz-social-post-sharer/&amp;title=Juiz%20Social%20Post%20Sharer%20-%20WordPress%20Plugin&amp;description=Awesome%20WordPress%20Plugin%20helping%20you%20to%20add%20buttons%20at%20the%20beginning%20or%20the%20end%20of%20your%20WordPress%20contents%20easily&amp;tags=WordPress,Social,Share,Buttons,Network,Twitter,Facebook,Linkedin&amp;category=software" lang="en" hreflang="en"><img src="https://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this!" width="93" height="20" style="vertical-align:-6px;"></a>
			<a target="_blank" href="<?php echo JUIZ_SPS_PLUGIN_URL; ?>documentation.html"><?php _e('Documentation',JUIZ_SPS_LANG); ?> (en)</a>
		</p>
	</div>
	<?php
}
}