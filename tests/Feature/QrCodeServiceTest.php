<?php

use App\Models\Patient;
use App\Models\QrCode;
use App\Services\QrCodeService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

test('generateForPatient creates QR code and links it to patient', function () {
    $patient = Patient::factory()->create(['qr_code_id' => null]);

    $service = app(QrCodeService::class);
    $qr = $service->generateForPatient($patient);

    expect($qr)->toBeInstanceOf(QrCode::class)
        ->and($qr->qrable_type)->toBe(Patient::class)
        ->and($qr->qrable_id)->toBe($patient->id)
        ->and($qr->is_active)->toBeTrue()
        ->and($qr->code)->not->toBeEmpty();

    expect($patient->fresh()->qr_code_id)->toBe($qr->id);

    Storage::disk('public')->assertExists($qr->image_path);
});

test('verifyAndResolve returns patient for valid code', function () {
    $patient = Patient::factory()->create(['qr_code_id' => null]);

    $service = app(QrCodeService::class);
    $qr = $service->generateForPatient($patient);

    $resolved = $service->verifyAndResolve($qr->code);

    expect($resolved->id)->toBe($patient->id);
    expect($qr->fresh()->scan_count)->toBe(1);
});

test('verifyAndResolve throws for unknown code', function () {
    $service = app(QrCodeService::class);

    expect(fn () => $service->verifyAndResolve('invalid-token'))
        ->toThrow(ModelNotFoundException::class);
});

test('regenerate issues new code and removes old image', function () {
    $patient = Patient::factory()->create(['qr_code_id' => null]);

    $service = app(QrCodeService::class);
    $qr = $service->generateForPatient($patient);

    $oldCode = $qr->code;
    $oldPath = $qr->image_path;

    $refreshed = $service->regenerate($qr);

    expect($refreshed->code)->not->toBe($oldCode);
    expect($refreshed->scan_count)->toBe(0);
    Storage::disk('public')->assertMissing($oldPath);
    Storage::disk('public')->assertExists($refreshed->image_path);
});
