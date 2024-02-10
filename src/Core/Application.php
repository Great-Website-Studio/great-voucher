<?php

namespace GreatVoucher\Core;

class Application
{
    /**
     * @var \GreatVoucher\Core\Application
     */
    private static $instance = null;

    /**
     * @var \GreatVoucher\App\Admin
     */
    protected $admin;

    /**
     * @var \GreatVoucher\App\Order
     */
    protected $order;

    /**
     * @var \GreatVoucher\App\OrderHistory
     */
    protected $orderHistory;

    public function __construct()
    {
        $this->admin = \GreatVoucher\App\Admin::getInstance();
        $this->order = \GreatVoucher\App\Order::getInstance();
        $this->orderHistory = \GreatVoucher\App\OrderHistory::getInstance();
    }

    /**
     * @return void
     */
    public static function activate()
    {
        if (!class_exists('WooCommerce')) {
            die('Required <b>WooCommerce</b> plugin activate.');
        }

        Database::migrate();
    }

    /**
     * @return void
     */
    public static function deactivate()
    {
        Database::rollback();
    }

    /**
     * @return \GreatVoucher\Core\Application
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
