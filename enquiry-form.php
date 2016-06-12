<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
    Plugin Name: Enquiry Form for Woocommerce
    Description: Woocommerce product enquiry form that uses third party forms like Contact Form 7. Enquiry Form uses Contact Form 7 as default form.
    Version: 1.0
    Author: Paolo Gallardo
    Author URI: www.paologallardo.com
**/

/**
    Check if WooCommerce is active
 **/
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    return;
}

/**
    This will add the form to woocommerce tabs
**/
add_filter('woocommerce_product_tabs','woocommerce_product_tabs_contact_form7',10,1);
function woocommerce_product_tabs_contact_form7($tabs){

    $tabs['contact_form7'] = array(
        'title'    => __( 'Enquiry', 'woocommerce' ),
        'priority' => 20,
        'callback' => 'woocommerce_product_contact_form7_tab'
    );

    return $tabs;
}

/**
    This will echo the form to use
    By default, it uses Contact Form 7.
**/
function woocommerce_product_contact_form7_tab(){

    $shortCode = get_option( 'wc_enquiry_form_shortcode' );
    
    echo do_shortcode( $shortCode );

}

/**
    Add settings tabs to woocommerce
    @HOOK woocommerce_get_sections_products
    @PARAM $sections | ARRAY | woocommerce settings sections
    @RETURN $sections | the sections with the new section
**/
add_action( 'woocommerce_get_sections_products', 'add_enquiry_options' );
function add_enquiry_options( $sections ){
    
    $sections[ 'wc_enquiry_form' ] = __( 'Enquiry Form','wc_enquiry_form' );
    return $sections;
    
}

/**
    Add the settings to the new tab
    @HOOK woocommerce_get_settings_products
    @PARAM $settings | ARRAY | settings of woocommerce
    @PARAM $current_section | STRING | the current section of the tab the the use is viewing
    @RETURN $settings
**/
add_action( 'woocommerce_get_settings_products','add_enquiry_settings',10,2 );
function add_enquiry_settings( $settings,$current_section ){
    
    // check the current section
    if( $current_section == 'wc_enquiry_form' ){
        
        $enquiry_settings = array();
        
        $enquiry_settings[] = array( 
            'name' => __( 'Enquiry Form Settings','wc_enquiry_form' ),
            'type' => 'title',
            'desc' => __( 'Setting the third party form to use as the enquiry form.','wc_enquiry_form' ),
            'id' => 'wcenquiryform'
        );
        
        $enquiry_settings[] = array(
            'desc' => __( 'Shortcode for the third party form.','wc_enquiry_form' ),
            'desc_tip' => __( 'The shortcode provided for use of your form.' ),
            'id' => 'wc_enquiry_form_shortcode',
            'type' => 'text',
            'css' => 'min-width: 300px;',
            'name' => __( 'Form Shortcode','wc_enquiry_form' ),
            'default' => '[contact-form-7 title="Contact form 1"]'
        );
        
        $enquiry_settings[] = array( 'type' => 'sectionend', 'id' => 'wc_enquiry_form' );
        
        return $enquiry_settings;
    } else {
        
        return $settings;
        
    }
}
