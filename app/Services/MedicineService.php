<?php

namespace App\Services;

use App\Models\Medicine;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MedicineService
{
    public function store(array $data): Medicine
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $data['image'] = $data['image']->store('medicines', 'public');
            }

            return Medicine::create($data);
        });
    }

    public function update(Medicine $medicine, array $data): Medicine
    {
        return DB::transaction(function () use ($medicine, $data) {
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                if ($medicine->image) {
                    Storage::disk('public')->delete($medicine->image);
                }
                $data['image'] = $data['image']->store('medicines', 'public');
            }

            $medicine->update($data);

            return $medicine->fresh();
        });
    }

    public function destroy(Medicine $medicine): void
    {
        DB::transaction(function () use ($medicine) {
            if ($medicine->image) {
                Storage::disk('public')->delete($medicine->image);
            }

            $medicine->delete();
        });
    }
}
