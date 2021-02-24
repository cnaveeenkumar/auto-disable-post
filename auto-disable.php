<?php
/**
* Plugin Name:     Auto Disable Post
* Plugin URI:      https://cnaveenkumar.wordpress.com/
* Description:     Disable posts by time line
* Version:         1.0.0
* Author:          Naveenkumar C
* Text Domain:     auto-disable
*/

define( 'AD_POST_TYPE', 'job' );
define( 'AD_RECURRENCE', 'daily' );
define( 'AD_STATUS', 'draft' );
define( 'AD_EXPIRE', '30' );

register_activation_hook(__FILE__, 'auto_disable_activation');

function auto_disable_activation() {
    if (! wp_next_scheduled ( 'disable_event' )) {
        wp_schedule_event(time(), AD_RECURRENCE, 'disable_event');
    }
}

add_action('disable_event', 'do_this_hourly');

function do_this_hourly() {

    global $wpdb;	
 
    $sql = "SELECT ID FROM {$wpdb->posts} WHERE post_type = '".AD_POST_TYPE."' AND DATEDIFF(NOW(),post_date)>".AD_EXPIRE."";
	
	$results = $wpdb->get_results($sql,ARRAY_A,3);
	
	foreach($results as $result){
		$new_post = array(
			'ID'           => $result['ID'],
			'post_status'   => AD_STATUS,
		);

		wp_update_post( $new_post );
	}

}