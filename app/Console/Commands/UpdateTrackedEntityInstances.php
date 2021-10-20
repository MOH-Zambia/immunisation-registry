<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Facility;
use App\Models\ImportLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UpdateTrackedEntityInstances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Base URL for trackedEntityInstances
     */
    const TRACKED_ENTITY_INSTANCES_URL = 'https://dhis2.moh.gov.zm/covax/api/trackedEntityInstances.json';

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
        $httpClient = new GuzzleHttp\Client();

        $facilities = Facility::all();

        foreach($facilities as $facility) {
            if (!empty($facility->DHIS2_UID)) {
                $response = $httpClient->request('GET', self::TRACKED_ENTITY_INSTANCES_URL . "?orgUnit={$facility->DHIS2_UID}", [
                    'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
                ]);

                $client = null;

                if ($response->getStatusCode() == 200) {
                    $responce_body = json_decode($response->getBody(), true);
                    $trackedEntityInstance = $responce_body['trackedEntityInstances'][0];

                    $client_uid = $trackedEntityInstance['trackedEntityInstance'];
                    $orgUnit = $trackedEntityInstance['orgUnit'];
                    //Check is client exits in database already
                    $client = Client::where('client_uid', $client_uid)->first();

                    // Check if hash of $trackedEntityInstance exist in import log
                    $import_log = ImportLog::where('hash', Hash::make(json_encode($trackedEntityInstance)))->first();
                }
            }
        }
        return 0;
    }
}
