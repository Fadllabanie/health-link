<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\QrCode;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QrCodeService
{
    public function generateForPatient(Patient $patient): QrCode
    {
        $token = Crypt::encryptString($patient->id.'|'.uniqid('', true));

        $imagePath = $this->saveImage($token);

        $qr = QrCode::create([
            'code' => $token,
            'qrable_type' => Patient::class,
            'qrable_id' => $patient->id,
            'image_path' => $imagePath,
            'is_active' => true,
        ]);

        $patient->update(['qr_code_id' => $qr->id]);

        return $qr;
    }

    public function regenerate(QrCode $qr): QrCode
    {
        if ($qr->image_path && Storage::disk('public')->exists($qr->image_path)) {
            Storage::disk('public')->delete($qr->image_path);
        }

        $token = Crypt::encryptString($qr->qrable_id.'|'.uniqid('', true));
        $imagePath = $this->saveImage($token);

        $qr->update([
            'code' => $token,
            'image_path' => $imagePath,
            'is_active' => true,
            'scan_count' => 0,
            'last_scanned_at' => null,
        ]);

        return $qr->fresh();
    }

    /**
     * Decrypt code and return the owning model, or throw if invalid/inactive.
     *
     * @throws \RuntimeException
     */
    public function verifyAndResolve(string $code): Patient
    {
        $qr = QrCode::where('code', $code)->where('is_active', true)->firstOrFail();

        if ($qr->isExpired()) {
            throw new \RuntimeException('QR code has expired.');
        }

        $qr->incrementScan();

        return $qr->qrable;
    }

    private function saveImage(string $token): string
    {
        $url = url('/qr/'.$token);
        $png = QrCodeGenerator::format('png')->size(300)->generate($url);

        $path = 'qr-codes/'.sha1($token).'.png';
        Storage::disk('public')->put($path, $png);

        return $path;
    }
}
