<?php

/**
 * Plugin Name: Great Voucher
 * Description: Providing services to assist you in generating voucher codes and using them.
 * Author: Great Website Studio
 * Author URI: https://great.web.id
 * Version: 0.0.1
 * Text Domain: great-voucher
 *
 * Copyright: (c) 2024 Great Website Studio. All rights reserved.
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   Great Voucher
 * @author    Great Website Studio
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 * Requires PHP: 8.0
 * WC requires at least: 7.0.0
 * WC tested up to: 7.2.2
 */

use GreatVoucher\Core\Application;

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Global constants.
 */

defined('ABSPATH') or die('Hey, you can\t access this file, you silly human !');

if (!defined('GV_FILE')) define('GV_FILE', __FILE__);

if (!defined('GV_PATH')) define('GV_PATH', plugin_dir_path(__FILE__));

/**
 * Register activation and deactivation hook.
 */

register_activation_hook(
    __FILE__,
    [
        \GreatVoucher\Core\Application::class,
        'activate'
    ]
);

register_deactivation_hook(
    __FILE__,
    [
        \GreatVoucher\Core\Application::class,
        'deactivate'
    ]
);

$application = Application::getInstance();
