<?php
function prdct_pllr_model_admin_style(){
    wp_enqueue_style(
        'prdct-admin-css',
        PRDP_CSS.'style.css?v='.PRDP_VS,
        array(),
        time()
    );
    wp_enqueue_style(
        'select2-admin-css',
        PRDP_CSS.'select2.min.css?v='.PRDP_VS,
        array(),
        time()
    );
    wp_enqueue_style(
        'prdct-admin-awesome-css',
        'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
        array(),
        time()
    );
}
add_action('admin_enqueue_scripts','prdct_pllr_model_admin_style');

function prdct_pllr_model_frontend_styles(){
    wp_enqueue_style(
        'pllr-frontend-css',
        PRDP_CSS.'fp.css?v='.PRDP_VS,
        time()
    );

}
add_action('wp_enqueue_scripts','prdct_pllr_model_frontend_styles',100);