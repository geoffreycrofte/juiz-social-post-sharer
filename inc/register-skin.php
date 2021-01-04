<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Get the core array of skins.
 * 
 * @param  (array) $core_skins The skins array.
 * @return (array)             Filtered new array of Core Skins.
 *
 * @since  2.0.0
 * @author Geoffrey Crofte
 */
function jsps_get_core_skins( $core_skins = array() ) {

	/**
	 * Filters the list of Core Skins. This hook is used by the Core Plugin itself.
	 * <br> **You shouldn't use this hook**: use {@link jsps_register_custom_skin} instead.
	 * 
	 * @hook jsps_register_core_skin
	 *
 	 * @since  2.0.0 First version
 	 *
 	 * @param  {array}  cskins=array()               The list of Core Skins.
 	 * @param  {array}  cskins.slug                  Info of a skin where `slug` is its shortname.
 	 * @param  {string} cskins.slug.name             The visible name of the skin.
 	 * @param  {string} cskins.slug.demo_url_2x      The image used for the option in admin.
 	 * @param  {boolean} cskins.slug.support_compact Does it have compact mode (tinier verion) support. 
 	 * @param  {boolean} cskins.slug.support_hidden_name Does it have hidden name support. 
 	 *
 	 * @return {array}                    The list of Core Skins.
	 */
	$core_skins = apply_filters( 'jsps_register_core_skin', $core_skins );
	return $core_skins;
}

/**
 * Get the custom array of skins.
 * 
 * @param  (array) $custom_skins The custom skins array.
 * @return (array)               Filtered new array of Custom Skins.
 *
 * @since  2.0.0
 * @author Geoffrey Crofte
 */
function jsps_get_custom_skins( $custom_skins = array() ) {

	/**
	 * Filters the list of Custom Skins. It's the best way to add visual themes via external plugins or themes. You can also add skins directly in your theme. Follow this tutorial to do it: {@tutorial create-buttons-skin}  
	 * 
	 * @hook jsps_register_custom_skin
	 * @tutorial create-buttons-skin
	 *
 	 * @since  2.0.0 First version
 	 *
 	 * @param  {array}  cskins=array()          The list of Custom Skins.
 	 * @param  {array}  cskins.slug             Info of a skin where `slug` is its shortname.
 	 * @param  {string} cskins.slug.name        The visible name of the skin.
 	 * @param  {string} cskins.slug.author      The Name of the author displayed in the admin.
 	 * @param  {string} cskins.slug.author_url  The URL of the author displayed in the admin.
 	 * @param  {string} cskins.slug.css_url     The CSS URL used to style the skin.
 	 * @param  {string} cskins.slug.demo_url    The image of the demo to display it in the admin.
 	 * @param  {string} cskins.slug.demo_url_2x      The image used for the option in admin.
 	 * @param  {boolean} cskins.slug.support_compact Does it have compact mode (tinier verion) support. 
 	 *
 	 * @return {array}                          The list of Core Skins.
	 */
	$custom_skins = apply_filters( 'jsps_register_custom_skin', $custom_skins );

	// TODO: check array structure to pop error.
	return $custom_skins;
}