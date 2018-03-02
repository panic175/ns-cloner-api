<?php
/*
Plugin Name: NS Cloner API
Plugin URI: https://github.com/panic175/ns-cloner-api
Description: Adds an endpoint to the site to copy a site in an multisite enviroment
Author: PaNic175
Version: 1.0.0
Network: true
Text Domain: ns-cloner-api
Author URI: https://github.com/panic175
License: MIT
*/

namespace NS_Cloner_API;

add_action( 'rest_api_init', function () {
    register_rest_route( 'ns-cloner/v1', '/copy', array(
        'methods' => 'POST',
        'callback' => 'NS_Cloner_API\copy_site',
        'permission_callback' => function () {
            return current_user_can( 'export' );
        }
    ) );
} );

/**
 * Copy a site
 *
 * @param WP_REST_Request $request
 * @return Array|WP_Error
 */
function copy_site( \WP_REST_Request $request ) {

    $source_id = (int) $request->get_param('source_id');
    $target_name = (string) $request->get_param('target_name');
    $target_title = (string) $request->get_param('target_title');

    if ( !empty($source_id) && !empty($target_name) && !empty($target_title) && get_blog_details($source_id) ) {

        $_POST = array( 
            'action'         => 'process',
            'clone_mode'     => 'core',
            'source_id'      => $source_id,
            'target_name'    => $target_name,
            'target_title' 	 => $target_title,
            'disable_addons' => false,
            'clone_nonce'    => wp_create_nonce('ns_cloner')
        );

        if (!class_exists('\ns_cloner')) return new \WP_Error('ns_cloner_missing', 'Please install and activate the NS Cloner plugin.');

        $ns_site_cloner = new \ns_cloner();

        $ns_site_cloner->set_up_request();

        $ns_site_cloner->process();

        $site_id = $ns_site_cloner->target_id;
        if ($site_id) {
            $site_info = get_blog_details( $site_id );
            if ( $site_info ) {
                return $site_info;
            }
        }
    }
    return new WP_Error('copy_failed', 'Could not copy site');
}