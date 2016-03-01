<?php
/**
 * Plugin Name: dbDelta Test
 * Plugin URI: https://www.storelocatorplus.com/product/db-delta-test/
 * Description: dbDelta Testing
 * Author: Store Locator Plus
 * Author URI: http://storelocatorplus.com/
 * Requires at least: 4.4
 * Tested up to : 4.4.2
 * Version: 4.4.2
 *
 * Text Domain: db-delta-test
 * Domain Path: /languages/
 */

// Exit if access directly, dang hackers
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load db Delta Test after plugins loaded.
 */
function db_delta_test_loader() {
    if ( ! defined( 'DBDELTA_TEST_REL_DIR'  ) ) { define( 'DBDELTA_TEST_REL_DIR'    , plugin_basename( dirname( __FILE__ ) ) ); }  // Relative directory for this plugin in relation to wp-content/plugins
    if ( ! defined( 'DBDELTA_TEST_FILE'     ) ) { define( 'DBDELTA_TEST_FILE'       ,  __FILE__                              ); }  // FQ File name for this file.
    require_once('include/db_schema.php');
    dbDelta_db_schema::init();
}

add_action('plugins_loaded', 'db_delta_test_loader');
