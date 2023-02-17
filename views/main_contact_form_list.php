<?php

function _l4l_main_contact_form_list_callback()
{
    if ( ! class_exists( 'L4L_Contact_Form_Table' ) ) {
        require_once(WPCFL4L_PLUGIN_VIEWS_DIR . '/contact_form_list_table.php');
    }

    $addNewLink = sprintf("?page=%s", "_l4l_new_contact_form_slug");
    ?>


    <div class = "wrap">
        <h1 class='wp-heading-inline'>Contact Forms</h1>
        <a class="page-title-action" href="<?=$addNewLink?>">Add New</a>
<?php 
    $contactFormTable = new L4L_Contact_Form_Table();
    $contactFormTable -> prepare_items();
    $contactFormTable -> display();
?>
    </div>
    <div class="clear"></div>
<?php
}