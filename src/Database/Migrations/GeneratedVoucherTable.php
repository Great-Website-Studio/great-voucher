<?php

namespace GreatVoucher\Database\Migrations;

class GeneratedVoucherTable
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

        $this->tableName = $wpdb->prefix . 'gv_generated_vouchers';
        $this->charsetCollate = $wpdb->get_charset_collate();
    }

    /**
     * @return void
     */
    public function up()
    {
        return "CREATE TABLE {$this->tableName} (
            id INT(11) NOT NULL AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            code VARCHAR(255) NOT NULL UNIQUE,
            discount BIGINT(11) NOT NULL,
            item_id INT(11) NOT NULL,
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
