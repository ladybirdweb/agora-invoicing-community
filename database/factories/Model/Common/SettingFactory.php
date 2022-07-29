<?php

namespace Database\Factories\Model\Common;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Model\Common\Setting;

class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     * 
     * 
     */

    protected $model = Setting::class;

    public function definition()
    {
        return [
         'version' => 'V1.5.2',

        ];
    }
}
