<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Mushmero\Lapdash\Models\User;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createClockInTimeLimit();
    }

    public function createClockInTimeLimit()
    {
        $is_exist = AppSetting::where('parameter','clock_in_time_limit')->first();
        if(!$is_exist){
            AppSetting::create([
                'parameter' => 'clock_in_time_limit',
                'value' => '09:00:00',
                'user_id' => User::role('Superadmin')->first()->id,
            ]);
        }else{
            $this->command->info('Parameter exist! Skipping');
        }
    }
}
