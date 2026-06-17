<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Models\Certificate;
use App\Models\Vaccination;
use App\Models\Client;

class SystemToolsController extends AppBaseController
{
    private const EXECUTION_TIME_SUFFIX = ' seconds';

    /**
     * Display system tools interface
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get statistics for the tools page
        $stats = [
            'vaccinations_without_certificates' => Vaccination::whereNull('certificate_id')->count(),
            'certificates_without_trusted_code' => Certificate::whereNull('trusted_vaccine_code')->count(),
            'total_certificates' => Certificate::count(),
            'total_vaccinations' => Vaccination::count(),
            'total_clients' => Client::count(),
        ];

        return view('admin.system_tools', compact('stats'));
    }

    /**
     * Generate vaccination certificates
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateCertificates()
    {
        try {
            $startTime = microtime(true);

            Log::info('Admin initiated certificate generation', [
                'user_id' => auth()->id(),
                'timestamp' => now()->toDateTimeString()
            ]);

            // Run the command
            Artisan::call('command:GenerateVaccinationCertificates');
            $output = Artisan::output();

            $executionTime = round((microtime(true) - $startTime), 2);

            Log::info('Certificate generation completed', [
                'user_id' => auth()->id(),
                'execution_time' => $executionTime,
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Certificate generation completed successfully',
                'output' => $output,
                'execution_time' => $executionTime . self::EXECUTION_TIME_SUFFIX
            ]);
        } catch (\Exception $e) {
            Log::error('Certificate generation failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error generating certificates: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Regenerate missing QR codes
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function regenerateMissingQrCodes()
    {
        try {
            $startTime = microtime(true);

            Log::info('Admin initiated missing QR code regeneration', [
                'user_id' => auth()->id(),
                'timestamp' => now()->toDateTimeString()
            ]);

            Artisan::call('command:RegenerateMissingQrCodes');
            $output = Artisan::output();
            $executionTime = round((microtime(true) - $startTime), 2);

            Log::info('Missing QR code regeneration completed', [
                'user_id' => auth()->id(),
                'execution_time' => $executionTime,
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Missing QR code regeneration completed successfully',
                'output' => $output,
                'execution_time' => $executionTime . self::EXECUTION_TIME_SUFFIX
            ]);
        } catch (\Exception $e) {
            Log::error('Missing QR code regeneration failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error regenerating missing QR codes: ' . $e->getMessage(),
                'output' => Artisan::output()
            ], 500);
        }
    }

    /**
     * Import DHIS2 data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importDHIS2Data(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        try {
            $startTime = microtime(true);

            Log::info('Admin initiated DHIS2 data import', [
                'user_id' => auth()->id(),
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'timestamp' => now()->toDateTimeString()
            ]);

            // Run the command
            Artisan::call('command:ImportDHIS2Data', [
                'startDate' => $validated['start_date'],
                'endDate' => $validated['end_date']
            ]);
            $output = Artisan::output();

            $executionTime = round((microtime(true) - $startTime), 2);

            Log::info('DHIS2 data import completed', [
                'user_id' => auth()->id(),
                'execution_time' => $executionTime,
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'DHIS2 data import completed successfully',
                'output' => $output,
                'execution_time' => $executionTime . self::EXECUTION_TIME_SUFFIX
            ]);
        } catch (\Exception $e) {
            Log::error('DHIS2 data import failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error importing DHIS2 data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export data to Trusted Vaccine Portal
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportToTrustedPortal()
    {
        try {
            $startTime = microtime(true);

            Log::info('Admin initiated export to Trusted Vaccine Portal', [
                'user_id' => auth()->id(),
                'timestamp' => now()->toDateTimeString()
            ]);

            // Run the command
            Artisan::call('command:ExportDataToTrustedVaccinePortal');
            $output = Artisan::output();

            $executionTime = round((microtime(true) - $startTime), 2);

            Log::info('Export to Trusted Vaccine Portal completed', [
                'user_id' => auth()->id(),
                'execution_time' => $executionTime,
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Export to Trusted Vaccine Portal completed successfully',
                'output' => $output,
                'execution_time' => $executionTime . self::EXECUTION_TIME_SUFFIX
            ]);
        } catch (\Exception $e) {
            Log::error('Export to Trusted Vaccine Portal failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error exporting to Trusted Vaccine Portal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear application cache
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearCache()
    {
        try {
            Log::info('Admin initiated cache clear', [
                'user_id' => auth()->id(),
                'timestamp' => now()->toDateTimeString()
            ]);

            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            return response()->json([
                'success' => true,
                'message' => 'All caches cleared successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Cache clear failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error clearing cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Optimize application
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function optimizeApp()
    {
        try {
            Log::info('Admin initiated application optimization', [
                'user_id' => auth()->id(),
                'timestamp' => now()->toDateTimeString()
            ]);

            Artisan::call('optimize');
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');

            return response()->json([
                'success' => true,
                'message' => 'Application optimized successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Application optimization failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error optimizing application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get job statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats()
    {
        try {
            $stats = [
                'vaccinations_without_certificates' => Vaccination::whereNull('certificate_id')->count(),
                'certificates_without_trusted_code' => Certificate::whereNull('trusted_vaccine_code')->count(),
                'total_certificates' => Certificate::count(),
                'total_vaccinations' => Vaccination::count(),
                'total_clients' => Client::count(),
                'recent_certificates' => Certificate::where('created_at', '>=', now()->subDays(7))->count(),
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
