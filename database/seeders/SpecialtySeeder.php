<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            ['name' => 'طب القلب والأوعية الدموية', 'en' => 'Cardiology'],
            ['name' => 'طب الأطفال',                'en' => 'Pediatrics'],
            ['name' => 'طب الأمراض الجلدية',         'en' => 'Dermatology'],
            ['name' => 'طب العيون',                  'en' => 'Ophthalmology'],
            ['name' => 'طب الأعصاب',                 'en' => 'Neurology'],
            ['name' => 'جراحة العظام والمفاصل',       'en' => 'Orthopedics'],
            ['name' => 'طب الأمراض الباطنية',         'en' => 'Internal Medicine'],
            ['name' => 'طب النساء والتوليد',          'en' => 'Obstetrics & Gynecology'],
            ['name' => 'جراحة عامة',                 'en' => 'General Surgery'],
            ['name' => 'طب الأنف والأذن والحنجرة',   'en' => 'ENT'],
            ['name' => 'طب الأسنان',                 'en' => 'Dentistry'],
            ['name' => 'الطب النفسي',                'en' => 'Psychiatry'],
            ['name' => 'طب الطوارئ والإسعاف',        'en' => 'Emergency Medicine'],
            ['name' => 'طب الغدد الصماء',            'en' => 'Endocrinology'],
            ['name' => 'طب الجهاز الهضمي',           'en' => 'Gastroenterology'],
            ['name' => 'أمراض الدم',                 'en' => 'Hematology'],
            ['name' => 'طب الأورام',                 'en' => 'Oncology'],
            ['name' => 'طب الكلى',                   'en' => 'Nephrology'],
            ['name' => 'الطب الطبيعي وإعادة التأهيل', 'en' => 'Physical Medicine & Rehabilitation'],
            ['name' => 'طب الأشعة والتصوير الطبي',   'en' => 'Radiology'],
        ];

        foreach ($specialties as $item) {
            $slug = Str::slug($item['en']);
            DB::table('specialties')->updateOrInsert(
                ['slug' => $slug],
                [
                    'name' => $item['name'],
                    'slug' => $slug,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]
            );
        }
    }
}
