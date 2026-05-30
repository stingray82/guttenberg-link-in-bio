<?php
/**
 * Plugin Name:       Guttenberg Link in Bio
 * Description:       Build link in bio pages using Gutenberg blocks, sections and social icons.
 * Tested up to:      7.0
 * Requires at least: 6.5
 * Requires PHP:      8.0
 * Version:           1.0
 * Author:            ReallyUsefulPlugins.com
 * Author URI:        https://reallyusefulplugins.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       guttenberg-link-in-bio
 * Website:           https://reallyusefulplugins.com
 */

if (!defined('ABSPATH')) {
    exit;
}

define('RUP_GUTTENBERG_LINK_IN_BIO_VERSION', '1.0');
define('RUP_GUTTENBERG_LINK_IN_BIO_SLUG', 'guttenberg-link-in-bio');
define('RUP_GUTTENBERG_LINK_IN_BIO_MAIN_FILE', __FILE__);
define('RUP_GUTTENBERG_LINK_IN_BIO_DIR', plugin_dir_path(__FILE__));
define('RUP_GUTTENBERG_LINK_IN_BIO_URL', plugin_dir_url(__FILE__));


add_action('plugins_loaded', function () {
    $updater_file = RUP_GUTTENBERG_LINK_IN_BIO_DIR . '/inc/updater.php';

    if (!file_exists($updater_file)) {
        return;
    }

    require_once $updater_file;

    if (!class_exists('\RUP\Updater\Updater_V2')) {
        return;
    }

    $updater_config = [
        'vendor'      => 'RUP',
        'plugin_file' => plugin_basename(__FILE__),
        'slug'        => RUP_GUTTENBERG_LINK_IN_BIO_SLUG,
        'name'        => 'Guttenberg Link in Bio',
        'version'     => RUP_GUTTENBERG_LINK_IN_BIO_VERSION,
        'key'         => '',
        'server'      => 'https://raw.githubusercontent.com/stingray82/guttenberg-link-in-bio/main/uupd/index.json',
    ];

    \RUP\Updater\Updater_V2::register($updater_config);
}, 20);

/**
 * MainWP icon support.
 */
add_filter('mainwp_child_stats_get_plugin_info', function ($info, $slug) {
    if ('guttenberg-link-in-bio/guttenberg-link-in-bio.php' === $slug) {
        $info['icon'] = 'https://raw.githubusercontent.com/stingray82/guttenberg-link-in-bio/main/uupd/icon-128.png';
    }

    return $info;
}, 10, 2);

/**
 * Register block assets and block types.
 */
add_action('init', function () {
    wp_register_style(
        'lpb-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css',
        [],
        '6.5.2'
    );

    wp_register_script(
        'lpb-blocks',
        RUP_GUTTENBERG_LINK_IN_BIO_URL . 'assets/js/blocks.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-compose'],
        RUP_GUTTENBERG_LINK_IN_BIO_VERSION,
        true
    );

    wp_register_script(
        'lpb-frontend',
        RUP_GUTTENBERG_LINK_IN_BIO_URL . 'assets/js/frontend.js',
        [],
        RUP_GUTTENBERG_LINK_IN_BIO_VERSION,
        true
    );

    wp_register_style(
        'lpb-style',
        RUP_GUTTENBERG_LINK_IN_BIO_URL . 'assets/css/style.css',
        ['lpb-fontawesome'],
        RUP_GUTTENBERG_LINK_IN_BIO_VERSION
    );

    register_block_type('lpb/wrapper', [
        'editor_script' => 'lpb-blocks',
        'script'        => 'lpb-frontend',
        'style'         => 'lpb-style',
        'editor_style'  => 'lpb-style',
    ]);

    register_block_type('lpb/section', [
        'editor_script' => 'lpb-blocks',
        'style'         => 'lpb-style',
        'editor_style'  => 'lpb-style',
    ]);

    register_block_type('lpb/button', [
        'editor_script' => 'lpb-blocks',
        'style'         => 'lpb-style',
        'editor_style'  => 'lpb-style',
    ]);

    register_block_type('lpb/socials', [
        'editor_script' => 'lpb-blocks',
        'style'         => 'lpb-style',
        'editor_style'  => 'lpb-style',
    ]);
});
