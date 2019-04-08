<?php
/*
 * Plugin Name: Scatter Payment Gateway
 * Plugin URI: none
 * Description: Scatter Payment Gateway
 * Version: 1.0.0
 * Author: Scatter team
 * Author URI: none
 * License: none
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// INITIATE WC GATEWAY CLASS
add_action( 'plugins_loaded', 'scatterPG_init_gateway_class' );
add_filter( 'woocommerce_payment_gateways', 'scatterPG_add_gateway_class' );

function scatterPG_init_gateway_class(){
    require_once "lib/Scatter_Payment_Gateway.php";
}

function scatterPG_add_gateway_class( $methods ) {
    $methods[] = 'Scatter_Payment_Gateway';
    return $methods;
}
