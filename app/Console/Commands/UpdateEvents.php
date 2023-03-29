<?php

namespace App\Console\Commands;

use App\Models\Vaccination;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UpdateEvents';

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
        $httpClient = new GuzzleHttp\Client();

        $vaccinations = Vaccination::all(['source_id', 'source_updated_at']);

        foreach ($vaccinations as $vaccination) {
            try {
                $response = $httpClient->request('GET', env('DHIS2_BASE_URL')."events.json?event = {$vaccination->source_id}", [
                    'auth' => [env('DHIS2_USERNAME'), env('DHIS2_PASSWORD')]
                ]);

                if ($response->getStatusCode() == 200) {
                    $response_body = json_decode($response->getBody(), true);
                    $event = $response_body['events'][0];

                    $lastUpdated = new DateTime($event['lastUpdated']);
                    $lastUpdated = $lastUpdated->format('Y-m-d H:i:s');

                    if ($lastUpdated > $vaccination->source_updated_at) {
                        DB::beginTransaction();

                        $time = date('Y-m-d H:i:s');

                        $this->getOutput()
                            ->writeln("<info>$time Saving event... Event UID: {$event['event']}, TRACKED_ENTITY_INSTANCE: {$event['trackedEntityInstance']}</info>");

                        $vaccination->date = $event['eventDate'];
                        $vaccination->source_updated_at = $event['lastUpdated'];

                        switch ($event['programStage']) {
                            case 'a1jCssI2LkW': //programStage: Vaccination Dose 1
                                $vaccination->dose_number = '1';
                                break;
                            case 'RiV7VDxXQLN': //programStage: Vaccination Dose 2
                                $vaccination->dose_number = '2';
                                break;
                            case 'jatC7jRwVKO': //programStage: Vaccination Booster Dose
                                $vaccination->dose_number = 'Booster';
                                break;
                        }

                        foreach ($event['dataValues'] as $dataValue) {
                            if ($dataValue['dataElement'] == 'bbnyNYD1wgS') { //Vaccine Name
                                switch ($dataValue['value']) {
                                    case 'AstraZeneca_zm':
                                        $vaccination->vaccine_id = 1;
                                        break;
                                    case 'Johnson_Johnsons_zm':
                                        $vaccination->vaccine_id = 3;
                                        break;
                                    case 'Sinopharm':
                                        $vaccination->vaccine_id = 7;
                                        break;
                                    case 'Pfizer':
                                        $vaccination->vaccine_id = 6;
                                        break;
                                    case 'Moderna':
                                        $vaccination->vaccine_id = 4;
                                        break;
                                }
                            } else if ($dataValue['dataElement'] == 'Yp1F4txx8tm') { // Batch Number
                                $vaccination->vaccine_batch_number = $dataValue['value'];
                            } else if ($dataValue['dataElement'] == 'FFWcps4MfuH') { //Suggested date for next dose
                                $vaccination->date_of_next_dose = $dataValue['value'];
                            }
                        }

                        $vaccination->save();

                        DB::commit();
                    }
                }
            } catch (ConnectException $e) {
                // Connection exceptions are not caught by RequestException
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');
                Log::error( " $time: ConnectException - $message");
                $this->getOutput()->writeln("<error>$time $message </error>");
            } catch (RequestException $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');
                Log::error( "$time: RequestException - $message");
                $this->getOutput()->writeln("<error>$time RequestException: $message</error>");
            } catch (TransferException $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');
                Log::error( "$time: TransferException: $message");
                $this->getOutput()->writeln("<error>$time TransferException: $message</error>");
            }catch (Exception $e) {
                $message = $e->getMessage();
                $time = date('Y-m-d H:i:s');
                $trackedEntityInstance = json_encode($event, JSON_UNESCAPED_SLASHES);

                Log::error( "$time: Exception: $message \n $event");
                $this->getOutput()->writeln("<error>$time Exception: $message \n $event</error>");
            }
        }
        return Command::SUCCESS;
    }
}
