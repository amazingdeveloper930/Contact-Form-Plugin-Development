<?php

function _l4l_new_contact_form_callback()
{
    ?>
    <div class = "wrap">
        <h1 class='wp-heading-inline'>Add New Contact Forms</h1>
        <?php 
            require_once(WPCFL4L_PLUGIN_VIEWS_DIR . '/contact_form_config_panel.php');
            
        ?>
    </div>
<?php 
}