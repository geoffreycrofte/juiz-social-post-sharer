<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cheatin&#8217; uh?' );
}

/**
 * Get the core array of networks.
 *
 * @param  (array) $core_networks The networks array.
 * @return (array)                The new array of networks.
 *
 * @author Geoffrey Crofte
 * @since  2.0.0
 */
function jsps_get_core_networks( $core_networks = array() ) {
	/**
	 * Filters the list of Core Networks. This hook is used by the Core Plugin itself.
	 * <br> **You shouldn't use this hook**: use {@link jsps_register_custom_network} instead.
	 * 
	 * @hook jsps_register_core_network
	 *
 	 * @since  2.0.0 First version
 	 *
 	 * @param  {array}  cnetw               The list of Core Networks.
 	 * @param  {array}  cnetw.slug          Info of a network where `slug` is its shortname.
 	 * @param  {string} cnetw.slug.name     The visible name of the network.
 	 * @param  {int}    cnetw.slug.visible  `1` to make the network visible by default.
 	 *
 	 * @return {array}                      The list of Core Networks.
	 */
	$core_networks = apply_filters( 'jsps_register_core_network', $core_networks );

	return $core_networks;
}

/**
 * Get the custom array of networks.
 *
 * @param  (array)  $custom_networks The networks array.
 * @return (array)                   The new array of networks.
 *
 * @author Geoffrey Crofte
 * @since  2.0.0
 */
function jsps_get_custom_networks( $custom_networks = array() ) {
	/**
	 * Filters the list of Custom Networks. It's the best way to add new networks.
	 * 
	 * @hook jsps_register_custom_network
	 * @tutorial create-a-custom-button
	 *
 	 * @since  2.0.0 First version
 	 *
 	 * @param  {array}  custnw               The list of Custom Networks.
 	 * @param  {array}  custnw.slug          Info of a network where `slug` is its shortname.
 	 * @param  {string} custnw.slug.name     The visible name of your network.
 	 * @param  {int}    custnw.slug.visible  `1` to make the network visible by default.
 	 * @param  {string} custnw.slug.api_url  The API URL to make the sharing possible. Use `%%url%%` as placeholder for the URL to share, `%%title%%` and `%%excerpt%%` to replace short and long texts if the API allows it.
 	 * @param  {string} custnw.slug.icon     A text free CSS class or a path to a SVG image.
 	 * @param  {string} custnw.slug.title    The value of the title attribute of the button.
 	 * @param  {string} custnw.slug.color    The main color value of the button.
 	 * @param  {string} custnw.slug.hcolor   The :hover color value of the button.
 	 *
 	 * @return {array}                      The list of Custom Network.
	 */
	$custom_networks = apply_filters( 'jsps_register_custom_network', $custom_networks );

	// TODO: check array structure to pop error.
	return $custom_networks;
}