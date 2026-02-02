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

            // Gender breakdown
            $genderStats = DB::table('clients')
                ->selectRaw('COUNT(CASE WHEN sex = "M" THEN 1 END) as male_count')
                ->selectRaw('COUNT(CASE WHEN sex = "F" THEN 1 END) as female_count')
                ->first();

            // Age group breakdown (approximate based on date_of_birth)
            $ageStats = DB::table('clients')
                ->whereNotNull('date_of_birth')
                ->selectRaw('COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 18 THEN 1 END) as under_18')
                ->selectRaw('COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 40 THEN 1 END) as age_18_40')
                ->selectRaw('COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 41 AND 60 THEN 1 END) as age_41_60')
                ->selectRaw('COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) > 60 THEN 1 END) as over_60')
                ->first();

            // Recent activity (last 7 days)
            $recentStats = DB::table('vaccinations')
                ->where('created_at', '>=', now()->subDays(7))
                ->selectRaw('COUNT(*) as vaccinations_last_7_days')
                ->selectRaw('COUNT(DISTINCT client_id) as unique_clients_last_7_days')
                ->first();

            // Fully vaccinated count (clients with at least 2 doses or 1 J&J dose)
            $fullyVaccinated = DB::table('clients')
                ->whereExists(function($query) {
                    $query->select(DB::raw(1))
                        ->from('vaccinations')
                        ->whereColumn('vaccinations.client_id', 'clients.id')
                        ->where(function($q) {
                            $q->where('vaccinations.vaccine_id', 3) // J&J single dose
                              ->orHaving(DB::raw('COUNT(*)'), '>=', 2); // Or 2+ doses
                        });
                })
                ->count();

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

            // Vaccination trends - last 30 days by day
            $vaccinationTrends = DB::table('vaccinations')
                ->where('created_at', '>=', now()->subDays(30))
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

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

            // Calculate vaccination progress percentage
            $vaccinationProgress = $basicStats->clients_count > 0
                ? round(($fullyVaccinated / $basicStats->clients_count) * 100, 1)
                : 0;

            return [
                'clients' => $basicStats->clients_count,
                'vaccinations' => $basicStats->vaccinations_count,
                'certificates' => $basicStats->certificates_count,
                'male_count' => $genderStats->male_count ?? 0,
                'female_count' => $genderStats->female_count ?? 0,
                'under_18' => $ageStats->under_18 ?? 0,
                'age_18_40' => $ageStats->age_18_40 ?? 0,
                'age_41_60' => $ageStats->age_41_60 ?? 0,
                'over_60' => $ageStats->over_60 ?? 0,
                'vaccinations_last_7_days' => $recentStats->vaccinations_last_7_days ?? 0,
                'unique_clients_last_7_days' => $recentStats->unique_clients_last_7_days ?? 0,
                'fully_vaccinated' => $fullyVaccinated,
                'vaccination_progress' => $vaccinationProgress,
                'astrazeneca_doses' => $vaccineStats->astrazeneca_doses,
                'astrazeneca_first_dose' => $vaccineStats->astrazeneca_first_dose,
                'astrazeneca_second_dose' => $vaccineStats->astrazeneca_second_dose,
                'janssen_doses' => $vaccineStats->janssen_doses,
                'sinopharm_doses' => $vaccineStats->sinopharm_doses,
                'pfizer_doses' => $vaccineStats->pfizer_doses,
                'moderna_doses' => $vaccineStats->moderna_doses,
                'moderna_first_dose' => $vaccineStats->moderna_first_dose,
                'moderna_second_dose' => $vaccineStats->moderna_second_dose,
                'user_data' => $user_data,
                'vaccination_trends' => $vaccinationTrends
            ];
        });

        return view('dashboard')
            ->with('clients', $stats['clients'])
            ->with('vaccinations', $stats['vaccinations'])
            ->with('certificates', $stats['certificates'])
            ->with('male_count', $stats['male_count'])
            ->with('female_count', $stats['female_count'])
            ->with('under_18', $stats['under_18'])
            ->with('age_18_40', $stats['age_18_40'])
            ->with('age_41_60', $stats['age_41_60'])
            ->with('over_60', $stats['over_60'])
            ->with('vaccinations_last_7_days', $stats['vaccinations_last_7_days'])
            ->with('unique_clients_last_7_days', $stats['unique_clients_last_7_days'])
            ->with('fully_vaccinated', $stats['fully_vaccinated'])
            ->with('vaccination_progress', $stats['vaccination_progress'])
            ->with('astrazeneca_first_dose', $stats['astrazeneca_first_dose'])
            ->with('astrazeneca_second_dose', $stats['astrazeneca_second_dose'])
            ->with('astrazeneca_doses', $stats['astrazeneca_doses'])
            ->with('janssen_doses', $stats['janssen_doses'])
            ->with('sinopharm_doses', $stats['sinopharm_doses'])
            ->with('pfizer_doses', $stats['pfizer_doses'])
            ->with('moderna_first_dose', $stats['moderna_first_dose'])
            ->with('moderna_second_dose', $stats['moderna_second_dose'])
            ->with('moderna_doses', $stats['moderna_doses'])
            ->with('user_data', $stats['user_data'])
            ->with('vaccination_trends', $stats['vaccination_trends']);
    }
}
