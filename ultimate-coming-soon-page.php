<?php
/*
Plugin Name: Ultimate Coming Soon Page
Plugin URI: http://seedprod.com/wordpress-coming-soon-plugin/
Description: Creates a Teaser Page for your Site and Collect Email Addresses from your Visitors.
Version: 0.2
Author: SeedProd
Author URI: http://seedprod.com
License: GPLv2
Copyright 2011  John Turner (email : john@seedprod.com, twitter : @johnturner)
*/

/**
 * Init
 *
 * @package WordPress
 * @subpackage Ultimate_Coming_Soon_Page
 * @since 0.1
 */

/**
 * Require config to get our initial values
 */
require_once('framework/framework.php');
require_once('inc/config.php');

/**
 * Upon activation of the plugin, see if we are running the required version and deploy theme in defined.
 *
 * @since 0.1
 */
function seedprod_activation() {
    if ( version_compare( get_bloginfo( 'version' ), '3.0', '<' ) ) {
        deactivate_plugins( __FILE__  );
        wp_die( __('WordPress 3.0 and higher. The plugin has now disabled itself. On a side note why are you running an old version :( Upgrade!') );
    }else{
        if($seedprod_comingsoon->deploy_theme)
            switch_theme($seedprod_comingsoon->deploy_theme_name['template'],$seedprod_comingsoon->deploy_theme_name['stylesheet']);
    }
}


/**
 * If deploy theme register our custom theme path
 */
if($seedprod_comingsoon->deploy_theme)
    register_theme_directory(plugin_dir_path(__FILE__).'theme/');
      
?>