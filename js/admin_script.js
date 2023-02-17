( function( $ ) {

	'use strict';

    $( function() {

        $("#l4l-contact-form-editor").tabs({
            active: 3,
            activate: function( event, ui ) {
                $( '#active-tab' ).val( ui.newTab.index() );
            }
        } 
        );
        if($(".contact-form-widget-table tr.contact-form-widget-row").length == 0)
        addNewL4lContactWidget();
        

        
    });

})( jQuery );

function deleteL4lContactWidget(element)
{
    jQuery(element).parent().parent().remove();
}

function addNewL4lContactWidget()
{
    var html = '<tr class="contact-form-widget-row">' +
    '<td>' +
        '<select class="contact-form-widget-type" name="contact-form-widget-type[]">' +
            '<option value="" default></option>' +
            '<option value="TEXT">Text</option>' +
            '<option value="EMAIL">Email</option>' +
            '<option value="PHONE">Phone</option>' +
            '<option value="TEXTAREA">Textarea</option>' +
        '</select>' +
    '</td>' + 
    '<td><input type="text" class="contact-form-widget-label" maxlength=30 name="contact-form-widget-label[]" /></td>' +
    '<td><input type="text" class="contact-form-widget-id" maxlength=30 name="contact-form-widget-id[]" /></td>' + 
    '<td><input type="text" class="contact-form-widget-placeholder" maxlength=30 name="contact-form-widget-placeholder[]"/></td>' +
    '<td>' + 
    '<select class="contact-form-widget-mandatory"  name="contact-form-widget-mandatory[]">' + 
        '<option value = 1 >Required</option>' + 
        '<option value = 0 >Not Required</option>' + 
    '</select>' +
    '</td>' +
    '<td><a href="JavaScript:void(0);" onclick="deleteL4lContactWidget(this)">Delete</a></td>' +   
    '</tr>';
    jQuery(".contact-form-widget-table").append(html);
}

function saveL4LContactFormData()
{
    const l4l_contact_form = jQuery("#l4l-admin-form-element");
    // var contact_form_widget_rows = [];
    // jQuery(".contact-form-widget-table .contact-form-widget-row").each((index, item) => {
    //     contact_form_widget_rows.push({
    //         widget_type : jQuery(item).find(".contact-form-widget-type").val(),
    //         widget_id : jQuery(item).find(".contact-form-widget-id").val(),
    //         placeholder : jQuery(item).find(".contact-form-widget-placeholder").val(),
    //         label : jQuery(item).find(".contact-form-widget-label").val(),
    //         mandatory : jQuery(item).find(".contact-form-widget-mandatory").prop('checked'),
    //     });
    // });
    // l4l_contact_form.data('contact_form_widget_rows', "123");
    l4l_contact_form.submit();
}


function deleteContactFormSubmit()
{
    const l4l_contact_form = jQuery("#l4l-admin-form-element");
    jQuery("#l4l-admin-form-element #contact-form-action").val("delete");
    l4l_contact_form.submit();
}