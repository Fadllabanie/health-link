<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'المملكة العربية السعودية', 'code' => 'SA', 'code3' => 'SAU', 'phone_code' => '+966', 'currency_code' => 'SAR', 'is_active' => true],
            ['name' => 'الإمارات العربية المتحدة',  'code' => 'AE', 'code3' => 'ARE', 'phone_code' => '+971', 'currency_code' => 'AED', 'is_active' => true],
            ['name' => 'جمهورية مصر العربية',        'code' => 'EG', 'code3' => 'EGY', 'phone_code' => '+20',  'currency_code' => 'EGP', 'is_active' => true],
            ['name' => 'المملكة الأردنية الهاشمية',  'code' => 'JO', 'code3' => 'JOR', 'phone_code' => '+962', 'currency_code' => 'JOD', 'is_active' => true],
            ['name' => 'دولة الكويت',                'code' => 'KW', 'code3' => 'KWT', 'phone_code' => '+965', 'currency_code' => 'KWD', 'is_active' => true],
            ['name' => 'المملكة المغربية',            'code' => 'MA', 'code3' => 'MAR', 'phone_code' => '+212', 'currency_code' => 'MAD', 'is_active' => true],
            ['name' => 'الجمهورية التونسية',          'code' => 'TN', 'code3' => 'TUN', 'phone_code' => '+216', 'currency_code' => 'TND', 'is_active' => true],
            ['name' => 'الجمهورية العراقية',          'code' => 'IQ', 'code3' => 'IRQ', 'phone_code' => '+964', 'currency_code' => 'IQD', 'is_active' => true],
            ['name' => 'الجمهورية اللبنانية',         'code' => 'LB', 'code3' => 'LBN', 'phone_code' => '+961', 'currency_code' => 'LBP', 'is_active' => true],
            ['name' => 'دولة قطر',                   'code' => 'QA', 'code3' => 'QAT', 'phone_code' => '+974', 'currency_code' => 'QAR', 'is_active' => true],
        ];

        foreach ($countries as $country) {
            DB::table('countries')->updateOrInsert(
                ['code' => $country['code']],
                array_merge($country, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
