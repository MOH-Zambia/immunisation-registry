<?php

namespace App\Services;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CertificateStorageService
{
    private const SETTING_KEYS = [
        'certificate.storage_disk',
        'certificate.storage_path',
        'certificate.storage_visibility',
    ];

    private ?array $runtimeSettings = null;

    public function disk(): string
    {
        return (string) $this->settingValue('certificate.storage_disk', config('certificate_storage.disk', config('filesystems.default', 'local')));
    }

    public function directory(): string
    {
        return trim((string) $this->settingValue('certificate.storage_path', config('certificate_storage.path', 'certificates')), '/');
    }

    public function visibility(): ?string
    {
        $visibility = trim((string) $this->settingValue('certificate.storage_visibility', config('certificate_storage.visibility', '')));

        return $visibility !== '' ? $visibility : null;
    }

    public function relativePathForUuid(string $certificateUuid): string
    {
        $filename = $certificateUuid . '.pdf';
        $directory = $this->directory();

        return $directory === '' ? $filename : $directory . '/' . $filename;
    }

    public function storePdf(string $certificateUuid, string $contents): string
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

    public function downloadResponse(string $path)
    {
        $disk = Storage::disk($this->disk());
        $filename = basename($path);

        return response()->streamDownload(function () use ($disk, $path) {
            echo $disk->get($path);
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
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
