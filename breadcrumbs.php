<?php

/*
 * @wordpress-plugin
 * Plugin Name:       _ANDYP - Shortcode [breadcrumb]
 * Plugin URI:        http://londonparkour.com
 * Description:       <strong>Shortcode</strong> [breadcrumb]  to display a [category]-->[subcategory]-->[page] 
 * Version:           1.0.0
 * Author:            Andy Pearson
 * Author URI:        https://londonparkour.com
 * Domain Path:       /languages
 */



//  ┌─────────────────────────────────────────────────────────────────────────┐
//  │                           Register CONSTANTS                            │
//  └─────────────────────────────────────────────────────────────────────────┘
define( 'ANDYP_BREADCRUMB_PATH', __DIR__ );
define( 'ANDYP_BREADCRUMB_URL', plugins_url( '/', __FILE__ ) );
define( 'ANDYP_BREADCRUMB_FILE',  __FILE__ );

//  ┌─────────────────────────────────────────────────────────────────────────┐
//  │                    Register with ANDYP Plugins                          │
//  └─────────────────────────────────────────────────────────────────────────┘
require __DIR__.'/src/acf/andyp_plugin_register.php';

// ┌─────────────────────────────────────────────────────────────────────────┐
// │                         Use composer autoloader                         │
// └─────────────────────────────────────────────────────────────────────────┘
require __DIR__.'/vendor/autoload.php';


// ┌─────────────────────────────────────────────────────────────────────────┐
// │                        	   Initialise    		                     │
// └─────────────────────────────────────────────────────────────────────────┘
$bc = new andyp\breadcrumb\initialise;
$bc->register();