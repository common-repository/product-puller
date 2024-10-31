<?php
//CREATING TABLE
function prdct_pllr_create_table() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE ".$wpdb->base_prefix."product_puller (
            `id` BIGINT(20) NOT NULL AUTO_INCREMENT , 
            `product_id` VARCHAR(255) NOT NULL ,
            `title` VARCHAR(255) NOT NULL ,  
            `type` VARCHAR(255) NOT NULL DEFAULT 'amazon' ,
            `price` VARCHAR(255) NOT NULL ,
            `image` VARCHAR(255) NOT NULL ,
            `local` VARCHAR(10) NOT NULL ,
            `cache` LONGTEXT NOT NULL,
            `page` INT(3) NOT NULL,
            UNIQUE KEY id (id)
        ) ".$charset_collate.";";



    if ( ! function_exists('dbDelta') ) {

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    }

    dbDelta( $sql );
    if(!get_option('product_puller_plugin_version')) {
        add_option('product_puller_plugin_version', PRDP_VS,'',"yes");
    }else{
        update_option('product_puller_plugin_version', PRDP_VS);
    }
}
// Creating table
register_activation_hook( PRDP_FILE, 'prdct_pllr_create_table' );

$pllr_db_version = get_option( 'product_puller_plugin_version' ) ? get_option( 'product_puller_plugin_version' ) : "1.0.0";

if(version_compare( $pllr_db_version, PRDP_VS, '<' )) {
    update_option('product_puller_plugin_version', PRDP_VS);
}
