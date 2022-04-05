<?php
/**
 * Plugin Name: Veda Global Code Plug In
 * Plugin URI: https://veda.myshopkit.app
 * Author: MyShopKit
 * Author URI: https://myshopkit.app
 * Version: 1.0
 * Description: Veda Global Code Plug In
 */

define("GLOBAL_CODE_PREFIX", "vdgc_");
define("GLOBAL_CODE_NAMESPACE", "global-code");
define("GLOBAL_CODE_REST_NAMESPACE", "veda");
define("GLOBAL_CODE_VERSION", "v1");
define("GLOBAL_CODE_REST_ROOT", GLOBAL_CODE_REST_NAMESPACE."/".GLOBAL_CODE_VERSION);
//define("VEDA_ZUUL_API", "http://192.168.50.65:8762/api/v1");
//define("GLOBAL_CODE_REST_ENDPOINT", VEDA_ZUUL_API . GLOBAL_CODE_REST_ROOT);
//define("VEDA_SHOPIFY_REST_ENDPOINT", VEDA_ZUUL_API . "/auth/shopify");
define("GLOBAL_CODE_URL", plugin_dir_url(__FILE__));


add_filter( 'wp_is_application_passwords_available', '__return_true' );
require_once plugin_dir_path(__FILE__) . "vendor/autoload.php";
require_once plugin_dir_path(__FILE__) . "src/GlobalCode/GlobalCode.php";
