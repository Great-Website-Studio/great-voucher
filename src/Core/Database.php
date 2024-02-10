<?php

namespace GreatVoucher\Core;

class Database
{
    /**
     * @param string $sql
     * 
     * @return mixed
     */
    public static function query($sql)
    {
        global $wpdb;

        return $wpdb->query($sql);
    }

    /**
     * @return void
     */
    public static function migrate()
    {
        foreach (glob(GV_PATH . 'src/Database/Migrations/*.php') as $filename) {
            $className = 'GreatVoucher\\Database\\Migrations\\' . pathinfo(basename($filename))['filename'];
            $instance = new $className();

            self::query($instance->up());
        }
    }

    /**
     * @return void
     */
    public static function rollback()
    {
        foreach (glob(GV_PATH . 'src/Database/Migrations/*.php') as $filename) {
            $className = 'GreatVoucher\\Database\\Migrations\\' . pathinfo(basename($filename))['filename'];
            $instance = new $className();

            self::query($instance->down());
        }
    }
}
