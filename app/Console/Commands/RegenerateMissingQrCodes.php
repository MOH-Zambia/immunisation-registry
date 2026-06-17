<?php

namespace App\Console\Commands;

use App\Models\Certificate;
use App\Services\QrCodeStorageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RegenerateMissingQrCodes extends Command
{
    private const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:RegenerateMissingQrCodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerates missing QR code files for existing certificates';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $startTime = microtime(true);
        $startedAt = $this->timestamp();
        $qrCodeStorage = app(QrCodeStorageService::class);

        $processed = 0;
        $recovered = 0;
        $skipped = 0;
        $failed = 0;

        Log::info($startedAt . ': Regenerating missing QR codes');
        $this->info($startedAt . ': Script started - Regenerating missing QR codes');

        Certificate::query()
            ->orderBy('id')
            ->chunkById(100, function ($certificates) use ($qrCodeStorage, &$processed, &$recovered, &$skipped, &$failed) {
                foreach ($certificates as $certificate) {
                    $processed++;

                    try {
                        if (!$qrCodeStorage->isQrCodeMissingForCertificate($certificate)) {
                            $skipped++;
                            continue;
                        }

                        $restoredPath = $qrCodeStorage->ensureQrCodeExists($certificate);

                        if ($restoredPath === null) {
                            $failed++;
                            $this->warn($this->timestamp() . ' QR code recovery failed for certificate: ' . $certificate->certificate_uuid);
                            continue;
                        }

                        $recovered++;
                        $this->info($this->timestamp() . ' QR code restored for certificate: ' . $certificate->certificate_uuid);
                    } catch (\Throwable $exception) {
                        $failed++;

                        Log::error('QR code recovery failed', [
                            'certificate_id' => $certificate->id,
                            'certificate_uuid' => $certificate->certificate_uuid,
                            'error' => $exception->getMessage(),
                        ]);

                        $this->error($this->timestamp() . ' QR code recovery exception for certificate: ' . $certificate->certificate_uuid . ' - ' . $exception->getMessage());
                    }
                }
            });

        $duration = number_format((microtime(true) - $startTime) * 1000, 2);
        $completedAt = $this->timestamp();

        $summary = sprintf(
            '%s Script completed: Processed %d certificates. Recovered: %d. Skipped: %d. Failed: %d. Duration: %sms',
            $completedAt,
            $processed,
            $recovered,
            $skipped,
            $failed,
            $duration
        );

        Log::info($summary);
        $this->info($summary);

        return $failed > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    private function timestamp(): string
    {
        return date(self::DATE_TIME_FORMAT);
    }
}

