<?php
/**
 * @author Koen Van den Wijngaert <koen@siteoptimo.com>
 */

function wcgw_get_template( $template ) {
	$plugin_path = trailingslashit( wcgw_get_plugin_path() );

	require_once $plugin_path . 'templates/' . $template . '.php';
}

function wcgw_get_plugin_path() {
	global $WCGW;

	return $WCGW->plugin_path();
}

function wcgw_get_option( $name, $default = "" ) {
	$filtered = get_option( apply_filters( 'wcgw_get_option', $name ), $default );

	if(empty($filtered)) {
		return get_option($name, $default);
	}

	return $filtered;
}