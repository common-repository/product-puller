<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}



delete_option('product_puller_plugin_version');
delete_site_option('product_puller_plugin_version');
delete_option('product_puller_local_amazon');
delete_site_option('product_puller_local_amazon');



// drop a custom database table

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}product_puller");