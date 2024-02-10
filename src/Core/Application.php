<?php

namespace GreatVoucher\Core;

class Application
{
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
}
