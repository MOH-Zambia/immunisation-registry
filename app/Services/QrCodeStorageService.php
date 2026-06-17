<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\AppSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;
use QRCode;

class QrCodeStorageService
{
    private const SETTING_KEYS = [
        'qrcode.storage_disk',
        'qrcode.storage_path',
        'qrcode.storage_visibility',
        'qrcode.public_base_url',
    ];

    private ?array $runtimeSettings = null;

    public function disk(): string
    {
        return (string) $this->settingValue('qrcode.storage_disk', config('qrcode.disk', config('filesystems.default', 'local')));
    }

    public function directory(): string
    {
        return trim((string) $this->settingValue('qrcode.storage_path', config('qrcode.path', 'qrcodes')), '/');
    }

    public function visibility(): ?string
    {
        $visibility = trim((string) $this->settingValue('qrcode.storage_visibility', config('qrcode.visibility', '')));

        return $visibility !== '' ? $visibility : null;
    }

    public function publicBaseUrl(): ?string
    {
        $baseUrl = trim((string) $this->settingValue('qrcode.public_base_url', config('qrcode.public_base_url', '')));

        return $baseUrl !== '' ? rtrim($baseUrl, '/') : null;
    }

    public function relativePathForUuid(string $certificateUuid): string
    {
        $filename = $certificateUuid . '.png';
        $directory = $this->directory();

        return $directory === '' ? $filename : $directory . '/' . $filename;
    }

    public function storeQrCode(string $certificateUuid, string $contents): string
    {
        $path = $this->relativePathForUuid($certificateUuid);
        $disk = Storage::disk($this->disk());
        $visibility = $this->visibility();

        $disk->put($path, $contents);

        if ($visibility !== null) {
            rescue(static function () use ($disk, $path, $visibility) {
                $disk->setVisibility($path, $visibility);
            }, null, false);
        }

        return $path;
    }

    public function read(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if ($this->isLegacyPublicPath($path)) {
            $absolutePath = public_path(ltrim($path, '/'));

            return file_exists($absolutePath) ? file_get_contents($absolutePath) : null;
        }

        $disk = Storage::disk($this->disk());

        return $disk->exists($path) ? $disk->get($path) : null;
    }

    public function ensureQrCodeExists(Certificate $certificate): ?string
    {
        $path = $certificate->qr_code_path ?: $this->relativePathForUuid($certificate->certificate_uuid);
        $contents = $this->read($path);

        if ($contents !== null) {
            return $path;
        }

        $contents = $this->regeneratedContents($certificate);
        if ($contents === null) {
            return null;
        }

        $storedPath = $this->storeQrCode($certificate->certificate_uuid, $contents);
        $updates = [];

        if (empty($certificate->qr_code)) {
            $updates['qr_code'] = $contents;
        }

        if ($certificate->qr_code_path !== $storedPath) {
            $updates['qr_code_path'] = $storedPath;
        }

        if ($certificate->exists && !empty($updates)) {
            $certificate->forceFill($updates)->saveQuietly();
        }

        foreach ($updates as $key => $value) {
            $certificate->setAttribute($key, $value);
        }

        return $storedPath;
    }

    public function isQrCodeMissingForCertificate(Certificate $certificate): bool
    {
        $path = $certificate->qr_code_path ?: $this->relativePathForUuid($certificate->certificate_uuid);

        return $this->read($path) === null;
    }

    public function contentsForCertificate(Certificate $certificate): ?string
    {
        if (!empty($certificate->qr_code)) {
            $this->ensureQrCodeExists($certificate);

            return $certificate->qr_code;
        }

        $path = $this->ensureQrCodeExists($certificate);

        return $path !== null ? $this->read($path) : null;
    }

    public function url(?string $path): ?string
    {
        $resolvedUrl = null;

        if (empty($path)) {
            return $resolvedUrl;
        }

        if ($this->isLegacyPublicPath($path)) {
            $resolvedUrl = url(ltrim($path, '/'));
        } else {
            $baseUrl = $this->publicBaseUrl();
            if ($baseUrl !== null) {
                $resolvedUrl = $baseUrl . '/' . ltrim($path, '/');
            } else {
                $resolvedUrl = rescue(static function () use ($path) {
                    return Storage::disk(config('qrcode.disk', config('filesystems.default', 'local')))->url($path);
                }, null, false);
            }
        }

        return $resolvedUrl;
    }

    public function urlForCertificate(Certificate $certificate): ?string
    {
        $path = $this->ensureQrCodeExists($certificate);

        return $path !== null ? $this->url($path) : null;
    }

    private function isLegacyPublicPath(string $path): bool
    {
        return Str::startsWith(ltrim($path, '/'), 'img/qrcodes/');
    }

    private function regeneratedContents(Certificate $certificate): ?string
    {
        if (!empty($certificate->qr_code)) {
            return $certificate->qr_code;
        }

        if (empty($certificate->certificate_url)) {
            return null;
        }

        return $this->generateQrCodeContents($certificate->certificate_url);
    }

    private function generateQrCodeContents(string $certificateUrl): ?string
    {
        $temporaryQrCode = tempnam(sys_get_temp_dir(), 'qrc');

        if ($temporaryQrCode === false) {
            return null;
        }

        QRCode::url($certificateUrl)
            ->setSize(6)
            ->setOutfile($temporaryQrCode)
            ->png();

        if (!file_exists($temporaryQrCode)) {
            @unlink($temporaryQrCode);

            return null;
        }

        $contents = file_get_contents($temporaryQrCode);
        @unlink($temporaryQrCode);

        return $contents !== false ? $contents : null;
    }

    private function settingValue(string $key, $fallback)
    {
        $settings = $this->runtimeSettings();

        if (array_key_exists($key, $settings) && $settings[$key] !== null && $settings[$key] !== '') {
            return $settings[$key];
        }

        return $fallback;
    }

    private function runtimeSettings(): array
    {
        if ($this->runtimeSettings !== null) {
            return $this->runtimeSettings;
        }

        $this->runtimeSettings = [];

        try {
            if (!Schema::hasTable('app_settings')) {
                return $this->runtimeSettings;
            }

            $this->runtimeSettings = AppSetting::query()
                ->whereIn('key', self::SETTING_KEYS)
                ->pluck('value', 'key')
                ->all();
        } catch (Throwable $exception) {
            $this->runtimeSettings = [];
        }

        return $this->runtimeSettings;
    }
}

