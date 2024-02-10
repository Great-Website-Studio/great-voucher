<?php

namespace GreatVoucher\App;

class Order
{
    /**
     * @var \GreatVoucher\App\Order
     */
    private static $instance = null;

    /**
     * @var \GreatVoucher\App\VoucherService
     */
    protected $voucherService;

    public function __construct()
    {
        $this->voucherService = new \GreatVoucher\App\VoucherService();

        add_action('woocommerce_order_status_processing', [$this, 'generateVoucher']);
        add_action('woocommerce_order_status_completed', [$this, 'generateVoucher']);
        add_action('woocommerce_thankyou', [$this, 'thankyou'], 10, 1);
    }

    /**
     * @param mixed $order_id
     * 
     * @return void
     */
    public function generateVoucher($order_id)
    {
        global $wpdb;

        $order = wc_get_order($order_id);

        $status = $order->get_status();

        if (!in_array($status, ['processing', 'completed'])) {
            foreach ($order->get_items() as $item_id => $item) {

                $product = $item->get_product();

                $productId = $product->get_id();
                $title = $product->get_title();
                $price = (int) $product->get_price();
                $quantity = $item->get_quantity();

                for ($i = 0; $i < $quantity; $i++) {
                    $hasVoucherCategory  = has_term('Voucher', 'product_cat', $productId);
                    $voucher = $hasVoucherCategory ? $this->voucherService->generateVoucher($title, $price) : null;
                    $tableName = $wpdb->prefix . 'gv_generated_vouchers';

                    if ($voucher) {
                        $wpdb->insert(
                            $tableName,
                            [
                                'title' => $voucher->title,
                                'code' => $voucher->code,
                                'discount' => $voucher->discount,
                                'item_id' => $item_id,
                            ],
                            [
                                '%s',
                                '%s',
                                '%d',
                                '%d',
                            ],
                        );
                    }
                }
            }
        }
    }

    /**
     * @param mixed $order_id
     * 
     * @return void
     */
    public function thankyou($order_id)
    {
        $order = wc_get_order($order_id);

        $status = $order->get_status();

        in_array($status, ['processing', 'completed']) ? $this->renderVouchers($order) : null;
    }

    /**
     * @param mixed $order
     * 
     * @return void
     */
    public function renderVouchers($order)
    {
        global $wpdb;

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

    /**
     * @return \GreatVoucher\App\Order
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
