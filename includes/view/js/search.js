jQuery(document).ready(function () {


    jQuery('#prd-src').on('click', function() {
        let Src = jQuery('#prd-src');
        let check = jQuery('#prd-searched-now').val();
        let value = "";
        let input = jQuery('#prd-src-input');
        let page= jQuery('#prd-page-now').val();
        let woo = jQuery('#prd_woo_exist').length>0 ? 'woo' : 'normal';
        if(check==="") {
            value = input.val();
        }else{
            value = check;
            if(value!==input.val()){
                value=input.val();
                page = 1;
                jQuery('.prd-content').html('');
            }
        }
        if(value !=="") {
            Src.html('<i class="fa fa-spinner prd-rotating"></i>');
            jQuery.ajax({
                url: puller_search.ajaxurl,
                type: 'POST',
                data: {
                    action: 'prdct_search',
                    value: value,
                    page: page,
                    woo:woo
                },
                success: function (data) {
                    Src.html('<i class="fa fa-search"></i> Search more');
                    jQuery('#prd-searched-now').val(value);
                    page++;
                    jQuery('.prd-content').append(data);
                    jQuery('#prd-page-now').val(page);
                    jQuery('#prd-clear').removeClass('prd-hidden');
                }
            });
        }else{
            alert('Please enter a value');
        }

    });

    jQuery('#prd-clear').on('click', function (){
        jQuery('#prd-src').html('Search');
        jQuery('#prd-page-now').val(1);
        jQuery('.prd-content').html('');
        jQuery('#prd-searched-now').val('');
        jQuery('#prd-clear').addClass('prd-hidden');
    });

    jQuery(document).on('click','.prd-product-add',function (){
           let id = jQuery(this).attr("id");
           let price = jQuery('#price_input'+id).val();
           let img = jQuery('#img_input'+id).val();
           let title = jQuery('#title_input'+id).val();
            jQuery(this).html('<i class="fa fa-spinner prd-rotating"></i>');
            jQuery.ajax({
                url: puller_search.ajaxurl,
                type: 'POST',
                dataType:"html",
                data:{
                    action: 'prdct_save',
                    price: price,
                    title:title,
                    img:img,
                    product_id: id
                },
                success: function (data){
                    // jQuery('#test').html(data);
                    jQuery('#'+id).html('<i class="fa fa-check" style="color:green"></i>')
                }
            });
    });

    jQuery(document).on('click','#prdct_edit',function (){
        let button = jQuery(this);
        button.html('<i class="fa fa-spinner prd-rotating"></i>');
        let price = jQuery('#pr_price').val();
        let title = jQuery('#pr_title').val();
        let id = jQuery('#pr_id').val();
        jQuery.ajax({
            url: puller_search.ajaxurl,
            type: 'POST',
            data:{
                action: 'prdct_edit',
                price: price,
                title:title,
                id:id
            },
            success: function (){
                button.css('border','1px solid green');
                button.css('color','green');
                button.html('Saved <i class="fa fa-check"></i>')
            }
        });

    });


})

var input = document.getElementById("prd-src-input");

// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
    // Number 13 is the "Enter" key on the keyboard
    if (event.keyCode === 13) {
        // Cancel the default action, if needed
        event.preventDefault();
        // Trigger the button element with a click
        document.getElementById("prd-src").click();
    }
});