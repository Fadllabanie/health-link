<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MedicineCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // رئيسية
            ['name' => 'المضادات الحيوية',        'en' => 'Antibiotics',          'parent' => null],
            ['name' => 'مسكنات الألم',             'en' => 'Analgesics',           'parent' => null],
            ['name' => 'الفيتامينات والمكملات',   'en' => 'Vitamins & Supplements', 'parent' => null],
            ['name' => 'أدوية القلب والضغط',      'en' => 'Cardiovascular',       'parent' => null],
            ['name' => 'أدوية الجهاز الهضمي',     'en' => 'Gastrointestinal',     'parent' => null],
            ['name' => 'أدوية الجهاز التنفسي',    'en' => 'Respiratory',          'parent' => null],
            ['name' => 'أدوية السكري',             'en' => 'Antidiabetics',        'parent' => null],
            ['name' => 'الأدوية النفسية والعصبية', 'en' => 'Neuropsychiatric',     'parent' => null],
            ['name' => 'أدوية الحساسية',           'en' => 'Antiallergics',        'parent' => null],
            ['name' => 'الهرمونات',                'en' => 'Hormones',             'parent' => null],
            // فرعية
            ['name' => 'بيتالاكتام',               'en' => 'Beta-lactams',         'parent' => 'Antibiotics'],
            ['name' => 'ماكرولايد',                'en' => 'Macrolides',           'parent' => 'Antibiotics'],
            ['name' => 'مسكنات الألم غير المخدرة', 'en' => 'Non-opioid Analgesics', 'parent' => 'Analgesics'],
            ['name' => 'المسكنات المخدرة',          'en' => 'Opioid Analgesics',    'parent' => 'Analgesics'],
        ];

        $ids = [];

        foreach ($categories as $item) {
            $slug = Str::slug($item['en']);
            $parentId = $item['parent'] ? ($ids[Str::slug($item['parent'])] ?? null) : null;

            $id = DB::table('medicine_categories')->updateOrInsert(
                ['slug' => $slug],
                [
                    'name' => $item['name'],
                    'slug' => $slug,
                    'parent_id' => $parentId,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]
            );

            $ids[$slug] = DB::table('medicine_categories')->where('slug', $slug)->value('id');
        }
    }
}
