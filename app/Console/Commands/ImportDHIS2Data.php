<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp;

class ImportDHIS2Data extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ImportDHIS2Data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import DHIS2 Data from COVAX instance';

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

        $client = new GuzzleHttp\Client();

        // disable cert verification
        // $client->setDefaultOption(['verify'=>false]);

//        $res = $client->request('GET', env('DHIS2_URL'), [
//            'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
//            'query' => [
//                'ou'=>'sy04jreTFc0',
//                'program'=>'yDuAzyqYABS',
//                'programStartDate'=>'2021-08-01',
//                'programEndDate'=>'2021-08-08'
//            ]
//        ]);
//        echo $res->getStatusCode();
        // "200"
//        echo $res->getHeader('content-type')[0];
        // 'application/json; charset=utf8'
//        echo $res->getBody();

        // Send an asynchronous request.
        $request = new \GuzzleHttp\Psr7\Request('GET', env('DHIS2_URL'), [
            'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')],
            'query' => [
                'ou'=>'sy04jreTFc0',
                'program'=>'yDuAzyqYABS',
                'programStartDate'=>'2021-08-01',
                'programEndDate'=>'2021-08-08'
            ]
        ]);
        $promise = $client->sendAsync($request)->then(function ($response) {
            echo 'I completed! \n' . $response->getBody();
        });
        $promise->wait();
        return 0;
    }
}
