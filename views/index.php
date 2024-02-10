<script src="https://cdn.tailwindcss.com"></script>

<?php

global $wpdb;

$tableName = $wpdb->prefix . 'gv_configurations';

$result = $wpdb->get_results("SELECT * FROM $tableName WHERE config_key = 'access_key' LIMIT 1");
$accessKey = isset($result[0]) ? $result[0]->config_value : null;

$result = $wpdb->get_results("SELECT * FROM $tableName WHERE config_key = 'secret_key' LIMIT 1");
$secretKey = isset($result[0]) ? $result[0]->config_value : null;

?>

<div class="px-3">
    <h1 class="text-2xl font-semibold mt-3 mb-5">Great Voucher</h1>
    <div class="w-full bg-white rounded-sm shadow-sm p-4">
        <h1 class="text-lg font-medium mb-5">Configuration</h1>
        <?php if (isset($_SESSION['gv_flash_message']['success']['value'])) { ?>
            <div class="w-[500px] bg-green-100 text-green-700 rounded-md px-4 py-2 mb-5">
                <span><?php echo $_SESSION['gv_flash_message']['success']['value'] ?></span>
                <?php $_SESSION['gv_flash_message']['success']['called'] = true ?>
            </div>
        <?php } ?>
        <form method="post" class="w-[500px]" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="save_great_voucher_configuration">
            <div class="flex items-center gap-3 mb-5">
                <label class="block w-[100px] font-medium" for="access-key">Access Key</label>
                <input type="text" id="access-key" name="access_key" value="<?php echo $accessKey ?>" class="w-full">
            </div>
            <div class="flex items-center gap-3 mb-5">
                <label class="block w-[100px] font-medium" for="secret-key">Secret Key</label>
                <input type="text" id="secret-key" name="secret_key" value="<?php echo $secretKey ?>" class="w-full">
            </div>
            <div class="flex justify-end mt-3">
                <button type="submit" class="bg-blue-500 text-xs text-white font-medium tracking-wider rounded-sm px-5 py-2">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>