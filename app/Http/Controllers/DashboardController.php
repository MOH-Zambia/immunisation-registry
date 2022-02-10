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
        $clients = DB::table('clients')->count();

        $vaccinations = DB::table('vaccinations')->count();

        $certificates = DB::table('certificates')->count();

        $astrazeneca_doses = DB::table('vaccinations')->where([
            ['vaccine_id', '=', 1],
        ])->count();

        $astrazeneca_first_dose = DB::table('vaccinations')->where([
            ['vaccine_id', '=', 1],
            ['dose_number', '=', '1'],
        ])->count();

        $astrazeneca_second_dose = DB::table('vaccinations')->where([
            ['vaccine_id', '=', 1],
            ['dose_number', '=', '2'],
        ])->count();

        $janssen_doses = DB::table('vaccinations')->where([
            ['vaccine_id', '=', 3],
        ])->count();

        $sinopharm_doses = DB::table('vaccinations')->where([
            ['vaccine_id', '=', 7],
        ])->count();


        $pfizer_doses = DB::table('vaccinations')->where([
            ['vaccine_id', '=', 6],
        ])->count();


        $moderna_first_dose = DB::table('vaccinations')->where([
            ['vaccine_id', '=', 4],
            ['dose_number', '=', '1'],
        ])->count();

        $moderna_second_dose = DB::table('vaccinations')->where([
            ['vaccine_id', '=', 4],
            ['dose_number', '=', '2'],
        ])->count();


        $moderna_doses = DB::table('vaccinations')->where([
            ['vaccine_id', '=', 4],
        ])->count();

        $users = User::select(DB::raw("COUNT(*) AS count"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->pluck('count');

        $months = User::select(DB::raw("MONTH(created_at) AS month"))
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->pluck('month');


        $user_data = array(0,0,0,0,0,0,0,0,0,0,0,0);

        foreach($months as $index => $month){
            $user_data[$month] = $users[$index];
        }

        $data = array_combine($users->toArray(), $months->toArray());


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
            ->with(compact('user_data'));
    }
}
