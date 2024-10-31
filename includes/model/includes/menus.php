<?php
function prdct_pllr_model_admin_menu(){
    add_menu_page(
        __( 'Product Puller', 'product_puller' ),
        __( 'Product Puller', 'product_puller' ),
        'manage_options',
        'product_puller',
        'prdct_pllr_view_menu_dashboard',
        plugins_url( '../../view/assets/icon.png',__FILE__),
        999
    );
    //Puller
    add_submenu_page(
        'product_puller',
        __( 'Products', 'product_puller' ),
        __( 'Products', 'product_puller' ),
        'manage_options',
        'pull_products',
        'prdct_pllr_view_menu_products'
    );

    //Woo
    add_submenu_page(
        'product_puller',
        __('Amazon WooCommerce Product Addition', 'product_puller'),
        __('Amazon Woo Puller', 'product_puller'),
        'manage_options',
        'product_puller_woo',
        'prdct_pllr_view_menu_woo'
    );

    add_submenu_page(
        'product_puller',
        __( 'Product Comparison', 'product_puller' ),
        __( 'Product Comparison', 'product_puller' ),
        'manage_options',
        'product_puller_comparison',
        'prdct_pllr_view_menu_comparison'
    );

    //Language
    add_submenu_page(
        'product_puller',
        __( 'Language Settings', 'product_puller' ),
        __( 'Language Settings', 'product_puller' ),
        'manage_options',
        'product_puller_lang',
        'prdct_pllr_view_menu_langs'
    );
}
add_action('admin_menu','prdct_pllr_model_admin_menu');