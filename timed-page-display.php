<?php
/*
Plugin Name: Timed Page Display
Plugin URI: https://bloomwp.com/plugins/timed-page-display/
Description: Redirect a page based on day(s) and time of the week.
Version: 1.0
Author: Bloom WP
Author URI: https://www.bloomwp.com/
*/

global $time_page_display_plugin_dir, $time_page_display_plugin_url, $options;
$time_page_display_plugin_dir = untrailingslashit( plugin_dir_path( __FILE__ ) );
$time_page_display_plugin_url = untrailingslashit( plugin_dir_url( __FILE__ ) );
$options = get_option( 'time_page_display_options', array() );

/**
 * Load Current User stuff
 */
if (!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php"); 
}


/**
 * Load CSS & JS for admin datePicker
*/
function time_page_display_load_plugin_css( $hook ) {
	global $time_page_display_plugin_url;
	if ( $hook == 'settings_page_timed-page-display' ) {
		wp_enqueue_style( 'timed-page-display-timepicker-css', plugins_url('/css/jquery.timepicker.min.css', __FILE__ ), false, time() );
		wp_enqueue_script( 'timed-page-display-timepicker-js', plugins_url( '/js/jquery.timepicker.min.js', __FILE__ ), false, time());
		wp_enqueue_script( 'timed-page-display-js', plugins_url( '/js/timed.page.display.js', __FILE__ ), false, time());
	}
}
add_action( 'admin_enqueue_scripts', 'time_page_display_load_plugin_css' );


/**
 * Admin Options
 */
if ( is_admin() ) {
	include_once $time_page_display_plugin_dir . '/timed-page-display-admin.php';
}

/**
 * Add setting page hook
*/
function time_page_display_add_menu_link() {
	add_submenu_page( 'options-general.php', 'Timed Page Display', 'Timed Page Display',
    'manage_options', 'timed-page-display', 'time_page_display_settings');   
}
add_filter( 'admin_menu', 'time_page_display_add_menu_link' );


function time_page_display_open_close_support_page() {
	
	$options = stripslashes_deep( get_option( 'time_page_display_options', array() ) );

	if (isset($options['time_page_display_page']) && $options['time_page_display_page'] != '' ) {

		if ( is_page( $options['time_page_display_page'] ) && ! current_user_can( 'administrator' ) ) { 

			//date_default_timezone_set('US/Eastern');
			date_default_timezone_set(get_option('timezone_string'));
	        // current OR user supplied UNIX timestamp
	        $timestamp = time();
	        
	        // default status
	        $status = 'closed';

	        // get current time object
	        $currentTime = (new DateTime())->setTimestamp($timestamp);

	        if (array_key_exists(date('D', $timestamp), $options['time_page_display_opening_hours'])) {
	            // loop through time ranges for current day
            	$startTime = $options['time_page_display_opening_hours'][date('D', $timestamp)]['start'];
            	$endTime = $options['time_page_display_opening_hours'][date('D', $timestamp)]['end'];

                // create time objects from start/end times
                $startTime = DateTime::createFromFormat('h:i A', $startTime);
                $endTime   = DateTime::createFromFormat('h:i A', $endTime);

                // check if current time is within a range
                if (($startTime < $currentTime) && ($currentTime < $endTime)) {
                    $status = 'open';
                }
	        }

	        //echo "status is=".$status;
	        if ( $status =='open' ) {
	            wp_redirect( get_the_permalink($options['time_page_display_redirect_page']) );
	            die;            
	        }
		}
	}
	//echo "<pre>";		print_r($options);	echo "</pre>";		die();
}
add_action( 'template_redirect', 'time_page_display_open_close_support_page' );
?>