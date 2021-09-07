<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Client;
use App\Models\Vaccination;
use App\Models\Certificate;

class AdminController extends Controller
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
            ['dose_number', '=', 'First'],
        ])->count();

        $astrazeneca_second_dose = Vaccination::where([
            ['vaccine_id', '=', 1],
            ['dose_number', '=', 'Second'],
        ])->count();

        $janssen_doses = Vaccination::where([
            ['vaccine_id', '=', 3],
        ])->count();

        $sinopharm_doses = Vaccination::where([
            ['vaccine_id', '=', 7],
        ])->count();

        return view('admin')
            ->with('clients', $clients)
            ->with('vaccinations',  $vaccinations)
            ->with('certificates',  $certificates)
            ->with('astrazeneca_doses',  $astrazeneca_doses)
            ->with('janssen_doses',  $janssen_doses)
            ->with('sinopharm_doses',  $sinopharm_doses);
    }
}
