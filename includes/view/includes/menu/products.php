<?php
function prdct_pllr_view_menu_products(){
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <h1><?php esc_html_e(get_admin_page_title()); ?></h1>
    <?php
    $add = @$_GET['add'] == "new";
    if($add){
        include PRDP_VIEW_INC.'menu/products.add.php';
    }else{
        if(isset($_GET['id'])){
            include PRDP_VIEW_INC . 'menu/products.edit.php';
        }else {
            include PRDP_VIEW_INC . 'menu/products.menu.php';
        }
    }
}