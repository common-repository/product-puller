<?php
/***************** SEARCH **********************/
// Add rating js
function prdct_pllr_model_admin_ajax_search() {
    // load our jquery file that sends the $.post request
    wp_enqueue_script( "prdct-pllr-src-ajax", PRDP_JS. 'search.js', array( 'jquery' ) );

    // make the ajaxurl var available to the above script
    wp_localize_script( 'prdct-pllr-src-ajax', 'puller_search', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('admin_footer', 'prdct_pllr_model_admin_ajax_search');


//search
function prdct_pllr_model_admin_ajax_search_request(){
    $controller = new prdct_pllr_ajax_controller();
    $controller->product_search();
}
add_action( 'wp_ajax_prdct_search', 'prdct_pllr_model_admin_ajax_search_request');
//save
function prdct_pllr_model_admin_ajax_save_request(){
    $controller = new prdct_pllr_ajax_controller();
    if(get_option('product_puller_api_key')){
        $controller->amazon_full_save();
    }else{
        $controller->amazon_standard_save();
    }

}
add_action( 'wp_ajax_prdct_save', 'prdct_pllr_model_admin_ajax_save_request');
//edit
function prdct_pllr_model_admin_ajax_edit_request(){
    $controller = new prdct_pllr_ajax_controller();
    $controller->amazon_standard_edit();
}
add_action( 'wp_ajax_prdct_edit', 'prdct_pllr_model_admin_ajax_edit_request');


/***************** SELECT AMAZON LOCAL **********************/
function prdct_pllr_model_admin_ajax_amazon_local(){
    wp_enqueue_script( "prdct-pllr-amazon-local-ajax", PRDP_JS. 'amazonlocal.js', array( 'jquery' ) );

    // make the ajaxurl var available to the above script
    wp_localize_script( 'prdct-pllr-amazon-local-ajax', 'puller_amazon_local', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('admin_footer', 'prdct_pllr_model_admin_ajax_amazon_local');



function prdct_pllr_model_admin_ajax_amazon_local_request(){
    $controller = new prdct_pllr_ajax_controller();
    $controller->amazon_local_save();
}
add_action( 'wp_ajax_prdct_amazon', 'prdct_pllr_model_admin_ajax_amazon_local_request');

/******************************** API KEY *******************************/

// Api key check
function prdct_pllr_model_admin_ajax_check_api() {
    // load our jquery file that sends the $.post request
    wp_enqueue_script( "prdct-pllr-api-ajax", PRDP_JS. 'api.js', array( 'jquery' ) );

    // make the ajaxurl var available to the above script
    wp_localize_script( 'prdct-pllr-api-ajax', 'puller_api', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('admin_footer', 'prdct_pllr_model_admin_ajax_check_api');

//check
function prdct_pllr_model_admin_ajax_check_api_request(){
    $controller = new prdct_pllr_ajax_controller();
    $controller->check_api();
}
add_action( 'wp_ajax_prdct_api', 'prdct_pllr_model_admin_ajax_check_api_request');

/******************************* LANGUAGE SETTINGS *************************/
function prdct_pllr_model_admin_ajax_save_lang(){
    wp_enqueue_script( "prdct-pllr-save-lang-ajax", PRDP_JS. 'lang.js', array( 'jquery' ) );

    // make the ajaxurl var available to the above script
    wp_localize_script( 'prdct-pllr-save-lang-ajax', 'puller_save_lang', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('admin_footer', 'prdct_pllr_model_admin_ajax_save_lang');

//check
function prdct_pllr_model_admin_ajax_save_lang_request(){
    $controller = new prdct_pllr_ajax_controller();
    $controller->save_lang();
}
add_action( 'wp_ajax_prdct_lang', 'prdct_pllr_model_admin_ajax_save_lang_request');
/******************************* WOO SETTINGS *************************/
function prdct_pllr_model_admin_ajax_save_woo(){
    wp_enqueue_script( "prdct-pllr-save-woo-ajax", PRDP_JS. 'woo.js', array( 'jquery' ) );

    // make the ajaxurl var available to the above script
    wp_localize_script( 'prdct-pllr-save-woo-ajax', 'puller_save_woo', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('admin_footer', 'prdct_pllr_model_admin_ajax_save_woo');

//check
function prdct_pllr_model_admin_ajax_save_woo_request(){
    $controller = new prdct_pllr_ajax_controller();
    if($_POST['post_id']==0) {
        $controller->save_woo();
    }else{
        $product_id = sanitize_text_field($_POST['post_id']);
        $data = $controller->get_product(sanitize_text_field($_POST['product_id']));
        $json = json_decode(base64_decode($data->cache));
        $controller->AddAttributes($product_id,$json->details);

    }

}
add_action( 'wp_ajax_prdct_woo', 'prdct_pllr_model_admin_ajax_save_woo_request');

/******************************* COMPARISON SETTINGS *************************/
function prdct_pllr_model_admin_ajax_comparison(){
    wp_enqueue_script( "prdct-pllr-comparison-ajax", PRDP_JS. 'comparison.js', array( 'jquery' ) );

    // make the ajaxurl var available to the above script
    wp_localize_script( 'prdct-pllr-comparison-ajax', 'puller_comparison', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('admin_footer', 'prdct_pllr_model_admin_ajax_comparison');

//check
function prdct_pllr_model_admin_ajax_comparison_request(){
    $controller = new prdct_pllr_ajax_controller();
    $controller->comparisonAjax();
}
add_action( 'wp_ajax_prdct_comp', 'prdct_pllr_model_admin_ajax_comparison_request');
/******************************* COMPARISON ADD *************************/
function prdct_pllr_model_admin_ajax_comparison_add(){
    wp_enqueue_script( "prdct-pllr-comparison-ajax-add", PRDP_JS. 'comparison-add.js', array( 'jquery' ) );

    // make the ajaxurl var available to the above script
    wp_localize_script( 'prdct-pllr-comparison-ajax-add', 'puller_comparison_add', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('admin_footer', 'prdct_pllr_model_admin_ajax_comparison_add');

//check
function prdct_pllr_model_admin_ajax_comparison_add_request(){
    $controller = new prdct_pllr_ajax_controller();
    $controller->comparisonAddAjax();
}
add_action( 'wp_ajax_prdct_comp_add', 'prdct_pllr_model_admin_ajax_comparison_add_request');
/******************************* COMPILE ATTR *************************/
function prdct_pllr_model_admin_ajax_compile_attr(){
    wp_enqueue_script( "prdct-pllr-compile-ajax-attr", PRDP_JS. 'compile.attr.js', array( 'jquery' ) );

    // make the ajaxurl var available to the above script
    wp_localize_script( 'prdct-pllr-compile-ajax-attr', 'puller_compile_attr', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('admin_footer', 'prdct_pllr_model_admin_ajax_compile_attr');

//check
function prdct_pllr_model_admin_ajax_compile_attr_request(){
    $controller = new prdct_pllr_ajax_controller();
    $controller->compileAttr();
}
add_action( 'wp_ajax_prdct_compile_attr', 'prdct_pllr_model_admin_ajax_compile_attr_request');

/******************************* SAVE ASSOCIATE ID *************************/

function prdct_pllr_model_admin_ajax_save_affi(){
    wp_enqueue_script( "prdct-pllr-save-affi-ajax", PRDP_JS. 'affi.js', array( 'jquery' ) );

    // make the ajaxurl var available to the above script
    wp_localize_script( 'prdct-pllr-save-affi-ajax', 'puller_save_affi', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action('admin_footer', 'prdct_pllr_model_admin_ajax_save_affi');

//check
function prdct_pllr_model_admin_ajax_save_affi_request(){
    $controller = new prdct_pllr_ajax_controller();
    $controller->save_affi();
}
add_action( 'wp_ajax_prdct_affi', 'prdct_pllr_model_admin_ajax_save_affi_request');

