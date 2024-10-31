jQuery(document).ready(function () {
    jQuery('#plr_save_lang').on('click',function (){
        let button = jQuery(this);
        button.html('<i class="fa fa-spinner prd-rotating"></i>');
        let langs = {};
        jQuery('.plr-lang-inputs').each(function () {
            langs[jQuery(this).attr('name')] = jQuery(this).val();
        });
        jQuery.ajax({
            url: puller_save_lang.ajaxurl,
            type: 'POST',
            data: {
                action: 'prdct_lang',
                lang: langs,
            },
            success: function (data){
                button.html('Saved <i class="fa fa-check"></i>');
                button.css('border','1px solid green');
                button.css('color','green');
                jQuery('.plr-lang-inputs').css('border','1px solid green');
            },
        });
    })





})