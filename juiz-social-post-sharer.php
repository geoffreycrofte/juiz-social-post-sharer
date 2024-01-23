<?php /*
	Plugin Name: Nobs • Share Buttons
	Plugin URI: http://wordpress.org/extend/plugins/juiz-social-post-sharer/
	Description: Add buttons after (or before, or both) your posts to allow visitors share your content (includes no JavaScript mode). You can also use <code>juiz_sps($array)</code> template function or <code>[juiz_sps]</code> shortcode. For more informations see the setting page located in <strong>Settings</strong> submenu.
	Author: Geoffrey Crofte
	Version: 2.3.3
	Author URI: https://geoffrey.crofte.fr/en
	License: GPLv2 or later
	Text Domain: juiz-social-post-sharer

	
	Copyright 2012  Geoffrey Crofte  (email : support@creativejuiz.com)

	    
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
	die( 'Cheatin’ uh?' );
}

define( 'JUIZ_SPS_PLUGIN_NAME',	  'Nobs • Share Buttons' );
define( 'JUIZ_SPS_VERSION',		  '2.3.3' );
define( 'JUIZ_SPS_FILE',		  __FILE__ );
define( 'JUIZ_SPS_DIRNAME',		  basename( dirname( __FILE__ ) ) );
define( 'JUIZ_SPS_PLUGIN_URL',	  plugin_dir_url( __FILE__ ));
define( 'JUIZ_SPS_PLUGIN_ASSETS', JUIZ_SPS_PLUGIN_URL . 'assets/' );
define( 'JUIZ_SPS_SKINS_FOLDER',  JUIZ_SPS_PLUGIN_URL . 'skins/' );
define( 'JUIZ_SPS_SLUG',		  'juiz-social-post-sharer' );
define( 'JUIZ_SPS_SETTING_NAME',  'juiz_SPS_settings' );

include_once( 'inc/options.php'                       );
include_once( 'inc/register-skin.php'                 );
include_once( 'inc/register-skins.php'                );
include_once( 'inc/register-network.php'              );
include_once( 'inc/register-networks.php'             );
include_once( 'inc/utilities.php'                     );
include_once( 'class/nobs-license-manager-client.php' );

// Include the admin files in admin and when it's not an AJAX request.
if ( is_admin() || ( defined( 'DOING_AJAX' ) && ! DOING_AJAX ) ) {
	include_once( 'inc/admin/register-settings.php' );
	include_once( 'inc/admin/settings.php'          );
	include_once( 'inc/admin/upgrade-notice.php'    );
	include_once( 'inc/admin/metaboxes.php'         );
	include_once( 'inc/admin/enqueues.php'          );
	include_once( 'inc/admin/welcome.php'           );
}

if ( is_admin() ) {
	include_once( 'inc/admin/actions.php' );
}

include_once( 'inc/front/actions.php' );
include_once( 'inc/front/third-parties/wp-job-manager.php' );

// Things you do in front, or Gutenberg? can 
if ( ! is_admin() ) {
//if ( ! is_admin() || ( is_admin() && method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) ) {
	include_once( 'inc/front/enqueues.php'  );
	include_once( 'inc/front/buttons.php'   );
	include_once( 'inc/front/shortcode.php' );
}

// New Gutenberg blocks
include_once( 'inc/blocks/register-blocks.php' );
