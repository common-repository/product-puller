<?php

add_action( 'admin_footer', 'prdct_pllr_admin_js');

function prdct_pllr_admin_js(){
    wp_register_script( 'pllr_product_admin_sort_js', PRDP_JS.'sortable.js', array('jquery','jquery-ui-sortable'));
    wp_enqueue_script( 'pllr_product_admin_sort_js' );
    wp_register_script( 'pllr_product_admin_select2_js', PRDP_JS.'select2.min.js', array('jquery'));
    wp_enqueue_script( 'pllr_product_admin_select2_js' );

}
add_action( 'wp_footer', 'prdct_pllr_frontpage_js',10);

function prdct_pllr_frontpage_js(){

    wp_register_script( 'pllr_product_image_js', PRDP_JS. 'product-image.js', array('jquery'));
    wp_enqueue_script( 'pllr_product_image_js' );



}