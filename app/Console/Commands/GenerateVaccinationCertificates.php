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
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use QRCode;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Models\Client;
use App\Models\Certificate;
use App\Models\Vaccination;

class GenerateVaccinationCertificates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GenerateVaccinationCertificates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates vaccination certificates for clients';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = "public/img/qrcodes/";
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $number_of_certificates = 0;
        $script_start_time = microtime(true);
        $script_start_date_time = date('Y-m-d H:i:s');

        Log::info("$script_start_date_time: Generating certificates");
        $this->getOutput()->writeln("<info>$script_start_date_time: Script started - Generating certificates</info>");

        //Generate certificates
        $vaccinations = Vaccination::whereNull('certificate_id')->get();

        foreach($vaccinations as $vaccination){
            $startTime = microtime(true);

            $certificate = Certificate::where([
                ['client_id', '=', $vaccination->client_id],
            ])->first();

            if(empty($certificate)){
                $time = date('Y-m-d H:i:s');
                $this->getOutput()->writeln("<comment>$time Saving certificate for client:</comment> {$vaccination->client_id}");

                $certificate_uuid = Str::orderedUuid();
                $certificate_url = env('APPLICATION_CERTIFICATE_URL').$certificate_uuid;

                //generate qrcode and save qrcode image in our folder on this site
                $qr_code_path = 'img/qrcodes/'.$certificate_uuid.'.png';
                QRCode::url($certificate_url)
                    ->setSize(6)
                    ->setOutfile('public/'.$qr_code_path)
                    ->png();

                if (file_exists('public/'.$qr_code_path)) {

                    $qrcode = file_get_contents('public/'.$qr_code_path);
                    // $this->getOutput()->writeln("{$time} <comment>QR Code (Contents) :</comment> {$qrcode}");

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

                        $time = date('Y-m-d H:i:s');
                        $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
                        $this->getOutput()->writeln("<info>$time Certificate saved: {{$certificate_url}}</info> ({$runTime}ms)");
                    } catch (\Exception $e) {
                        DB::rollback(); //Rollback database transaction if any error occurs

                        $message = $e->getMessage();
                        $time = date('Y-m-d H:i:s');
                        $this->getOutput()->writeln("<error>$time Exception: $message</error>");
                    }
                } else {
                    $this->getOutput()->writeln("{$time} <comment>QR Code NOT FOUND : </comment> $qr_code_path}");
                }
            } else {
                $time = date('Y-m-d H:i:s');
                $this->getOutput()->writeln("<comment>$time Updating certificate for client:</comment> {$vaccination->client_id}");

                $vaccination->certificate_id = $certificate->id;
                $vaccination->save();

                DB::commit();

                $time = date('Y-m-d H:i:s');
                $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
                $this->getOutput()->writeln("<info>$time Updated certificate: {{$certificate->certificate_url}}</info> ({$runTime}ms)");
            }

            $number_of_certificates++;
        }

        $script_end_time = date('Y-m-d H:i:s');
        $script_run_time = number_format((microtime(true) - $script_start_time) * 1000, 2);
        Log::info("$script_end_time Completed generating $number_of_certificates certificates: Duration: $script_run_time");
        $this->getOutput()->writeln("<info>$script_end_time Script completed:</info> Completed generating $number_of_certificates certificates. Duration: $script_run_time");

        return Command::SUCCESS;
    }
}