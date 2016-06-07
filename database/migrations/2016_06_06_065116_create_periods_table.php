<?php

use App\Model\Payment\Period;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('days');
            $table->timestamps();
        });
        $periods = [
            ['name' => 'One Month', 'days' => 30],
            ['name' => 'Three Month', 'days' => 90],
            ['name' => 'Six Month', 'days' => 180],
            ['name' => 'One Year', 'days' => 365],
            ['name' => 'Two Year', 'days' => 2 * 365],
            ['name' => 'Three Year', 'days' => 3 * 365],
            ['name' => 'Four Year', 'days' => 4 * 365],
            ['name' => 'Five Year', 'days' => 5 * 365],
            ['name' => 'Six Year', 'days' => 6 * 365],
            ['name' => 'Seven Year', 'days' => 7 * 365],
            ['name' => 'Eight Year', 'days' => 8 * 365],
            ['name' => 'Nine Year', 'days' => 9 * 365],
            ['name' => 'Ten Year', 'days' => 10 * 365],
        ];
        $period = new Period();
        foreach ($periods as $per) {
            $period->create([
                'name' => $per['name'],
                'days' => $per['days'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('periods');
    }
}
