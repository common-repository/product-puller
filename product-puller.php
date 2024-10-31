<?php
/*
Plugin Name: Product Puller
Description: This plugin allows you to automatically import the product information from Amazon and add show it in everywhere.
Version: 1.5.1
Author: Kemal YAZICI / PluginPress
Author URI: http://pluginpress.net?Encoding=UTF8&showVariations=true&smid=A3P5ROKL5A1OLE&pf_rd_p=3d142137-7236-4d73-8df3-c0b943b7075e&pd_rd_wg=hXsJv&pf_rd_r=4A0R45W1V60BSTGG83HY&ref=wp_official_puller&from=wordpress.org/plugins/product-puller
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: product-puller
*/
//Root
// If this file calls directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
wp_enqueue_editor();
define('PRDP_VS','1.5.1');
define('PRDP_URL',plugin_dir_url(__FILE__));
define('PRDP_FILE', __FILE__);
define('PRDP_ROOT',plugin_dir_path(__FILE__));

//Model
include PRDP_ROOT.'includes/model/model.php';

//Controller
include PRDP_ROOT.'includes/controller/controller.php';

//View
include PRDP_ROOT.'includes/view/view.php';

