<?php

/*
  Plugin Name: LS Snacktools
  Plugin URI:
  Description:  Add oEmbed support for Slidesnack.com in WordPress posts, pages and custom post types.
  Tags: snacktools, slidesnack, snack.to
  Version: 1.0
  Requires at least: WordPress  3.5
  Tested up to: WordPress 4.2.2
  Author: lenasterg, NTS on CTI.gr
  Author URI: 
  Text Domain: ls_snack
  Domain Path: /languages/
  License: GNU/GPL 3
 */

add_action( 'plugins_loaded', 'ls_snack_i18n_init' );

function ls_snack_i18n_init () {
    load_plugin_textdomain('ls_snack', false, basename( dirname( __FILE__ ) ) . '/languages/');
}

 
/**
 * @version 1, lenasterg
 * @since 20/7/2015
 */
wp_embed_register_handler( 'snack', '/http:\/\/(snack.to|www.slidesnack.com|share.snacktools.com)\/(\w+)/i', 'wp_embed_handler_ls_snack' );

function wp_embed_handler_ls_snack( $matches, $attr, $url, $rawattr ) {
    $args = wp_parse_args( $args, wp_embed_defaults() );

    $hash = ls_after_last( '=', $url );
    if ( is_bool( $hash ) ) {
	$hash = ls_after_last( '/', $url );
    }
	$mikos=strlen( $hash );
	
	//They drop the 1st letter if the $hash if too long (?!?!)
	if ( $mikos > 22 ) {
		$hash = substr( $hash, 1 );
    }
		
    $width = $args['width'];
    $height = floor( $width * 402 / 485 );

    $embed = '<div align="center">'
	    . '<iframe src="http://files.slidesnack.com/iframe/embed.html?hash=' . $hash . '&amp;'
	    . 'wmode=transparent&amp;bgcolor=EEEEEE&amp;type=presentation&amp;t=1355911920" '
	    . 'seamless="seamless" scrolling="no" frameborder="0" allowtransparency="true" style="width: ' . ($width - 5) . 'px; height: ' . ($height - 5) . 'px;"></iframe>'
	    . '	<br/><a href="' . $url . '">'.__('Watch it in slidesnack.com', 'ls_snack').'</a></div>';

    return apply_filters( 'embed_ls_snack', $embed, $matches, $attr, $url, $rawattr );
}


if ( ! function_exists( 'ls_after_last' ) ) {
function ls_after_last( $this, $inthat ) {
    if ( ! is_bool( strrevpos( $inthat, $this ) ) ) {
	return substr( $inthat, strrevpos( $inthat, $this ) + strlen( $this ) );
    }
    return false;
}
}

if ( ! function_exists( 'strrevpos' ) ) {
// use strrevpos function in case your php version does not include it
    function strrevpos( $instr, $needle ) {
	$rev_pos = strpos( strrev( $instr ), strrev( $needle ) );
	if ( $rev_pos === false ) {
	    return false;
	} else {
	    return strlen( $instr ) - $rev_pos - strlen( $needle );
	}
    }
}
