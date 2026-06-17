<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Models\AppSetting;
use App\Services\QrCodeStorageService;
use Flash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class QrCodeSettingsController extends AppBaseController
{
    public function index(QrCodeStorageService $qrCodeStorageService): View
    {
        return view('admin.qrcode_settings', [
            'availableDisks' => array_keys(config('filesystems.disks', [])),
            'settings' => [
                'storage_disk' => $qrCodeStorageService->disk(),
                'storage_path' => $qrCodeStorageService->directory(),
                'storage_visibility' => $qrCodeStorageService->visibility() ?? 'public',
                'public_base_url' => $qrCodeStorageService->publicBaseUrl(),
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $availableDisks = array_keys(config('filesystems.disks', []));

        $validated = $request->validate([
            'storage_disk' => ['required', 'string', 'in:' . implode(',', $availableDisks)],
            'storage_path' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9_\-\/]+$/'],
            'storage_visibility' => ['required', 'string', 'in:public,private'],
            'public_base_url' => ['nullable', 'url', 'max:255'],
        ]);

        $settings = [
            'qrcode.storage_disk' => $validated['storage_disk'],
            'qrcode.storage_path' => trim($validated['storage_path'], '/'),
            'qrcode.storage_visibility' => $validated['storage_visibility'],
            'qrcode.public_base_url' => $validated['public_base_url'] ? rtrim($validated['public_base_url'], '/') : null,
        ];

        foreach ($settings as $key => $value) {
            AppSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Log::info('QR code settings updated', [
            'user_id' => auth()->id(),
            'settings' => $settings,
        ]);

        Flash::success('QR code storage settings updated successfully.');

        return redirect()->route('admin.qrcode-settings.index');
    }
}
