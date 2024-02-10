<?php

namespace GreatVoucher\App;

class VoucherService
{
    /**
     * @var \GreatVoucher\App\VoucherService
     */
    private static $instance = null;

    /**
     * @var \GuzzleHttp\Client
     */
    public $client;

    public function __construct()
    {
        global $wpdb;

        $tableName = $wpdb->prefix . 'gv_configurations';

        $result = $wpdb->get_results("SELECT * FROM $tableName WHERE config_key = 'access_key'");
        $accessKey = isset($result[0]) ? $result[0]->config_value : null;

        $result = $wpdb->get_results("SELECT * FROM $tableName WHERE config_key = 'secret_key'");
        $secretKey = isset($result[0]) ? $result[0]->config_value : null;

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.rakhi-dev.tech',
            'headers' => [
                'X-Great-Voucher-Service-Access-Key' => $accessKey,
                'X-Great-Voucher-Service-Secret-Key' => $secretKey,
            ]
        ]);
    }

    /**
     * @return mixed
     */
    public function generateVoucher($title, $discount)
    {
        try {
            $response = $this->client->post('/vouchers', [
                'json' => [
                    'title' => $title,
                    'discount' => $discount,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents());

            return $data->voucher;
        } catch (\GuzzleHttp\Exception\RequestException $exception) {
            return null;
        }
    }

    /**
     * @return \GreatVoucher\App\VoucherService
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
