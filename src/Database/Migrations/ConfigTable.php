<?php

namespace GreatVoucher\Database\Migrations;

class ConfigTable
{
    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var string
     */
    protected $charsetCollate;

    public function __construct()
    {
        global $wpdb;

        $this->tableName = $wpdb->prefix . 'gv_configurations';
        $this->charsetCollate = $wpdb->get_charset_collate();
    }

    /**
     * @return void
     */
    public function up()
    {
        return "CREATE TABLE {$this->tableName} (
            id INT(11) NOT NULL AUTO_INCREMENT,
            config_key VARCHAR(255) NOT NULL UNIQUE,
            config_value VARCHAR(255) NOT NULL,
            PRIMARY KEY  (id)
        ) {$this->charsetCollate};";
    }

    /**
     * @return void
     */
    public function down()
    {
        return "DROP TABLE {$this->tableName};";
    }
}
