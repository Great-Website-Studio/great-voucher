<?php

namespace GreatVoucher\App;

class Admin
{
    /**
     * @var \GreatVoucher\App\Admin
     */
    private static $instance = null;

    public function __construct()
    {
        if (!session_id()) session_start();

        add_action('admin_menu', [$this, 'menu']);
        add_action('admin_post_save_great_voucher_configuration', [$this, 'saveConfiguration']);
    }

    /**
     * @return void
     */
    public function menu()
    {
        add_menu_page(
            'Great Voucher',
            'Great Voucher',
            'manage_options',
            'great-voucher',
            [$this, 'page'],
            'dashicons-tickets',
            7
        );
    }

    /**
     * @return void
     */
    public function page()
    {
        require_once GV_PATH . 'views/index.php';
    }

    /**
     * @return void
     */
    public function saveConfiguration()
    {
        if (!session_id()) session_start();

        if (isset($_POST['access_key']) && isset($_POST['secret_key'])) {
            $access_key = sanitize_text_field($_POST['access_key']);
            $secret_key = sanitize_text_field($_POST['secret_key']);

            global $wpdb;

            $tableName = $wpdb->prefix . 'gv_configurations';

            $wpdb->replace(
                $tableName,
                [
                    'config_key' => 'access_key',
                    'config_value' => $access_key,
                ],
                [
                    '%s',
                    '%s',
                ]
            );

            $wpdb->replace(
                $tableName,
                [
                    'config_key' => 'secret_key',
                    'config_value' => $secret_key,
                ],
                [
                    '%s',
                    '%s',
                ]
            );
        }

        $_SESSION['gv_flash_message']['success'] = [
            'value' => 'Successfully saved configuration.',
            'called' => false,
        ];

        wp_redirect(admin_url('admin.php?page=great-voucher'));

        exit();
    }

    /**
     * @return \GreatVoucher\App\Admin
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __destruct()
    {
        $session = $_SESSION['gv_flash_message'];

        foreach ($session as $key => $value) {
            if (isset($value['called']) && $value['called'] == true) {
                unset($session[$key]);
            }
        }

        $_SESSION['gv_flash_message'] = $session;
    }
}
