<?php

namespace Database\Seeders\Models;

//use App\Drivers\AmmiraglioDriver;
//use App\Drivers\Magento1Driver;
use App\Drivers\Magento2Driver;
use App\Services\ImportOrders;
use DNAFactory\Database\Factories\AutomationFactory;
use Illuminate\Database\Seeder;

class AutomationSeeder extends Seeder
{
//    protected function createImportOrdersLiberotechM1Automation(): void
//    {
//        $importOrdersAutomation = AutomationFactory::new()->make();
//        $importOrdersAutomation->name = 'Import Orders Liberotech M1';
//        $importOrdersAutomation->alias = 'import-orders-liberotech';
//        $importOrdersAutomation->service = ImportOrders::class;
//        $importOrdersAutomation->driver = Magento1Driver::class;
//        $importOrdersAutomation->cron = '20 * * * *';
//        $importOrdersAutomation->enabled = false;
//
//        $configuration = [
//            'base_url' => 'https://www.liberotech.it/',
//            'store_id' => '',
//            'username' => '',
//            'password' => '',
//            'token' => 'dao8tgxdd7q7j71hov0hv9ntgfscurx8',
//            'page_size' => 500,
//            'starting_id' => 1,
//            'include_sourced_orders' => '0',
//            'load_from_last_imported_id' => '0',
//            'chunk_size' => 50,
//            'wait_time_milliseconds' => 200,
//        ];
//
//        $importOrdersAutomation->configs = $configuration;
//        $importOrdersAutomation->cron = '0 0,8,12,16,20 * * *';
//        $importOrdersAutomation->save();
//    }

    protected function createImportOrdersGruppoedicoAutomation(): void
    {
        $importOrdersAutomation = AutomationFactory::new()->make();
        $importOrdersAutomation->name = 'Import Orders Liberotech M2';
        $importOrdersAutomation->alias = 'import-orders-liberotech';
        $importOrdersAutomation->service = ImportOrders::class;
        $importOrdersAutomation->driver = Magento2Driver::class;
        $importOrdersAutomation->cron = '0 0,8,12,16,20 * * *';
        $importOrdersAutomation->enabled = true;

        $configuration = [
            'base_url' => 'https://www.liberotech.it/',
            'store_code' => '',
            'username' => '',
            'password' => '',
            'token' => 'dao8tgxdd7q7j71hov0hv9ntgfscurx8',
            'page_size' => 50,
            'from_date' => '',
            'load_from_last_order_date' => '1',
            'chunk_size' => 50,
            'wait_time_milliseconds' => 200,
        ];

        $importOrdersAutomation->configs = $configuration;
        $importOrdersAutomation->save();
    }

//    protected function createImportOrdersAmmiraglioAutomation(): void
//    {
//        $importOrdersAutomation = AutomationFactory::new()->make();
//        $importOrdersAutomation->name = 'Import Orders Ammiraglio';
//        $importOrdersAutomation->alias = 'import-orders-ammiraglio';
//        $importOrdersAutomation->service = ImportOrders::class;
//        $importOrdersAutomation->driver = AmmiraglioDriver::class;
//        $importOrdersAutomation->cron = '0 0,8,12,16,20 * * *';
//        $importOrdersAutomation->enabled = true;
//
//        $configuration = [
//            'base_url' => 'https://ammiragl.io/',
//            'store_code' => '',
//            'token' => '16|TMJQy7k9RMKbIGEkGMsgOsylJW1tyuPK7hbUGbuke21caca6',
//            'limit' => 50,
//            'from_date' => '',
//            'load_from_last_order_date' => '0',
//            'chunk_size' => 50,
//            'wait_time_milliseconds' => 200,
//        ];
//
//        $importOrdersAutomation->configs = $configuration;
//        $importOrdersAutomation->save();
//    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        $this->createImportOrdersLiberotechM1Automation();
        $this->createImportOrdersGruppoedicoAutomation();
//        $this->createImportOrdersAmmiraglioAutomation();
    }
}
