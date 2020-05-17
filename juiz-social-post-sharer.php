<?php /*
	Plugin Name: Juiz Social Post Sharer
	Plugin URI: http://wordpress.org/extend/plugins/juiz-social-post-sharer/
	Description: Add buttons after (or before, or both) your posts to allow visitors share your content (includes no JavaScript mode). You can also use <code>juiz_sps($array)</code> template function or <code>[juiz_sps]</code> shortcode. For more informations see the setting page located in <strong>Settings</strong> submenu.
	Author: Geoffrey Crofte
	Version: 1.5
	Author URI: http://geoffrey.crofte.fr/en
	License: GPLv2 or later
	Text Domain: juiz-social-post-sharer
	Domain Path: /languages



	Copyright 2012-2020  Geoffrey Crofte  (email : support@creativejuiz.com)

	    
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

define( 'JUIZ_SPS_PLUGIN_NAME',	  'Juiz Social Post Sharer' );
define( 'JUIZ_SPS_VERSION',		  '1.5' );
define( 'JUIZ_SPS_FILE',		  __FILE__ );
define( 'JUIZ_SPS_DIRNAME',		  basename( dirname( __FILE__ ) ) );
define( 'JUIZ_SPS_PLUGIN_URL',	  plugin_dir_url( __FILE__ ));
define( 'JUIZ_SPS_PLUGIN_ASSETS', JUIZ_SPS_PLUGIN_URL . 'assets/' );
define( 'JUIZ_SPS_THEMES_FOLDER', JUIZ_SPS_PLUGIN_URL . 'themes/' );
define( 'JUIZ_SPS_SLUG',		  'juiz-social-post-sharer' );
define( 'JUIZ_SPS_SETTING_NAME',  'juiz_SPS_settings' );

// Checking network activation.
$is_nw_activated = function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( JUIZ_SPS_SLUG . '/' . JUIZ_SPS_SLUG . '.php' ) ? true : false;

define( 'JUIZ_SPS_NETWORK_ACTIVATED', $is_nw_activated );

	
// Multilingal.
add_action( 'init', 'make_juiz_sps_multilang' );
function make_juiz_sps_multilang() {
	load_plugin_textdomain( 'juiz-social-post-sharer', false, JUIZ_SPS_DIRNAME.'/languages' );
}

include_once( 'inc/options.php'           );
include_once( 'inc/register-theme.php'    );
include_once( 'inc/register-network.php'  );
include_once( 'inc/register-networks.php' );
include_once( 'inc/utilities.php' );

// Include the admin files in admin and when it's not an AJAX request.
if ( is_admin() || ( defined( 'DOING_AJAX' ) && ! DOING_AJAX ) ) {
	include_once( 'inc/admin/register-settings.php' );
	include_once( 'inc/admin/register-themes.php'   );
	include_once( 'inc/admin/settings.php'          );
	include_once( 'inc/admin/metaboxes.php'         );
	include_once( 'inc/admin/enqueues.php'          );
}

if ( is_admin() ) {
	include_once( 'inc/admin/actions.php' );
}

// Include the AJAX file when you send an Email.
if ( defined('DOING_AJAX') && DOING_AJAX ) {
	include_once( 'inc/front/actions.php' );
}

// Things you do in front.
if ( ! is_admin() ) {
	include_once( 'inc/front/enqueues.php'  );
	include_once( 'inc/front/buttons.php'   );
	include_once( 'inc/front/shortcode.php' );
}
