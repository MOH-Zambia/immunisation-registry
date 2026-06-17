<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Models\AppSetting;
use App\Services\CertificateStorageService;
use App\Services\QrCodeStorageService;
use Flash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class QrCodeSettingsController extends AppBaseController
{
    public function index(QrCodeStorageService $qrCodeStorageService, CertificateStorageService $certificateStorageService): View
    {
        return view('admin.qrcode_settings', [
            'availableDisks' => array_keys(config('filesystems.disks', [])),
            'settings' => [
                'qrcode_storage_disk' => $qrCodeStorageService->disk(),
                'qrcode_storage_path' => $qrCodeStorageService->directory(),
                'qrcode_storage_visibility' => $qrCodeStorageService->visibility() ?? 'public',
                'qrcode_public_base_url' => $qrCodeStorageService->publicBaseUrl(),
                'certificate_storage_disk' => $certificateStorageService->disk(),
                'certificate_storage_path' => $certificateStorageService->directory(),
                'certificate_storage_visibility' => $certificateStorageService->visibility() ?? 'private',
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $availableDisks = array_keys(config('filesystems.disks', []));

        $validated = $request->validate([
            'qrcode_storage_disk' => ['required', 'string', 'in:' . implode(',', $availableDisks)],
            'qrcode_storage_path' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9_\-\/]+$/'],
            'qrcode_storage_visibility' => ['required', 'string', 'in:public,private'],
            'qrcode_public_base_url' => ['nullable', 'url', 'max:255'],
            'certificate_storage_disk' => ['required', 'string', 'in:' . implode(',', $availableDisks)],
            'certificate_storage_path' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9_\-\/]+$/'],
            'certificate_storage_visibility' => ['required', 'string', 'in:public,private'],
        ]);

        $settings = [
            'qrcode.storage_disk' => $validated['qrcode_storage_disk'],
            'qrcode.storage_path' => trim($validated['qrcode_storage_path'], '/'),
            'qrcode.storage_visibility' => $validated['qrcode_storage_visibility'],
            'qrcode.public_base_url' => $validated['qrcode_public_base_url'] ? rtrim($validated['qrcode_public_base_url'], '/') : null,
            'certificate.storage_disk' => $validated['certificate_storage_disk'],
            'certificate.storage_path' => trim($validated['certificate_storage_path'], '/'),
            'certificate.storage_visibility' => $validated['certificate_storage_visibility'],
        ];

        foreach ($settings as $key => $value) {
            AppSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Log::info('QR code settings updated', [
            'user_id' => auth()->id(),
            'settings' => $settings,
        ]);

        Flash::success('Storage settings updated successfully.');

        return redirect()->route('admin.qrcode-settings.index');
    }
}
