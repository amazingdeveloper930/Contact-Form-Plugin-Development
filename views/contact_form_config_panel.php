<?php 

    function generateContactFormWidget($contactFormWidgetArr = [])
    {
        foreach($contactFormWidgetArr as $widget)
        {
            ?>
            <tr class="contact-form-widget-row">
                <td>
                    <select class="contact-form-widget-type" name="contact-form-widget-type[]">
                        <option value="" default></option>
                        <option value="TEXT" <?=$widget['widget_type'] == 'TEXT'? 'selected':''?>>Text</option>
                        <option value="EMAIL" <?=$widget['widget_type'] == 'EMAIL'? 'selected':''?>>Email</option>
                        <option value="PHONE" <?=$widget['widget_type'] == 'PHONE'? 'selected':''?>>Phone</option>
                        <option value="TEXTAREA" <?=$widget['widget_type'] == 'TEXTAREA'? 'selected':''?>>Textarea</option>
                    </select>
                </td>
                <td><input type="text" class="contact-form-widget-label" maxlength=30 value="<?=$widget['label']?>" name="contact-form-widget-label[]"/></td>
                <td><input type="text" class="contact-form-widget-id" maxlength=30  name="contact-form-widget-id[]" value="<?=$widget['widget_id']?>"/></td>
                <td><input type="text" class="contact-form-widget-placeholder" maxlength=30  name="contact-form-widget-placeholder[]" value="<?=$widget['placeholder']?>"/></td>
                <td>
                <select class="contact-form-widget-mandatory" name="contact-form-widget-mandatory[]">
                    <option value = 1 >Required</option>
                    <option value = 0 <?=$widget['mandatory'] == 0? 'selected':''?>>Not Required</option>
                </select>
              
                <td><a href="JavaScript:void(0);" onclick="deleteL4lContactWidget(this)">Delete</a></td>               
                
            </tr>
            <?php
        }
    }

    $page = $_REQUEST['page'];
    $flag = 0; // 1: new , 2: edit
    if($page == '_l4l_new_contact_form_slug')
    {
        $flag = 1;
    }
    else if($page == '_l4l_edit_contact_form_slug')
    {
        $flag = 2;
    }


    $contact_form_data = [
        'id' => '0',
        'title' => '',
        'txt_on_submit' => '',
        'txt_over_submit' => '',
        'txt_below_submit' => '',
        'txt_alert_success' => '',
        'txt_alert_error' => '',
        'email_for_receive' => '',
        'contact_form_widget_list' => []
    ];
    $contact_form_id = 0;
    if($flag == 1) // new contact form
    {
        
    }
    else if($flag == 2) // edit contact form
    {
        global $wpdb;
        $table_name_l4l = $wpdb -> prefix . "contact_form";
        $contact_form_id = $_GET['id'];
        $row_data = $wpdb -> get_row($wpdb -> prepare('SELECT * FROM ' . $table_name_l4l . ' WHERE id = %d', $contact_form_id));



        if($row_data)
        {


           

            $table_name_l4l = $wpdb -> prefix . "contact_form_widget";
            $rows_data = $wpdb -> get_results($wpdb -> prepare('SELECT * FROM ' . $table_name_l4l . ' WHERE form_id = %d ORDER BY order_number', $contact_form_id));
            $contact_form_widget_list = [];
            if($rows_data)
            {
                foreach($rows_data as $contact_form_widget_row)
                {
                    $item = [];
                    $item ['widget_id']= $contact_form_widget_row -> widget_id;
                    $item ['widget_type']= $contact_form_widget_row -> widget_type;
                    $item ['label']= $contact_form_widget_row -> label;
                    $item ['placeholder']= $contact_form_widget_row -> placeholder;
                    $item ['mandatory']= $contact_form_widget_row -> mandatory;
                    $contact_form_widget_list []= $item;
                }
            }
            
            $contact_form_data = [
                'id' => $row_data -> id,
                'title' => $row_data -> title,
                'txt_on_submit' => $row_data -> txt_on_submit,
                'txt_over_submit' => $row_data -> txt_over_submit,
                'txt_below_submit' => $row_data -> txt_below_submit,
                'txt_alert_success' => $row_data -> txt_alert_success,
                'txt_alert_error' => $row_data -> txt_alert_error,
                'email_for_receive' => $row_data -> email_for_receive,
                'contact_form_widget_list' => $contact_form_widget_list
            ];
        }
        else{
            
        }
    }

    $settings = array(
        'textarea_rows' => 3,
        'media_buttons' => false
    );

    $form_submit_link = sprintf('?page=%s&id=%d','_l4l_edit_contact_form_slug', $contact_form_id);
    ?>
    <input type="hidden" id="active-tab" name="active-tab" value="1">
    <form method="post" action="<?=$form_submit_link?>" id="l4l-admin-form-element">
    <input hidden name="contact-form-action" id="contact-form-action" value="store" hidden/>

   
    <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
    <div id="post-body-content">
        <div id="titlediv">
            <div id="titlewrap">
                <label class="screen-reader-text" id="title-prompt-text" for="title"><?php echo esc_html( __( 'Enter title here') ); ?></label>
                <input type="text" name="post_title" size="30" id="title" spellcheck="true" autocomplete="off" value="<?=$contact_form_data['title']?>" placeholder="Enter title here" requried/>
            </div>
        </div>

        <?php 
      if($flag == 2)
      {
        ?>

        <div class="inside">
            <p class="description">
            <label for="l4l-shortcode">Copy this shortcode and paste it into your post, page, or text widget content:</label>
            <span class="shortcode wp-ui-highlight"><input type="text" id="l4l-shortcode" onfocus="this.select();" readonly="readonly" class="large-text code" value="[l4l-contact id=<?=$contact_form_id?>]"></span>
            </p>
        </div>

        <?php
      }

    ?>


        <div class="postbox-container">
            <div id="l4l-contact-form-editor">

            
            <ul id="l4l-contact-form-editor-tabs" role="tablist" class="ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header">
                <li id="form-panel-tab" role="tab" tabindex="-1" class="ui-tabs-tab ui-corner-top ui-state-default ui-tab" aria-controls="form-config-panel" aria-labelledby="ui-id-1" aria-selected="false" aria-expanded="false"><a href="#form-config-panel" tabindex="-1" class="ui-tabs-anchor" id="ui-id-1">Form</a></li>
                <li id="text-panel-tab" role="tab" tabindex="-1" class="ui-tabs-tab ui-corner-top ui-state-default ui-tab" aria-controls="text-config-panel" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false"><a href="#text-config-panel" tabindex="-1" class="ui-tabs-anchor" id="ui-id-2">Text</a></li>
                <?php 

                    if($flag == 2)
                    {
                        ?>
                        <li id="mail-panel-tab" role="tab" tabindex="-1" class="ui-tabs-tab ui-corner-top ui-state-default ui-tab" aria-controls="mail-history-panel" aria-labelledby="ui-id-3" aria-selected="false" aria-expanded="false"><a href="#mail-history-panel" tabindex="-1" class="ui-tabs-anchor" id="ui-id-3">Received Mail</a></li>
                        <?php
                    }
                ?>
            </ul>

            <!-- form editor panel -->
            <div class="l4l-contact-form-editor-panel ui-tabs-panel ui-corner-bottom ui-widget-content" id="form-config-panel" aria-labelledby="ui-id-1" role="tabpanel" aria-hidden="false" style="">
                <table class="contact-form-widget-table">
                    <thead>
                        <th>Type of Field</th>
                        <th>Label</th>
                        <th>Widget Id</th>
                        <th>placeholder</th>
                        <th>Mandatory</th>
                        <th>Action</th>
                    </thead>
                    <?php 
                        generateContactFormWidget($contact_form_data['contact_form_widget_list']);
                    ?>
                </table>
                <div class="add-contact-widget-button-wizard">
                    <button class="button-primary" onclick="addNewL4lContactWidget()" type="button">Add New Widget</button>
                </div>
            </div>

            <!-- text editor panel -->
            <div class="l4l-contact-form-editor-panel ui-tabs-panel ui-corner-bottom ui-widget-content" id="text-config-panel" aria-labelledby="ui-id-2" role="tabpanel" aria-hidden="false" style="">
                <div class="row">
                    <label>Text on the submit button</label>
                    <input type="text" maxlength="50" id="txt_on_submit" value="<?=$contact_form_data['txt_on_submit']?>" name="txt_on_submit"/>
                </div>
                <div class="row">
                    <label>Text above submit button</label>
                    <?php 
                        $editor_id = "txt_over_submit";
                        wp_editor($contact_form_data[$editor_id], $editor_id, $settings);
                    ?>
                </div>
                <div class="row">
                    <label>Text below submit button</label>
                    <?php 
                        $editor_id = "txt_below_submit";
                        wp_editor($contact_form_data[$editor_id], $editor_id, $settings);
                    ?>
                </div>
                <div class="row">
                    <label>Text that appears after a succesful send</label>
                    <?php 
                        $editor_id = "txt_alert_success";
                        wp_editor($contact_form_data[$editor_id], $editor_id, $settings);
                    ?>
                </div>
                <div class="row">
                    <label>Text that appears after a failed send</label>
                    <?php 
                        $editor_id = "txt_alert_error";
                        wp_editor($contact_form_data[$editor_id], $editor_id, $settings);
                    ?>
                </div>
                <div class="row">
                    <label>Email addresses that will receive the message</label>
                    <input type="email" maxlength="50" id="email_for_receive" value="<?=$contact_form_data['email_for_receive']?>" name="email_for_receive" required/>
                </div>
            </div>

            <?php 

            if($flag == 2)
            {
                ?>
            <!-- mail history panel -->
            <div class="l4l-contact-form-editor-panel ui-tabs-panel ui-corner-bottom ui-widget-content" id="mail-history-panel" aria-labelledby="ui-id-3" role="tabpanel" aria-hidden="false" style="">

                <?php 

                    require_once(WPCFL4L_PLUGIN_VIEWS_DIR . '/contact_form_mail_list_table.php');  
                    $mailListTable = new L4L_Contact_Form_Mail_List_Table();
                    $mailListTable -> setContactFormID($contact_form_id);
                    $mailListTable -> prepare_items();
                    $mailListTable -> display();

                ?>

            </div>
            <?php
                }
            ?>
            </div>
        </div>
    </div>
    </div>
    <div class="postbox-container" id="postbox-save-panel">
    <div id="submitdiv" class="postbox">
        <h3>Status</h3>
        <div class="inside">
        <div class="submitbox" id="submitpost">

        <div id="minor-publishing-actions">

        <div class="hidden">
            <input type="submit" class="button-primary" name="l4l-contact-form-save" value="Save">
        </div>

        </div><!-- #minor-publishing-actions -->

        <div id="misc-publishing-actions">
        </div><!-- #misc-publishing-actions -->

        <div id="major-publishing-actions">

        <?php 

if($flag == 2)
{
    ?>
        <div id="delete-action">
            <input type="button"  class="delete submitdelete" value="Delete" onclick="if (confirm('You are about to delete this contact form.\n  \'Cancel\' to stop, \'OK\' to delete.')) { deleteContactFormSubmit(); return true;} return false;">
        </div>
        <?php 

}
        ?>
        <div id="publishing-action">
            <span class="spinner"></span>
            <input type="button" class="button-primary" name="l4lcontactform-save" value="Save" onclick="saveL4LContactFormData()"></div>
        <div class="clear"></div>
        </div><!-- #major-publishing-actions -->
</div><!-- #submitpost -->
</div>
</div>
    </div>
    </div>
    </form>
<?php 

?>

