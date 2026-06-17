<?php

namespace App\Console\Commands;

/*
 * © Copyright 2021 Ministry of Health, GRZ.
 *
 * This File is part of Immunisation Registry (IR)
 *
 * IR is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


/**
 * The script GenerateVaccinationCertificates
 *
 * This script seeds provinces into the database.
 * @package IR
 * @subpackage Commands
 * @access public
 * @author Chisanga Louis Siwale <Chisanga.Siwaled@moh.gov.zm>
 * @copyright Copyright &copy; 2021 Ministry of Health, GRZ.
 * @since v1.0
 * @version v1.0
 */

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use QRCode;

use App\Models\Certificate;
use App\Models\Vaccination;
use App\Services\QrCodeStorageService;

class GenerateVaccinationCertificates extends Command
{
    private const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GenerateVaccinationCertificates {--client= : Generate certificate for a single client id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates vaccination certificates for clients';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $number_of_certificates = 0;
        $script_start_time = microtime(true);
        $script_start_date_time = $this->timestamp();
        $qrCodeStorage = app(QrCodeStorageService::class);
        $clientId = $this->option('client');

        Log::info("$script_start_date_time: Generating certificates");
        $this->getOutput()->writeln("<info>$script_start_date_time: Script started - Generating certificates</info>");

        //Generate certificates
        $vaccinationsQuery = Vaccination::whereNull('certificate_id');

        if (!empty($clientId)) {
            $vaccinationsQuery->where('client_id', $clientId);
            $this->getOutput()->writeln("<comment>{$script_start_date_time} Filtering by client_id:</comment> {$clientId}");
        }

        $vaccinations = $vaccinationsQuery->get();

        foreach($vaccinations as $vaccination){
            $startTime = microtime(true);

            $certificate = Certificate::where([
                ['client_id', '=', $vaccination->client_id],
            ])->first();

            if(empty($certificate)){
                $time = $this->timestamp();
                $this->getOutput()->writeln("<comment>$time Saving certificate for client:</comment> {$vaccination->client_id}");

                $certificate_uuid = Str::orderedUuid();
                $certificate_url = env('APPLICATION_CERTIFICATE_URL').$certificate_uuid;
                $qr_code_path = $qrCodeStorage->relativePathForUuid($certificate_uuid);
                $qrcode = $this->generateQrCodeContents($certificate_url);

                if ($qrcode !== null) {

                    $qr_code_path = $qrCodeStorage->storeQrCode($certificate_uuid, $qrcode);

                    DB::beginTransaction();
                    try{
                        //Create new certificate
                        $certificate = new Certificate();

                        $certificate->certificate_uuid = $certificate_uuid;
                        $certificate->client_id = $vaccination->client_id;
                        $certificate->target_disease = 'COVID-19';
                        $certificate->qr_code = $qrcode;
                        $certificate->qr_code_path = $qr_code_path;
                        $certificate->certificate_url = $certificate_url;

                        $certificate->save();

                        //Add reference to the certificate in the vaccination table
                        $vaccination->certificate_id = $certificate->id;
                        $vaccination->save();

                        DB::commit();

                        $time = $this->timestamp();
                        $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
                        $this->getOutput()->writeln("<info>$time Certificate saved: {{$certificate_url}}</info> ({$runTime}ms)");
                    } catch (\Exception $e) {
                        DB::rollback(); //Rollback database transaction if any error occurs

                        $message = $e->getMessage();
                        $time = $this->timestamp();
                        $this->getOutput()->writeln("<error>$time Exception: $message</error>");
                    }
                } else {
                    $this->getOutput()->writeln("{$time} <comment>QR Code NOT FOUND : </comment> $qr_code_path}");
                }
            } else {
                $time = $this->timestamp();
                $this->getOutput()->writeln("<comment>$time Updating certificate for client:</comment> {$vaccination->client_id}");

                $vaccination->certificate_id = $certificate->id;
                $vaccination->save();

                DB::commit();

                $time = $this->timestamp();
                $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
                $this->getOutput()->writeln("<info>$time Updated certificate: {{$certificate->certificate_url}}</info> ({$runTime}ms)");
            }

            $number_of_certificates++;
        }

        $script_end_time = $this->timestamp();
        $script_run_time = number_format((microtime(true) - $script_start_time) * 1000, 2);
        Log::info("$script_end_time Completed generating $number_of_certificates certificates: Duration: $script_run_time");
        $this->getOutput()->writeln("<info>$script_end_time Script completed:</info> Completed generating $number_of_certificates certificates. Duration: $script_run_time");

        return Command::SUCCESS;
    }

    private function timestamp(): string
    {
        return date(self::DATE_TIME_FORMAT);
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
}

