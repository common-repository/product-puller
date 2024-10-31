<?php
// Shortcode functions
function product_puller_main_shortcode($args, $content=""){
    $controller = new prdct_pllr_general_controller();
    $html = "";
    if(isset($args['id'])) {
        $data = $controller->get_product($args['id']);
        if(@$args['data']=="detailed") {
            if(get_option('product_puller_plr_fetch_full')) {
                return $controller->view('shortcodes.detailed', json_decode(json_encode($data), true));
            }else{
                echo '<div style="padding: 20px; color:#fff;background-color: #0c0c0c">You have to register <a href="https://pluginpress.net/register" target="_blank" style="color:#fff!important;">pluginppress.net</a> and activate your api-key to use this option.</div>';
            }
        }else{
            return $controller->view('shortcodes.main', json_decode(json_encode($data), true));
        }
    }else if(isset($args['ids'])){
        $ids = explode(",",$args['ids']);
        if(count($ids)>0){
            $arr = array();
            $arr['controller'] = $controller;
            foreach ($ids as $id){
                $data = $controller->get_product(trim($id));
                if($data !="") {
                    $arr['products'][$id] = $data;
                }
            }
        }
        return $controller->view('shortcodes.bulk', $arr, true);
    }

}


function product_puller_comparison_shortcode($args, $content=""){
    $controller = new prdct_pllr_general_controller();
    $data = $controller->get_product($args['id']);
    if($data!="") {
        $arr = array();
        $arr['comp'] = json_decode(base64_decode($data->cache));
        $arr['controller'] = $controller;
        return $controller->view('shortcodes.comparison', $arr);
    }
}


//Add shortcodes
function product_puller_register_shortcodes(){
    add_shortcode('puller', 'product_puller_main_shortcode');
    add_shortcode('comparison', 'product_puller_comparison_shortcode');
}
add_action('init', 'product_puller_register_shortcodes');