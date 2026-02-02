<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Client;
use App\Models\Vaccination;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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
        // Cache dashboard statistics for 5 minutes to reduce database load
        $cacheKey = 'dashboard_stats_' . date('Y-m-d-H') . '_' . floor(now()->minute / 5);

        $stats = Cache::remember($cacheKey, now()->addMinutes(5), function () {
            // Optimize: Use single query for basic counts
            $basicStats = DB::table('clients')
                ->selectRaw('(SELECT COUNT(*) FROM clients) as clients_count')
                ->selectRaw('(SELECT COUNT(*) FROM vaccinations) as vaccinations_count')
                ->selectRaw('(SELECT COUNT(*) FROM certificates) as certificates_count')
                ->first();

            // Optimize: Use single query with conditional aggregation for vaccine doses
            $vaccineStats = DB::table('vaccinations')
                ->selectRaw('COUNT(CASE WHEN vaccine_id = 1 THEN 1 END) as astrazeneca_doses')
                ->selectRaw('COUNT(CASE WHEN vaccine_id = 1 AND dose_number = "1" THEN 1 END) as astrazeneca_first_dose')
                ->selectRaw('COUNT(CASE WHEN vaccine_id = 1 AND dose_number = "2" THEN 1 END) as astrazeneca_second_dose')
                ->selectRaw('COUNT(CASE WHEN vaccine_id = 3 THEN 1 END) as janssen_doses')
                ->selectRaw('COUNT(CASE WHEN vaccine_id = 7 THEN 1 END) as sinopharm_doses')
                ->selectRaw('COUNT(CASE WHEN vaccine_id = 6 THEN 1 END) as pfizer_doses')
                ->selectRaw('COUNT(CASE WHEN vaccine_id = 4 THEN 1 END) as moderna_doses')
                ->selectRaw('COUNT(CASE WHEN vaccine_id = 4 AND dose_number = "1" THEN 1 END) as moderna_first_dose')
                ->selectRaw('COUNT(CASE WHEN vaccine_id = 4 AND dose_number = "2" THEN 1 END) as moderna_second_dose')
                ->first();

            // Optimize: Single query for user registration data
            $userMonthlyData = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->pluck('count', 'month');

            // Initialize array with 12 months (all zeros)
            $user_data = array_fill(0, 12, 0);

            // Fill in actual data
            foreach($userMonthlyData as $month => $count) {
                $user_data[$month - 1] = $count; // Array is 0-indexed, months are 1-indexed
            }

            return [
                'clients' => $basicStats->clients_count,
                'vaccinations' => $basicStats->vaccinations_count,
                'certificates' => $basicStats->certificates_count,
                'astrazeneca_doses' => $vaccineStats->astrazeneca_doses,
                'astrazeneca_first_dose' => $vaccineStats->astrazeneca_first_dose,
                'astrazeneca_second_dose' => $vaccineStats->astrazeneca_second_dose,
                'janssen_doses' => $vaccineStats->janssen_doses,
                'sinopharm_doses' => $vaccineStats->sinopharm_doses,
                'pfizer_doses' => $vaccineStats->pfizer_doses,
                'moderna_doses' => $vaccineStats->moderna_doses,
                'moderna_first_dose' => $vaccineStats->moderna_first_dose,
                'moderna_second_dose' => $vaccineStats->moderna_second_dose,
                'user_data' => $user_data
            ];
        });

        return view('dashboard')
            ->with('clients', $stats['clients'])
            ->with('vaccinations', $stats['vaccinations'])
            ->with('certificates', $stats['certificates'])
            ->with('astrazeneca_first_dose', $stats['astrazeneca_first_dose'])
            ->with('astrazeneca_second_dose', $stats['astrazeneca_second_dose'])
            ->with('astrazeneca_doses', $stats['astrazeneca_doses'])
            ->with('janssen_doses', $stats['janssen_doses'])
            ->with('sinopharm_doses', $stats['sinopharm_doses'])
            ->with('pfizer_doses', $stats['pfizer_doses'])
            ->with('moderna_first_dose', $stats['moderna_first_dose'])
            ->with('moderna_second_dose', $stats['moderna_second_dose'])
            ->with('moderna_doses', $stats['moderna_doses'])
            ->with(compact('user_data', $stats['user_data']));
    }
}
