<?php
/*
Plugin Name: Contact Form L4L
Description: Custom Contact From For L4L
Author: Developer
Author URI: 
Version: 1.0.0
*/


define( 'WPCFL4L_PLUGIN', __FILE__ );

define( 'WPCFL4L_PLUGIN_BASENAME', plugin_basename( WPCFL4L_PLUGIN ) );

define( 'WPCFL4L_PLUGIN_NAME', trim( dirname( WPCFL4L_PLUGIN_BASENAME ), '/' ) );

define( 'WPCFL4L_PLUGIN_DIR', untrailingslashit( dirname( WPCFL4L_PLUGIN ) ) );

define( 'WPCFL4L_PLUGIN_VIEWS_DIR', WPCFL4L_PLUGIN_DIR . '/views' );

define( 'WPCFL4L_PLUGIN_MODULES_DIR', WPCFL4L_PLUGIN_DIR . '/modules' );



function l4l_plugin_url( $path = '' ) {
	$url = plugins_url( $path, WPCFL4L_PLUGIN );

	if ( is_ssl()
	and 'http:' == substr( $url, 0, 5 ) ) {
		$url = 'https:' . substr( $url, 5 );
	}

	return $url;
}



require_once WPCFL4L_PLUGIN_DIR . '/load.php';

