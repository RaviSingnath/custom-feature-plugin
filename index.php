<?php
/*
 * Plugin Name: Auto GraphQL
 * Description: A plugin that checks all available CPTs and makes them and their (ACF)fields available for mutations in GraphQL.
 * Version: 1.5
 * Author: MahsaM
 * text Domain: auto_graphql
 */

if ( !function_exists( 'add_action' ) ){
    echo "Hi there! I'm just a plugin, not much I can do when called directly.";
    exit;
}
if (!defined('ABSPATH')) {
    exit;
}



// Includes
include( 'includes/getCPTs.php' );
include( 'includes/activate.php' );
include( 'includes/registerCPTs.php' );
include('includes/register_acf_fields_in_graphql.php');
include('includes/validation_acf_inputs.php');
include('includes/add_acf_fields_to_create_mutation.php');
include('includes/add_acf_fields_to_update_mutation.php');


// Hooks
register_activation_hook( __FILE__, 'm_activate_plugin' );




