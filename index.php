<?php
/*
Plugin Name: GetBookingsWp - Appointments & Bookings Plugin Basic Version
Plugin URI: https://getbookingswp.com
Description: Booking Plugin for every service provider: dentists, medical services, hair & beauty salons, repair services, event planners, rental agencies, educational services, government agencies, school counsellors and more. This plugin allows you to manage your appointments easily.
Tested up to: 6.6
Version: 1.1.27
Author: Istmo Plugins
Domain Path: /languages
Text Domain: get-bookings-wp
Author URI: https://getbookingswp.com/
*/
define('getbookingpro_url',plugin_dir_url(__FILE__ ));
define('getbookingpro_path',plugin_dir_path(__FILE__ ));
define('GETBWP_SETTINGS_URL',"?page=getbookingswp&tab=welcome");

$plugin = plugin_basename(__FILE__);

/* Loading Function */
require_once (getbookingpro_path . 'functions/functions.php');

/* Init */
define('getbwp_pro_url','https://getbookingswp.com/');

function getbookingwp_load_textdomain() {     	   
	   $locale = apply_filters( 'plugin_locale', get_locale(), 'get-bookings-wp' );	   
       $mofile = getbookingpro_path . "languages/get-bookings-wp-$locale.mo";
			
		// Global + Frontend Locale
		load_textdomain( 'get-bookings-wp', $mofile );
		load_plugin_textdomain( 'get-bookings-wp', false, dirname(plugin_basename(__FILE__)).'/languages/' );
}


add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'getbwp_settings_link' );
add_action('init', 'getbookingwp_load_textdomain');			
add_action('init', 'getbookingwp_output_buffer');

function getbookingwp_output_buffer() {
		ob_start();
}
function getbwp_settings_link( array $links ) {	
	global  $getbwpcomplement;

	if(!isset($getbwpcomplement)) {	
		$url = "https://getbookingswp.com/pricing";
		$settings_link = '<a href="' . $url . '" target="_blank" class="getbwp-plugins-gopro">' . __('Go Pro', 'get-bookings-wp') . '</a>';
		$links[] = $settings_link;	

	}
	return $links;
}
require_once (getbookingpro_path . 'classes/getbookingswp.class.php');
register_activation_hook( __FILE__, 'getbookingwp_activation');
 
function  getbookingwp_activation( $network_wide ) {
	$plugin_path = '';
	$plugin = "get-bookings-wp/index.php";	
	
	if ( is_multisite() && $network_wide ){ 
		activate_plugin($plugin_path,NULL,true);			
		
	} else {   	
			
		activate_plugin($plugin_path,NULL,false);	
		
	}
}

$getbookingwp = new GetBookingsWP();
$getbookingwp->plugin_init();

register_activation_hook(__FILE__, 'getbwp_my_plugin_activate');
add_action('admin_init', 'getbwp_my_plugin_redirect');

function getbwp_my_plugin_activate(){
    add_option('getbwp_plugin_do_activation_redirect', true);
}

function getbwp_my_plugin_redirect() {
    if (get_option('getbwp_plugin_do_activation_redirect', false)) {
        delete_option('getbwp_plugin_do_activation_redirect');
        wp_redirect(GETBWP_SETTINGS_URL);
    }
}
require_once getbookingpro_path . 'addons/maintenance/index.php';