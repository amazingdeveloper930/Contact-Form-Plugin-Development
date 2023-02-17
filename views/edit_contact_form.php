<?php

function _l4l_edit_contact_form_callback()
{
    if(isset($_POST) 
        && isset($_POST['contact-form-action']))
        if($_POST['contact-form-action'] == 'store')
        
        {
            global $wpdb;
            $contact_form_id = $_GET['id'];
            $contact_form_id = (int) $contact_form_id;
            if($contact_form_id == 0)
            {
                $l4l_table_name = $wpdb -> prefix . 'contact_form';
                $wpdb -> insert(
                    $l4l_table_name,
                    array(
                        'title' => $_POST['post_title'],
                        'txt_on_submit' => $_POST['txt_on_submit'],
                        'txt_over_submit' => $_POST['txt_over_submit'],
                        'txt_below_submit' => $_POST['txt_below_submit'],
                        'txt_alert_success' => $_POST['txt_alert_success'],
                        'txt_alert_error' => $_POST['txt_alert_error'],
                        'email_for_receive' => $_POST['email_for_receive']
                    )
                );
                $contact_form_id = $wpdb -> insert_id;
            }
            else{
                $l4l_table_name = $wpdb -> prefix . 'contact_form';
                $wpdb -> update($l4l_table_name,
                    array(
                        'title' => $_POST['post_title'],
                        'txt_on_submit' => $_POST['txt_on_submit'],
                        'txt_over_submit' => $_POST['txt_over_submit'],
                        'txt_below_submit' => $_POST['txt_below_submit'],
                        'txt_alert_success' => $_POST['txt_alert_success'],
                        'txt_alert_error' => $_POST['txt_alert_error'],
                        'email_for_receive' => $_POST['email_for_receive']
                    ),
                    array('id' => $contact_form_id)
                );


                $l4l_table_name = $wpdb -> prefix . 'contact_form_widget';
                $wpdb -> delete($l4l_table_name, array('form_id' => $contact_form_id));
            }
            $count = 1;
            for($index = 0; $index < count($_POST['contact-form-widget-type']); $index ++)
            {
                if($_POST['contact-form-widget-type'][$index] == '')
                    continue;
                $l4l_table_name = $wpdb -> prefix . 'contact_form_widget';
                $wpdb -> insert(
                    $l4l_table_name,
                    array(
                        'form_id' => $contact_form_id,
                        'widget_id' => $_POST['contact-form-widget-id'][$index],
                        'widget_type' => $_POST['contact-form-widget-type'][$index],
                        'label' => $_POST['contact-form-widget-label'][$index],
                        'placeholder' => $_POST['contact-form-widget-placeholder'][$index],
                        'mandatory' => $_POST['contact-form-widget-mandatory'][$index],
                        'order_number' => $count ++
                    )
                );
            }
            if((int)$_GET['id'] == 0)
            {
                $url = "?page=_l4l_edit_contact_form_slug&id=" . $contact_form_id;
             ?>
                <script>
                window.location.href = "<?=$url?>";</script>
             <?php
            }
            
        }
        else if($_POST['contact-form-action'] == 'delete')
        {
            $contact_form_id = $_GET['id'];
            $contact_form_id = (int) $contact_form_id;
            global $wpdb;
            $l4l_table_name = $wpdb -> prefix . 'contact_form';
            $where = array('id' => $contact_form_id);
            $where_format = array('%d');
            $wpdb -> delete($l4l_table_name, $where, $where_format);

            $where = array('form_id' => $contact_form_id);
            $l4l_table_name = $wpdb -> prefix . 'contact_form_mails';
            $wpdb -> delete($l4l_table_name, $where, $where_format);

            $where = array('form_id' => $contact_form_id);
            $l4l_table_name = $wpdb -> prefix . 'contact_form_widget';
            $wpdb -> delete($l4l_table_name, $where, $where_format);
            $url = "?page=_l4l_contact_form_list_slug";

            ?>
                <script>
                window.location.href = "<?=$url?>";</script>
             <?php

        }

    ?>

    <div class = "wrap">
        <h1 class='wp-heading-inline'>Edit Contact Forms</h1>
        <?php 

            require_once(WPCFL4L_PLUGIN_VIEWS_DIR . '/contact_form_config_panel.php');
        ?>
    </div>
<?php 

}
