<?php

namespace Database\Seeders\v4_0_2_1;
use Illuminate\Database\Seeder;
use App\ReportColumn;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ReportColumn::truncate();

        $this->call([ReportColumnSeeder::class]);
        $this->command->info('Report column Table Seeded!');

         \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }

}

class ReportColumnSeeder extends Seeder
{
    public function run()
    {

        ReportColumn::create([
            'id' => '1',
            'key' => 'checkbox',
            'label' => 'checkbox',
            'type' => 'users',
            'default' => '1'
        ]);
        ReportColumn::create([
            'id' => '2',
            'key' => 'name',
            'label' => 'name',
            'type' => 'users',
            'default' => '1'
        ]);
        ReportColumn::create([
            'id' => '3',
            'key' => 'email',
            'label' => 'email',
            'type' => 'users',
            'default' => '1'
        ]);
        ReportColumn::create([
            'id' => '4',
            'key' => 'mobile',
            'label' => 'mobile',
            'type' => 'users',
            'default' => '1'
        ]);
        ReportColumn::create([
            'id' => '5',
            'key' => 'country',
            'label' => 'country',
            'type' => 'users',
            'default' => '1'
        ]);
         ReportColumn::create([
            'id' => '6',
            'key' => 'created_at',
            'label' => 'created_at',
            'type' => 'users',
            'default' => '1'
        ]);
          ReportColumn::create([
            'id' => '7',
            'key' => 'active',
            'label' => 'active',
            'type' => 'users',
            'default' => '1'
        ]);
           ReportColumn::create([
            'id' => '8',
            'key' => 'action',
            'label' => 'action',
            'type' => 'users',
            'default' => '1'
        ]);

        ReportColumn::create([
            'id' => '9',
            'key' => 'checkbox',
            'label' => 'checkbox',
            'type' => 'invoices',
            'default' => '1'
        ]);
        ReportColumn::create([
            'id' => '10',
            'key' => 'user_id',
            'label' => 'user_id',
            'type' => 'invoices',
            'default' => '1'
        ]);
        ReportColumn::create([
            'id' => '11',
            'key' => 'email',
            'label' => 'email',
            'type' => 'invoices',
            'default' => '0'
        ]);
        ReportColumn::create([
            'id' => '12',
            'key' => 'mobile',
            'label' => 'mobile',
            'type' => 'invoices',
            'default' => '0'
        ]);
        ReportColumn::create([
            'id' => '13',
            'key' => 'country',
            'label' => 'country',
            'type' => 'invoices',
            'default' => '0'
        ]);
         ReportColumn::create([
            'id' => '14',
            'key' => 'number',
            'label' => 'number',
            'type' => 'invoices',
            'default' => '1'
        ]);
          ReportColumn::create([
            'id' => '15',
            'key' => 'product',
            'label' => 'product',
            'type' => 'invoices',
            'default' => '0'
        ]);
          ReportColumn::create([
            'id' => '16',
            'key' => 'created_at',
            'label' => 'created_at',
            'type' => 'invoices',
            'default' => '1'
        ]);
          ReportColumn::create([
            'id' => '17',
            'key' => 'grand_total',
            'label' => 'grand_total',
            'type' => 'invoices',
            'default' => '1'
        ]);
          ReportColumn::create([
            'id' => '18',
            'key' => 'status',
            'label' => 'status',
            'type' => 'invoices',
            'default' => '1'
        ]);
         ReportColumn::create([
            'id' => '19',
            'key' => 'action',
            'label' => 'action',
            'type' => 'invoices',
            'default' => '1'
        ]);

        ReportColumn::create([
            'id' => '20',
            'key' => 'checkbox',
            'label' => 'checkbox',
            'type' => 'orders',
            'default' => '1'
        ]);
        ReportColumn::create([
            'id' => '21',
            'key' => 'client',
            'label' => 'client',
            'type' => 'orders',
            'default' => '1'
        ]);
        ReportColumn::create([
            'id' => '22',
            'key' => 'email',
            'label' => 'email',
            'type' => 'orders',
            'default' => '0'
        ]);
        ReportColumn::create([
            'id' => '23',
            'key' => 'mobile',
            'label' => 'mobile',
            'type' => 'orders',
            'default' => '0'
        ]);
        ReportColumn::create([
            'id' => '24',
            'key' => 'country',
            'label' => 'country',
            'type' => 'orders',
            'default' => '0'
        ]);
         ReportColumn::create([
            'id' => '25',
            'key' => 'number',
            'label' => 'number',
            'type' => 'orders',
            'default' => '1'
        ]);
          ReportColumn::create([
            'id' => '26',
            'key' => 'status',
            'label' => 'status',
            'type' => 'orders',
            'default' => '0'
        ]);
          ReportColumn::create([
            'id' => '27',
            'key' => 'product_name',
            'label' => 'product_name',
            'type' => 'orders',
            'default' => '1'
        ]);
          ReportColumn::create([
            'id' => '28',
            'key' => 'plan_name',
            'label' => 'plan_name',
            'type' => 'orders',
            'default' => '0'
        ]);
          ReportColumn::create([
            'id' => '29',
            'key' => 'version',
            'label' => 'version',
            'type' => 'orders',
            'default' => '1'
        ]);
        ReportColumn::create([
            'id' => '30',
            'key' => 'agents',
            'label' => 'agents',
            'type' => 'orders',
            'default' => '1'
        ]);
         ReportColumn::create([
            'id' => '31',
            'key' => 'order_status',
            'label' => 'order_status',
            'type' => 'orders',
            'default' => '1'
        ]);
         ReportColumn::create([
            'id' => '32',
            'key' => 'order_date',
            'label' => 'order_date',
            'type' => 'orders',
            'default' => '1'
        ]);
         ReportColumn::create([
            'id' => '33',
            'key' => 'update_ends_at',
            'label' => 'update_ends_at',
            'type' => 'orders',
            'default' => '1'
        ]);
         ReportColumn::create([
            'id' => '34',
            'key' => 'action',
            'label' => 'action',
            'type' => 'orders',
            'default' => '1'
        ]);


        ReportColumn::create([
            'id' => '35',
            'key' => 'checkbox',
            'label' => 'checkbox',
            'type' => 'tenats',
            'default' => '1'
        ]);
        ReportColumn::create([
            'id' => '36',
            'key' => 'Order',
            'label' => 'Order',
            'type' => 'tenats',
            'default' => '1'
        ]);
        ReportColumn::create([
            'id' => '37',
            'key' => 'name',
            'label' => 'name',
            'type' => 'tenats',
            'default' => '0'
        ]);
        ReportColumn::create([
            'id' => '38',
            'key' => 'email',
            'label' => 'email',
            'type' => 'tenats',
            'default' => '0'
        ]);
        ReportColumn::create([
            'id' => '39',
            'key' => 'mobile',
            'label' => 'mobile',
            'type' => 'tenats',
            'default' => '0'
        ]);
        ReportColumn::create([
            'id' => '40',
            'key' => 'country',
            'label' => 'country',
            'type' => 'tenats',
            'default' => '0'
        ]);
         ReportColumn::create([
            'id' => '41',
            'key' => 'Expiry day',
            'label' => 'Expiry day',
            'type' => 'tenats',
            'default' => '0'
        ]);
         ReportColumn::create([
            'id' => '42',
            'key' => 'Deletion day',
            'label' => 'Deletion day',
            'type' => 'tenats',
            'default' => '1'
        ]);
         ReportColumn::create([
            'id' => '43',
            'key' => 'plan',
            'label' => 'plan',
            'type' => 'tenats',
            'default' => '0'
        ]);
          ReportColumn::create([
            'id' => '44',
            'key' => 'tenants',
            'label' => 'tenants',
            'type' => 'tenats',
            'default' => '1'
        ]);
         ReportColumn::create([
            'id' => '45',
            'key' => 'domain',
            'label' => 'domain',
            'type' => 'tenats',
            'default' => '1'
        ]);
           ReportColumn::create([
            'id' => '46',
            'key' => 'db_name',
            'label' => 'db_name',
            'type' => 'tenats',
            'default' => '1'
        ]);
        ReportColumn::create([
            'id' => '47',
            'key' => 'db_username',
            'label' => 'db_username',
            'type' => 'tenats',
            'default' => '1'
        ]);
         ReportColumn::create([
            'id' => '48',
            'key' => 'action',
            'label' => 'action',
            'type' => 'tenats',
            'default' => '1'
        ]);
    }
}

