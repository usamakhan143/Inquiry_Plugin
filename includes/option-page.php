<?php

use Carbon_Fields\Field;
use Carbon_Fields\Container;

add_action('after_setup_theme', 'load_carbon_fields');
add_action('carbon_fields_register_fields', 'create_options_page');



function load_carbon_fields()
{
    \Carbon_Fields\Carbon_Fields::boot();
}

function create_options_page() {
    
    Container::make( 'theme_options', __( 'Contact Form' ) )
    ->set_icon( 'dashicons-carrot' )
    ->add_fields( array(
        
        Field::make( 'checkbox', 'contact_plugin_active', __( 'Active' ) ),

        Field::make( 'text', 'contact_plugin_email', __( 'Recipent Email' ) )
        ->set_attribute('placeholder', 'Enter Email Address')
        ->set_help_text('The submission notification will send to this email.'),
        
        Field::make( 'textarea', 'contact_plugin_message', __( 'Confirmation Message' ) )
        ->set_attribute('placeholder', 'If you want to use a dynamic tag for the name field in the message, please use {name}.')
        ->set_help_text('Type your confirmation message which will be displayed when the user submit the form.'),

    ) );

}