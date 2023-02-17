<?php
function _l4l_create_contact_form_left_menu() { 
 
    add_menu_page( 
        'L4L Contactformulieren', 
        'L4L Contactformulieren', 
        'administrator', 
        '_l4l_contact_form_list_slug', 
        '_l4l_main_contact_form_list_callback', 
        'dashicons-admin-site-alt' 
    );

    add_submenu_page(
        '_l4l_contact_form_list_slug',
        'Add New Contact Form',
        'Add New',
        'administrator',
        '_l4l_new_contact_form_slug',
        '_l4l_new_contact_form_callback'
    );

    add_dashboard_page(
        'Edit Contact Form',
        'Edit Contact Form',
        'administrator',
        '_l4l_edit_contact_form_slug',
        '_l4l_edit_contact_form_callback'
    );
}
   
add_action('admin_menu', '_l4l_create_contact_form_left_menu');
