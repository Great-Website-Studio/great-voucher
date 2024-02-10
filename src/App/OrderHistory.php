<?php

namespace GreatVoucher\App;

class OrderHistory
{
    /**
     * @var \GreatVoucher\App\OrderHistory
     */
    private static $instance = null;

    public function __construct()
    {
        add_action('woocommerce_order_details_after_order_table', [$this, 'renderVouchers']);
    }

    /**
     * @param mixed $order
     * 
     * @return void
     */
    public function renderVouchers($order)
    {
        global $wpdb;

        $status = $order->get_status();

        if (in_array($status, ['processing', 'completed'])) {
            echo "<script src='https://cdn.tailwindcss.com'></script>";

            echo "<table class='w-full my-5'>";

            echo "<tr class='border-b border-gray-300'>";
            echo "<th class='text-left px-5 py-4'>No</th>";
            echo "<th class='text-left px-5 py-4'>Voucher Name</th>";
            echo "<th class='text-left px-5 py-4'>Code</th>";
            echo "</tr>";

            $vouchers = [];

            foreach ($order->get_items() as $item_id => $item) {

                $tableName = $wpdb->prefix . 'gv_generated_vouchers';

                $query = $wpdb->prepare("SELECT * FROM $tableName WHERE item_id = %d", $item_id);
                $results = $wpdb->get_results($query);

                foreach ($results as $key => $item) {
                    $vouchers[] = $item;
                }
            }

            foreach ($vouchers as $key => $voucher) {
                $key++;

                echo "<tr>";
                echo "<td class='px-5 py-4'>{$key}</td>";
                echo "<td class='px-5 py-4'>{$voucher->title}</td>";
                echo "<td class='px-5 py-4'>{$voucher->code}</td>";
                echo "</tr>";
            }

            echo "</table>";
        }
    }

    /**
     * @return \GreatVoucher\App\OrderHistory
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
