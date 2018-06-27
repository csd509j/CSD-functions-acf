<?php
/*
Plugin Name: CSD Functions - ACF
Version: 1.0
Description: ACF Plugin Customizations for CSD Schools Theme
Author: Josh Armentano
Author URI: http://abidewebdesign.com
Plugin URI: http://abidewebdesign.com
*/

require WP_CONTENT_DIR . '/plugins/plugin-update-checker-master/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/csd509j/CSD-functions-acf',
	__FILE__,
	'CSD-functions-acf'
);

$myUpdateChecker->setBranch('master'); 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add option menus
 *
 * @since CSD Schools 1.0
 */
 
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page();
	
}
if( function_exists('acf_add_options_sub_page') ) {
    acf_add_options_sub_page( 'General' );
    acf_add_options_sub_page( 'Pages' );
    acf_add_options_sub_page( 'Calendar' );
    acf_add_options_sub_page( 'Footer' );
    acf_add_options_sub_page( '404 Page' );
    acf_add_options_sub_page( 'District Info' );
}

/**
 * Load sidebar select fields with callout blocks from options
 *
 * @since CSD Schools 1.0
 */
 
 function acf_load_sidebar_callout_blocks_field_choices( $field ) {
    
    // reset choices
    $field['choices'] = array();

    // if has rows
    if( have_rows('callout_blocks', 'option') ) {
        
        // while has rows
        while( have_rows('callout_blocks', 'option') ) {
            
            // instantiate row
            the_row();
            
            // vars
            $value = get_sub_field('callout_block_heading');
            $label = get_sub_field('callout_block_heading');

            
            // append to choices
            $field['choices'][ $value ] = $label;
            
        }
        
    }

    // return the field
    return $field;
    
} 
add_filter('acf/load_field/name=sidebar_callout_blocks', 'acf_load_sidebar_callout_blocks_field_choices');

/**
 * Load sidebar select fields with contact blocks from options
 *
 * @since CSD Schools 1.0
 */
 
function acf_load_sidebar_contact_blocks_field_choices( $field ) {
    
    // reset choices
    $field['choices'] = array();

    // if has rows
    if( have_rows('contact_blocks', 'option') ) {
        
        // while has rows
        while( have_rows('contact_blocks', 'option') ) {
            
            // instantiate row
            the_row();
            
            // vars
            $value = get_sub_field('contact_name');
            $label = get_sub_field('contact_name');

            
            // append to choices
            $field['choices'][ $value ] = $label;
            
        }
        
    }

    // return the field
    return $field;
    
} 
add_filter('acf/load_field/name=sidebar_contact_block', 'acf_load_sidebar_contact_blocks_field_choices');

/**
 * Set featured image from ACF field
 *
 * @since CSD Schools 1.0
 */
 
function acf_set_featured_image( $value, $post_id, $field  ){
	
	$id = $value;
	
	if( ! is_numeric( $id ) ){
		
		$data = json_decode( stripcslashes($id), true );
		$id = $data['cropped_image'];
	
	}
	
	update_post_meta( $post_id, '_thumbnail_id', $id );
	
	return $value;
}
// acf/update_value/name={$field_name} - filter for a specific field based on it's name
add_filter( 'acf/update_value/name=featured_image', 'acf_set_featured_image', 10, 3 );
