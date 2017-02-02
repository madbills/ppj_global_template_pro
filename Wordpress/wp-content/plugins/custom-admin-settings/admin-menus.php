<?php
/*
Plugin Name: Custom Admin settings
Plugin URI: http://redballoondigital.com/
Description: Custom admin menu settings
Version: 1.0
Author: Red Balloon Digital
Author URI: http://redballoondigital.com/
License: GPLv2
*/

add_action( 'admin_init', 'custom_settings_admin_init' );

// This page initialises the custom settings menus using the settings API
function custom_settings_admin_init() {

    // add_options_page( 'Alter Translation Text', 'Translation Text', 'manage_options', 'translation-text', 'translation_function_rb');
    // add_settings_field( $id, $title, $callback, $page, $section = 'default', $args = array() );
    // add_settings_field( $id, $title, $callback, $page, $section = 'default', $args = array() );
    // add_settings_field( $id, $title, $callback, $page, $section = 'default', $args = array() );

    add_option('country-contact-number', '');
    add_option('country-display-name', '');
    add_option('social-text', '');
    add_option('legal-text', '');
    add_option('more-info-text', '');
    add_option('email-signup-page-id', '');


    // Header
    add_settings_section( 'header-translation', 'Header Content', 'header_translation_fields', 'general' );

    register_setting( 'header-settings-group', 'country-contact-number' );
    register_setting( 'header-settings-group', 'country-display-name' );

    add_settings_field( 'country-contact-number', 'Contact number', 'display_contact_number_callback', 'general', 'header-translation' );
    add_settings_field( 'country-display-name', 'Store Name', 'display_store_name_callback', 'general', 'header-translation' );

    // Footer
    add_settings_section( 'footer-translation', 'Footer Content', 'footer_translation_fields', 'general' );

    register_setting( 'footer-settings-group', 'social-text' );
    register_setting( 'footer-settings-group', 'legal-text' );
    register_setting( 'footer-settings-group', 'email-signup-page-id' );

    add_settings_field( 'social-text', 'Social Text', 'social_text_callback', 'general', 'footer-translation' );
    add_settings_field( 'legal-text', 'Legal text', 'legal_text_callback', 'general', 'footer-translation' );
    add_settings_field( 'email-page-id', 'Email signup page', 'email_page_callback', 'general', 'footer-translation' );

    // More info
    add_settings_section( 'misc-translation', 'Misc Translations', 'misc_translation_fields', 'general' );

    register_setting( 'misc-translations', 'more-info-text' );

    add_settings_field( 'more-info-text', 'More info button text', 'display_more_info_callback', 'general', 'misc-translation' );

}

function header_translation_fields(){
    // Echo content here to have it output before the header settings.
}
function footer_translation_fields(){
    // Echo content here to have it output before the footer settings.
}
function misc_translation_fields(){
    // Echo content here to have it output before the misc settings.
}

function display_contact_number_callback(){
    $setting = stripslashes(get_option( 'country-contact-number' ));
    echo '<input type="text" name="country-contact-number" value="' . $setting . '" class="regular-text" />';
}
function display_store_name_callback(){
    $setting = stripslashes(get_option( 'country-display-name' ));
    echo '<input type="text" name="country-display-name" value="' . $setting . '" class="regular-text" />';
}

function social_text_callback(){
    $setting = stripslashes(esc_attr( get_option( 'social-text' ) ));
    echo '<input type="text" name="social-text" value="' . $setting . '" class="regular-text" />';
}
function legal_text_callback(){
    $setting = stripslashes(esc_attr( get_option( 'legal-text' ) ));
    echo '<input type="text" name="legal-text" value="' . $setting . '" class="regular-text" />';
}
function email_page_callback(){
    $setting = stripslashes(esc_attr( get_option( 'email-signup-page-id' ) ));
    echo '<input type="text" name="email-signup-page-id" value="' . $setting . '" class="regular-text" />';
}
function display_more_info_callback(){
    $setting = stripslashes(esc_attr( get_option( 'more-info-text' ) ));
    echo '<input type="text" name="more-info-text" value="' . $setting . '" class="regular-text" />';
}


add_action('admin_init', 'update_custom_settings');
function update_custom_settings(){
    if (current_user_can('edit_posts')){
        if (isset($_POST['country-contact-number'])){
            update_option( 'country-contact-number', $_POST['country-contact-number'] );
        };
        if (isset($_POST['country-display-name'])){
            update_option( 'country-display-name', $_POST['country-display-name'] );
        };
        if (isset($_POST['social-text'])){
            update_option( 'social-text', $_POST['social-text'] );
        };
        if (isset($_POST['legal-text'])){
            update_option( 'legal-text', $_POST['legal-text'] );
        };
        if (isset($_POST['more-info-text'])){
            update_option( 'more-info-text', $_POST['more-info-text'] );
        };
        if (isset($_POST['email-signup-page-id'])){
            update_option( 'email-signup-page-id', $_POST['email-signup-page-id'] );
        };
    }
}