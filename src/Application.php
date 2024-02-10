<?php

namespace GreatVoucher;

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

        self::migrate();
    }

    /**
     * @return void
     */
    public static function deactivate()
    {
        self::rollback();
    }

    /**
     * @return void
     */
    public static function migrate()
    {
        foreach (glob(GV_PATH . 'src/Database/Migrations/*.php') as $filename) {
            $class = pathinfo(basename($filename))['filename'];
        }
    }

    /**
     * @return void
     */
    public static function rollback()
    {
    }
}
