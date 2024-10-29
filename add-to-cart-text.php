<?php

/*
 Plugin Name: Add to Cart Text
 Plugin URI: https://hardani.de/addtocart
 Description: This plugin helps you change the WooCommerce "add to cart" text on single and multi product pages. 
 Author: Keyvan Hardani
 Author URI: https://hardani.de
 Version: 1.0
 License: GPLv3 or later License
 URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

/**
 * WooCommerce is active!?
 **/

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

/**
 *  Admin Settings Area
 */

function adtct_dash_section( $sections ) {
    $sections['adtct_dash_section'] = __( 'Add to cart button Text', 'add-to-cart-text' );
    return $sections;
}
add_filter( 'woocommerce_get_sections_products', 'adtct_dash_section' );

function adtct_dash_settings( $settings, $current_section ) {
    
    /**
     * Check the current section is what we want
     **/
    if ( 'adtct_dash_section' === $current_section ) {

        $settings[] = array( 'title' => __( 'Change the WooCommerce "add to cart" button text on product pages', 'woocommerce' ), 'type' => 'title', 'id' => 'adtct_wc_change' );

        $settings[] = array(
                'title'    => __( 'Simple products', 'add-to-cart-text' ),
                'desc' => __( 'This will change the "add to cart" text shown on single product page', 'add-to-cart-text' ),
                'id'       => 'simple_button_text_single',
                'type'     => 'text',
                'placeholder' => 'Add to cart',
                'css'      => 'min-width:350px;',
            );

        $settings[] = array(
                'title'    => __( 'Grouped products', 'add-to-cart-text' ),
                'desc' => __( 'This will change the "add to cart" text shown on grouped product page', 'add-to-cart-text' ),
                'id'       => 'grouped_button_text_single',
                'type'     => 'text',
                'placeholder' => 'Add to cart',
                'css'      => 'min-width:350px;',
            );

        $settings[] = array( 'type' => 'sectionend', 'id' => 'adtct_wc_change' );

    }

    return $settings;
}
add_filter( 'woocommerce_get_settings_products','adtct_dash_settings', 10, 2 );

function adtct_product_add_to_cart_text($text, $product) {

    $custom_text = '';

    switch ( $product->get_type() ) {
        case 'simple':
            $custom_text = adtct_dash_get_settings( 'simple_button_text_single');
        break;
        case 'grouped':
            $custom_text = adtct_dash_get_settings( 'grouped_button_text_single');
        break;

    }

    return '' !== $custom_text ? $custom_text : $text;
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'adtct_product_add_to_cart_text', 10, 2 );
add_filter( 'woocommerce_booking_single_add_to_cart_text', 'adtct_product_add_to_cart_text', 10, 2 );

function adtct_dash_get_settings( $key ) {
    return get_option( $key, '' );
}

}