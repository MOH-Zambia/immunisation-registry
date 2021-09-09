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
    protected $description = 'Command description';

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
        $astrazeneca_first_dose = Vaccination::where([
            ['vaccine_id', '=', 1],
            ['dose_number', '=', 'First'],
        ])->whereNull('certificate_id')->get();

        $astrazeneca_second_dose = Vaccination::where([
            ['vaccine_id', '=', 1],
            ['dose_number', '=', 'Second'],
        ])->whereNull('certificate_id')->get();

        $astrazeneca_fully_vaccinated = $astrazeneca_first_dose->intersect($astrazeneca_second_dose);

        foreach($astrazeneca_fully_vaccinated as $vaccination){

            $certificate = Certificate::where('client_id', $vaccination->client_id)->first();

            if(empty($certificate)){
                $this->getOutput()->writeln("<comment>ASTRAZENCA Saving certificate for client:</comment> {$vaccination->client_id}");
                $startTime = microtime(true);

                $certificate_uuid = Str::orderedUuid();

                $dose1 = Vaccination::where([
                    ['client_id', '=', $vaccination->client_id],
                    ['dose_number', '=', 'First'],
                ])->whereNull('certificate_id')->get();

                $dose2 = Vaccination::where([
                    ['client_id', '=', $vaccination->client_id],
                    ['dose_number', '=', 'Second'],
                ])->whereNull('certificate_id')->get();

                $certificate_url = env('APPLICATION_CERTIFICATE_URL').$certificate_uuid;

                //generate qrcode
                //save qrcode image in our folder on this site
                $qr_code_path = 'img/qrcodes/'.$certificate_uuid.'.png';

                //generate qrcode
                QRCode::url($certificate_url)
                    ->setSize(6)
                    ->setOutfile('public/'.$qr_code_path)
                    ->png();

                $qrcode = file_get_contents('public/'.$qr_code_path);

                $certificate = new Certificate([
                    'certificate_uuid' => $certificate_uuid,
                    'client_id' => $vaccination->client_id,
                    'dose_1_date' => $dose1->date,
                    'dose_2_date' => $dose2->date,
                    'qr_code' => $qrcode,
                    'qr_code_path' => $qr_code_path,
                    'certificate_url' => $certificate_url,
                ]);

                $certificate->save();

                //Add reference to the certificate in the vaccination table
                $dose1->certificate_id = $certificate->id;
                $dose1->save();

                $dose2->certificate_id = $certificate->id;
                $dose2->save();

                $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
                $this->getOutput()->writeln("<info>Certificate saved: {{$certificate_url}}</info> ({$runTime}ms)");
            }
        }

        $janssen_dose = Vaccination::where([
            ['vaccine_id', '=', 3],
        ])->whereNull('certificate_id')->get();

        foreach($janssen_dose as $vaccination){

            $certificate = Certificate::where('client_id', $vaccination->client_id)->first();

            if(empty($certificate)){
                $certificate_uuid =  (string) Str::orderedUuid();

                $this->getOutput()->writeln("<comment>JANSSEN Saving certificate for client: {$certificate_uuid}</comment> {$vaccination->client_id}");
                $startTime = microtime(true);

                $certificate_url = env('APPLICATION_CERTIFICATE_URL').$certificate_uuid;

                //generate qrcode
                //save qrcode image in our folder on this site
                $qr_code_path = 'img/qrcodes/'.$certificate_uuid.'.png';

                QRCode::url($certificate_url)
                    ->setSize(6)
                    ->setOutfile('public/'.$qr_code_path)
                    ->png();

                $qrcode = file_get_contents('public/'.$qr_code_path);

                // $qrcode = QrCode::format('png')
                //     ->size(8)
                //     ->margin(2)
                //     ->generate($certificate_url);

                $certificate = new Certificate([
                    'certificate_uuid' => $certificate_uuid,
                    'client_id' => $vaccination->client_id,
                    'dose_1_date' => $vaccination->date,
                    'qr_code' => $qrcode,
                    'qr_code_path' => $qr_code_path,
                    'certificate_url' => $certificate_url,
                ]);

                $certificate->save();

                //Add reference to the certificate in the vaccination table
                $vaccination->certificate_id = $certificate->id;
                $vaccination->save();

                $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
                $this->getOutput()->writeln("<info>Certificate saved: {{$certificate_url}}</info> ({$runTime}ms)");
            }
        }

        $sinopharm_dose = Vaccination::where([
            ['vaccine_id', '=', 7],
        ])->whereNull('certificate_id')->get();

        foreach($sinopharm_dose as $vaccination){

            $certificate = Certificate::where('client_id', $vaccination->client_id)->first();

            if(empty($certificate)){
                $certificate_uuid =  (string) Str::orderedUuid();

                $this->getOutput()->writeln("<comment>SINOPHARM Saving certificate for client: {$certificate_uuid}</comment> {$vaccination->client_id}");
                $startTime = microtime(true);

                $certificate_url = env('APPLICATION_CERTIFICATE_URL').$certificate_uuid;

                //generate qrcode
                //save qrcode image in our folder on this site
                $qr_code_path = 'img/qrcodes/'.$certificate_uuid.'.png';

                QRCode::url($certificate_url)
                    ->setSize(6)
                    ->setOutfile('public/'.$qr_code_path)
                    ->png();

                $qrcode = file_get_contents('public/'.$qr_code_path);

                // $qrcode = QrCode::format('png')
                //     ->size(8)
                //     ->margin(2)
                //     ->generate($certificate_url);

                $certificate = new Certificate([
                    'certificate_uuid' => $certificate_uuid,
                    'client_id' => $vaccination->client_id,
                    'dose_1_date' => $vaccination->date,
                    'qr_code' => $qrcode,
                    'qr_code_path' => $qr_code_path,
                    'certificate_url' => $certificate_url,
                ]);

                $certificate->save();

                //Add reference to the certificate in the vaccination table
                $vaccination->certificate_id = $certificate->id;
                $vaccination->save();

                $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
                $this->getOutput()->writeln("<info>Certificate saved: {{$certificate_url}}</info> ({$runTime}ms)");
            }
        }

        return 0;
    }
}
