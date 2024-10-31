jQuery(document).ready(function () {
    jQuery('#save_amazon_local').on('click', function() {
        let local = jQuery('#amazon_local').val();
        let button = jQuery(this);
        button.html('<i class="fa fa-spinner prd-rotating"></i>');
        jQuery.ajax({
            url: puller_amazon_local.ajaxurl,
            type: 'POST',
            data: {
                action: 'prdct_amazon',
                local: local,
            },
            success:function (){
                button.html('Saved <i class="fa fa-check"></i>');
                button.css('border','1px solid green');
                button.css('color','green');
                jQuery('#amazon_local').css('border','1px solid green');
            }
        });
    });
});