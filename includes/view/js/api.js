jQuery(document).ready(function () {
    jQuery('#pllr-api-btn').on('click', function() {
        let input = jQuery('#pllr-api-input')
        let api = input.val();
        let button = jQuery(this);
        button.html('<i class="fa fa-spinner prd-rotating"></i>');
        jQuery.ajax({
            url: puller_api.ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'prdct_api',
                api: api,
            },
            success:function (data) {
                if (data.member === "active") {
                    button.html('Update <i class="fa fa-check"></i>');
                    button.css('border', '2px solid green');
                    button.css('color', 'green');
                    input.css('border', '2px solid green');
                    input.css('color', 'green');
                    jQuery('#api-msg').html('<div style="color:green">You have successfully activated your api-key!</div>');
                    jQuery('#plr-card-1').addClass('plr-dashcard-bought');
                    jQuery('#title-card-1').html('Detailed Products');
                    if(typeof data.product_comparison!== 'undefined'){
                        jQuery('#plr-card-2').addClass('plr-dashcard-bought');
                        jQuery('#title-card-2').html('Product Comparison');
                    }else{
                        jQuery('#plr-card-2').removeClass('plr-dashcard-bought');
                        jQuery('#title-card-2').html('Pull a product comparison from Amazon.');
                    }
                    if(typeof data.amazon_woo_puller!== 'undefined'){
                        jQuery('#plr-card-3').addClass('plr-dashcard-bought');
                        jQuery('#title-card-3').html('Amazon Woo Puller');
                    }else{
                        jQuery('#plr-card-3').removeClass('plr-dashcard-bought');
                        jQuery('#title-card-3').html('Import an Amazon product data to WooCommerce.');
                    }
                } else {
                    button.html('Check');
                    button.css('border', '2px solid red');
                    button.css('color', 'red');
                    input.css('border', '2px solid red');
                    input.css('color', 'red');
                    let profileUrl = jQuery('#profile_puller_hidden').val();
                    jQuery('#api-msg').html('<div style="color:red">No confirmation. Make sure you have entered your api-key correctly or check if you have entered your web address correctly in your <a href="'+profileUrl+'" target="_blank">pluginpress</a> profile.</div>');
                    jQuery('#plr-card-1').removeClass('plr-dashcard-bought');
                    jQuery('#plr-card-2').removeClass('plr-dashcard-bought');
                    jQuery('#plr-card-3').removeClass('plr-dashcard-bought');
                    jQuery('#title-card-1').html('You can show the products in more detailed way.');
                    jQuery('#title-card-2').html('Pull a product comparison from Amazon.');
                    jQuery('#title-card-3').html('Import an Amazon product data to WooCommerce.');
                }
            }
        });
    });
});