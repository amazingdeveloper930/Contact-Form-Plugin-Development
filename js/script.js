function _l4l_submit_callback(ele)
{
    var data_arr = [];
    var contact_form_rows = jQuery(ele).parent().parent().find(".l4l-contact-form-row");

    var submit_result = jQuery(ele).parent().parent().find(".l4l-contact-form-submit-result");

    jQuery(ele).parent().parent().find(".l4l-contact-form-mandatory-warning").hide();
    
    submit_result.removeClass('txt-error');
    submit_result.removeClass('txt-success');
    var error = false;
    contact_form_rows.each((index, item) => {
        var title = jQuery(item).find(".l4l-contact-form-label").attr('for');
        var data = jQuery(item).find(".l4l-contact-form-control").val();
        data_arr.push({
            title : title,
            data : data
        });

        if(data == "" &&
        jQuery(item).find(".l4l-contact-form-control").prop('required'))
        {
            error = true;
            jQuery(item).find(".l4l-contact-form-mandatory-warning").show();
        }

    });
    if(error)
        return;
        debugger;
        jQuery(ele).attr('disabled', true);
    jQuery.ajax({
        type : "POST",

        url : l4lAjax.ajaxurl,

        data : {
            action : 'l4l_action',
            data : data_arr,
            contact_form_id : jQuery(ele).attr("data-set-info")
        },

        dataType: "json",

        success: function(result){
            if(result['status'] == 'success')
            {
                submit_result.addClass('txt-success');
            }
            else{
                submit_result.addClass('txt-error');
                
            }

            submit_result.text(result['message']);
            jQuery(ele).attr('disabled', false);
        },
        error: function(e1, e2, e3){
            submit_result.addClass('txt-error');
            submit_result.text("Verzenden van dit bericht is mislukt");
            jQuery(ele).attr('disabled', false);
        }
    });
}

