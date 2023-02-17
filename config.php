<?php

// Read styles

function l4l_plugin_styles()
{
    wp_enqueue_style('l4l-contact-form-admin', l4l_plugin_url('css/admin_styles.css'));
}

add_action('admin_enqueue_scripts', 'l4l_plugin_styles');

function l4l_plugin_scripts()
{
    wp_enqueue_script( 'l4l-contact-form-admin', l4l_plugin_url( 'js/admin_script.js' ), array( 'jquery', 'jquery-ui-tabs' ));
}

add_action('admin_enqueue_scripts', 'l4l_plugin_scripts');

function l4l_frontend_scripts()
{
    wp_enqueue_script( 'l4l-contact-form', l4l_plugin_url( 'js/script.js' ), array( 'jquery'), "1.0", true);

    wp_localize_script('l4l-contact-form', 'l4lAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'l4l_frontend_scripts');


function l4l_frontend_styles()
{
    wp_enqueue_style('l4l-contact-form-admin', l4l_plugin_url('css/style.css'));
}
add_action('wp_enqueue_scripts', 'l4l_frontend_styles');


// Read scripts



// Read DB

global $wpdb;

// --- contact form table
$table_name_l4l = $wpdb -> prefix . "contact_form";

$table_exists = $wpdb -> get_var("SHOW TABLES LIKE '$table_name_l4l'");

if($table_exists != $table_name_l4l){
    $charset_collate = $wpdb -> get_charset_collate();
    $sql = "CREATE TABLE $table_name_l4l (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(255) DEFAULT '',
        txt_on_submit TEXT DEFAULT '',
        txt_over_submit TEXT NULL,
        txt_below_submit TEXT NULL,
        txt_alert_success TEXT NULL,
        txt_alert_error TEXT NULL,
        email_for_receive varchar(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql); 
}


// --- contact form widget table

$table_name_l4l = $wpdb -> prefix . "contact_form_widget";

$table_exists = $wpdb -> get_var("SHOW TABLES LIKE '$table_name_l4l'");

if($table_exists != $table_name_l4l){
    $charset_collate = $wpdb -> get_charset_collate();
    $sql = "CREATE TABLE $table_name_l4l (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        form_id mediumint(9) NOT NULL, 
        widget_id VARCHAR(255) NULL,
        widget_type ENUM ('TEXT', 'EMAIL', 'PHONE', 'TEXTAREA'),
        label varchar(255) DEFAULT '',
        placeholder varchar(255) DEFAULT '',
        mandatory tinyint(2) DEFAULT 0,
        order_number mediumint(5) DEFAULT 1,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql); 
}

// contact_form_mails table
$table_name_l4l = $wpdb -> prefix . "contact_form_mails";
$table_exists = $wpdb -> get_var("SHOW TABLES LIKE '$table_name_l4l'");

if($table_exists != $table_name_l4l){
    $charset_collate = $wpdb -> get_charset_collate();
    $sql = "CREATE TABLE $table_name_l4l (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        form_id mediumint(9) NOT NULL, 
        data text DEFAULT '',
        sent_at datetime NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql); 
}


// plugin editor

function l4l_plugin_enqueue_editor() {
    wp_enqueue_editor();
}

add_action('admin_enqueue_scripts', 'l4l_plugin_enqueue_editor');



// shortcode maker

function l4l_contact_form_shortcode($atts, $content = null){
    $html = "<form class='l4l-contact-form'>";
    $html .= "<div class='l4l-contact-form-container'>";
    $contact_form_id = $atts['id'];
    global $wpdb;

    $table_name_l4l = $wpdb -> prefix . "contact_form";
    $row_data = $wpdb -> get_row($wpdb -> prepare('SELECT * FROM ' . $table_name_l4l . ' WHERE id = %d', $contact_form_id));

    if($row_data)
    {
        $table_name_l4l = $wpdb -> prefix . "contact_form_widget";

        $rows_data = $wpdb -> get_results($wpdb -> prepare('SELECT * FROM ' . $table_name_l4l . ' WHERE form_id = %d ORDER BY order_number', $contact_form_id));

        foreach($rows_data as $widget)
        {
            $html .= "<div class='l4l-contact-form-row'>";
            $html .= "<label class='l4l-contact-form-label' for='" . $widget -> widget_id . "'>" . $widget -> label . ($widget -> mandatory ? '<span class="l4l-contact-form-mandatory">*</span>' : '') . "</label>";
            $type = "text";
            switch($widget -> widget_type)
            {
                case 'EMAIL' : 
                    $type = 'email';
                    break;
                case 'TEXT' : 
                    $type = 'text';
                    break;    
                case 'PHONE' : 
                    $type = 'tel';
                    break;
                case 'TEXTAREA' :
                    $type = 'textarea';
                    break;
            }
            if($type == 'text' || $type == 'email')
            {
                $html .= "<input type='" . $type . "' id='" . $widget -> widget_id . "' placeholder='" . $widget -> placeholder . "' " . ($widget -> mandatory ? 'required' : '') . " pattern='[0-9]{3}-[0-9]{2}-[0-9]{3}'  class='l4l-contact-form-control'/>";
            }
            else if($type == 'tel')
            {
                $html .= "<input type='" . $type . "' id='" . $widget -> widget_id . "' placeholder='" . $widget -> placeholder . "' " . ($widget -> mandatory ? 'required' : '') . " pattern='[0-9]{3}-[0-9]{2}-[0-9]{3}'   class='l4l-contact-form-control'/>";
            }
            else if($type == 'textarea')
            {
                $html .= "<textarea id='" . $widget -> widget_id . "' placeholder='" . $widget -> placeholder . "'  " . ($widget -> mandatory ? 'required' : '') . "   class='l4l-contact-form-control'></textarea>";
            }
            $html .= $widget -> mandatory ? "<span class='l4l-contact-form-mandatory-warning'>Je bericht is succesvol verzonden</span>" : '';
            $html .= "</div></form>";
        }

        
        $html .= "<div class='l4l-contact-form-button-row'>";
        $html .= "<div class='l4l-contact-form-button-prev'>" . $row_data -> txt_over_submit . "</div>";
        $html .= "<button class='l4l-contact-form-button' type='button' onclick='_l4l_submit_callback(this)' data-set-info=" . $row_data -> id . ">" . $row_data -> txt_on_submit . "</button>";
        $html .= "<span class='l4l-contact-form-submit-result'></span>";
        $html .= "<div class='l4l-contact-form-button-prev'>" . $row_data -> txt_below_submit . "</div>";
        $html .= "</div>";

    }
    $html .= "</div>";

    return $html;
}

add_shortcode('l4l-contact', 'l4l_contact_form_shortcode');




function l4l_ajax_handler() {
    
    $datas = $_POST['data'];
    $contact_form_id = $_POST['contact_form_id'];
    global $wpdb;
    $table_name_l4l = $wpdb -> prefix . "contact_form";
    $row_data = $wpdb -> get_row($wpdb -> prepare('SELECT * FROM ' . $table_name_l4l . ' WHERE id = %d', $contact_form_id));

    if($row_data)
    {
        // $wp
        $html = "";
        foreach($datas as $data)
        {
            $html .= $data['title'] . " : " . $data['data'] . "\n";      
        }
        $to = $row_data -> email_for_receive;
        $subject = "Ingevuld formulier 'FORMNAME'";
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $value = wp_mail($to, $subject, $html, $headers);
        if($value)
        // if(true)
        {
            $result = ["status" => "success", "message" => $row_data -> txt_alert_success ? $row_data -> txt_alert_success : "Je bericht is succesvol verzonden"];
            $l4l_table_name = $wpdb -> prefix . 'contact_form_mails';
            $date = date_create();
            $dt = $date->format("Y-m-d H:i:s");;
            $wpdb -> insert(
                $l4l_table_name,
                array(
                    'form_id' => $contact_form_id,
                    'data' => nl2br($html),
                    'sent_at' => $dt
                )
            );

        }
        else{
            $result = ["status" => "error", "message" => $row_data -> txt_alert_error ? $row_data -> txt_alert_error : "Verzenden van dit bericht is mislukt"];
        }
    }
    else{
        $result = ["status" => "error", "message" => $row_data -> txt_alert_error ? $row_data -> txt_alert_error : "Verzenden van dit bericht is mislukt"];
    }

    
    
    wp_send_json($result);
}

add_action('wp_ajax_l4l_action', 'l4l_ajax_handler');
add_action('wp_ajax_nopriv_l4l_action', 'l4l_ajax_handler');