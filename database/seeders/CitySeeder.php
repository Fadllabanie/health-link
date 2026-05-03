<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $countries = DB::table('countries')->pluck('id', 'code');

        $cities = [
            // السعودية
            ['country_code' => 'SA', 'name' => 'الرياض',    'latitude' => 24.68773, 'longitude' => 46.72185],
            ['country_code' => 'SA', 'name' => 'جدة',       'latitude' => 21.54238, 'longitude' => 39.19797],
            ['country_code' => 'SA', 'name' => 'مكة المكرمة', 'latitude' => 21.38910, 'longitude' => 39.85791],
            ['country_code' => 'SA', 'name' => 'المدينة المنورة', 'latitude' => 24.52498, 'longitude' => 39.57001],
            ['country_code' => 'SA', 'name' => 'الدمام',    'latitude' => 26.39239, 'longitude' => 49.97760],
            // الإمارات
            ['country_code' => 'AE', 'name' => 'دبي',       'latitude' => 25.20485, 'longitude' => 55.27078],
            ['country_code' => 'AE', 'name' => 'أبوظبي',    'latitude' => 24.46667, 'longitude' => 54.36667],
            ['country_code' => 'AE', 'name' => 'الشارقة',   'latitude' => 25.33737, 'longitude' => 55.41206],
            // مصر
            ['country_code' => 'EG', 'name' => 'القاهرة',   'latitude' => 30.06263, 'longitude' => 31.24967],
            ['country_code' => 'EG', 'name' => 'الإسكندرية', 'latitude' => 31.19730, 'longitude' => 29.89560],
            ['country_code' => 'EG', 'name' => 'الجيزة',    'latitude' => 30.00000, 'longitude' => 31.21670],
            // الأردن
            ['country_code' => 'JO', 'name' => 'عمّان',     'latitude' => 31.95522, 'longitude' => 35.94503],
            ['country_code' => 'JO', 'name' => 'إربد',      'latitude' => 32.55556, 'longitude' => 35.85000],
            // الكويت
            ['country_code' => 'KW', 'name' => 'مدينة الكويت', 'latitude' => 29.36972, 'longitude' => 47.97833],
            ['country_code' => 'KW', 'name' => 'حولي',      'latitude' => 29.33388, 'longitude' => 48.02500],
            // قطر
            ['country_code' => 'QA', 'name' => 'الدوحة',    'latitude' => 25.28545, 'longitude' => 51.53096],
        ];

        foreach ($cities as $city) {
            $countryId = $countries[$city['country_code']] ?? null;
            if (! $countryId) {
                continue;
            }

            DB::table('cities')->updateOrInsert(
                ['name' => $city['name'], 'country_id' => $countryId],
                [
                    'country_id' => $countryId,
                    'name' => $city['name'],
                    'latitude' => $city['latitude'],
                    'longitude' => $city['longitude'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
