<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Client;
use App\Models\Vaccination;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all()->count();
        $vaccinations = Vaccination::all()->count();
        $certificates = Certificate::all()->count();

        $astrazeneca_doses = Vaccination::where([
            ['vaccine_id', '=', 1],
        ])->count();

        $astrazeneca_first_dose = Vaccination::where([
            ['vaccine_id', '=', 1],
            ['dose_number', '=', '1'],
        ])->count();

        $astrazeneca_second_dose = Vaccination::where([
            ['vaccine_id', '=', 1],
            ['dose_number', '=', '2'],
        ])->count();

        $janssen_doses = Vaccination::where([
            ['vaccine_id', '=', 3],
        ])->count();

        $sinopharm_doses = Vaccination::where([
            ['vaccine_id', '=', 7],
        ])->count();

        $pfizer_doses = Vaccination::where([
            ['vaccine_id', '=', 6],
        ])->count();

        $moderna_first_dose = Vaccination::where([
            ['vaccine_id', '=', 4],
            ['dose_number', '=', '1'],
        ])->count();

        $moderna_second_dose = Vaccination::where([
            ['vaccine_id', '=', 4],
            ['dose_number', '=', '2'],
        ])->count();

        $moderna_doses = Vaccination::where([
            ['vaccine_id', '=', 4],
        ])->count();

        $users = User::select(DB::raw("COUNT(*) AS count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('count');

        $months = User::select(DB::raw("Month(created_at) AS month"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw("Month(created_at)"))
            ->pluck('month');


        $user_data = array(0,0,0,0,0,0,0,0,0,0,0,0);

        foreach($months as $index => $month){
            $user_data[$month] = $users[$index];
        }

//        $data = array_combine($users->toArray(), $months->toArray());

        $vaccinations_last_12_month_by_vaccine = DB::select('select * from view_vaccinations_last_12_month_by_vaccine');

        $tempDataPoints = [];
        $dataPoints = [];

        foreach ($vaccinations_last_12_month_by_vaccine as $month) {
            $vaccine_doses = array(0,0,0);

            if(isset($tempDataPoints[$month->month])){
                $key = array_search($month->month, array_keys($tempDataPoints));

                if($month->vaccine_id == 1){
                    $tempDataPoints[$month->month][0] = $month->number_of_doses;
                    $dataPoints[$key]['data'][0] = $month->number_of_doses;
                }
                if($month->vaccine_id == 3){
                    $tempDataPoints[$month->month][1] = $month->number_of_doses;
                    $dataPoints[$key]['data'][1] = $month->number_of_doses;
                }

                if($month->vaccine_id == 7){
                    $tempDataPoints[$month->month][2] = $month->number_of_doses;
                    $dataPoints[$key]['data'][2] = $month->number_of_doses;
                }

            } else {
                if($month->vaccine_id == 1)
                    $vaccine_doses[0] = $month->number_of_doses;
                if($month->vaccine_id == 3)
                    $vaccine_doses[1] = $month->number_of_doses;
                if($month->vaccine_id == 7)
                    $vaccine_doses[2] = $month->number_of_doses;

                $tempDataPoints[$month->month] = $vaccine_doses;

                $dataPoints[] = array(
                    'month' => $month->month,
                    'data' => $vaccine_doses,
                );
            }
        }

        $vaccines = array('AstraZeneca', 'Jassen', 'Sinoparm');

        return view('dashboard')
            ->with('clients', $clients)
            ->with('vaccinations',  $vaccinations)
            ->with('certificates',  $certificates)
            ->with('astrazeneca_first_dose',  $astrazeneca_first_dose)
            ->with('astrazeneca_second_dose',  $astrazeneca_second_dose)
            ->with('astrazeneca_doses',  $astrazeneca_doses)
            ->with('janssen_doses',  $janssen_doses)
            ->with('sinopharm_doses',  $sinopharm_doses)
            ->with('pfizer_doses',  $pfizer_doses)
            ->with('moderna_first_dose',  $moderna_first_dose)
            ->with('moderna_second_dose',  $moderna_second_dose)
            ->with('moderna_doses',  $moderna_doses)
            ->with(compact('user_data', 'dataPoints', 'vaccines'));
    }
}
