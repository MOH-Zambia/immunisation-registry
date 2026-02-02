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
        try {
            // Cache dashboard statistics for 5 minutes to reduce database load
            $cacheKey = 'dashboard_stats_' . now()->format('Y-m-d-H-i');
            $cacheMinutes = 5;

            $stats = Cache::remember($cacheKey, now()->addMinutes($cacheMinutes), function () {
                return $this->calculateDashboardStatistics();
            });

            return view('dashboard', $stats);

        } catch (\Exception $e) {
            \Log::error('Dashboard error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return view('dashboard', $this->getDefaultStatistics())
                ->withErrors(['error' => 'Error loading dashboard data. Please check logs.']);
        }
    }

    /**
     * Calculate all dashboard statistics
     *
     * @return array
     */
    private function calculateDashboardStatistics()
    {
            $basicStats = $this->getBasicCounts();

            $demographics = $this->getDemographicStats();

            $recentStats = $this->getRecentActivity();

            $fullyVaccinated = $this->getFullyVaccinatedCount();

            $vaccineStats = $this->getVaccineStatistics();

            $vaccinationTrends = $this->getVaccinationTrends();

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

            $user_data = $this->getUserGrowthData();

            // Calculate vaccination progress percentage
            $vaccinationProgress = $basicStats->clients_count > 0
                ? round(($fullyVaccinated / $basicStats->clients_count) * 100, 1)
                : 0;

            return [
                'clients' => $basicStats->clients_count,
                'vaccinations' => $basicStats->vaccinations_count,
                'certificates' => $basicStats->certificates_count,
                'male_count' => $demographics['male_count'],
                'female_count' => $demographics['female_count'],
                'under_18' => $demographics['under_18'],
                'age_18_40' => $demographics['age_18_40'],
                'age_41_60' => $demographics['age_41_60'],
                'over_60' => $demographics['over_60'],
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
    }

    /**
     * Get basic counts
     */
    private function getBasicCounts()
    {
        return DB::table('clients')
            ->selectRaw('(SELECT COUNT(*) FROM clients) as clients_count')
            ->selectRaw('(SELECT COUNT(*) FROM vaccinations) as vaccinations_count')
            ->selectRaw('(SELECT COUNT(*) FROM certificates) as certificates_count')
            ->first();
    }

    /**
     * Get demographic statistics
     */
    private function getDemographicStats()
    {
        $genderStats = DB::table('clients')
            ->selectRaw('COUNT(CASE WHEN sex = "M" THEN 1 END) as male_count')
            ->selectRaw('COUNT(CASE WHEN sex = "F" THEN 1 END) as female_count')
            ->first();

        $ageStats = DB::table('clients')
            ->whereNotNull('date_of_birth')
            ->selectRaw('COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 18 THEN 1 END) as under_18')
            ->selectRaw('COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 40 THEN 1 END) as age_18_40')
            ->selectRaw('COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 41 AND 60 THEN 1 END) as age_41_60')
            ->selectRaw('COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) > 60 THEN 1 END) as over_60')
            ->first();

        return [
            'male_count' => $genderStats->male_count ?? 0,
            'female_count' => $genderStats->female_count ?? 0,
            'under_18' => $ageStats->under_18 ?? 0,
            'age_18_40' => $ageStats->age_18_40 ?? 0,
            'age_41_60' => $ageStats->age_41_60 ?? 0,
            'over_60' => $ageStats->over_60 ?? 0,
        ];
    }

    /**
     * Get recent activity statistics
     */
    private function getRecentActivity()
    {
        return DB::table('vaccinations')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('COUNT(*) as vaccinations_last_7_days')
            ->selectRaw('COUNT(DISTINCT client_id) as unique_clients_last_7_days')
            ->first();
    }

    /**
     * Get fully vaccinated count
     */
    private function getFullyVaccinatedCount()
    {
        $fullyVaccinatedWithJJ = DB::table('clients')
            ->whereExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('vaccinations')
                    ->whereColumn('vaccinations.client_id', 'clients.id')
                    ->where('vaccinations.vaccine_id', 3);
            })
            ->count();

        $fullyVaccinatedTwoDoses = DB::table('clients')
            ->whereRaw('(SELECT COUNT(*) FROM vaccinations WHERE vaccinations.client_id = clients.id) >= 2')
            ->count();

        $overlap = DB::table('clients')
            ->whereExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('vaccinations')
                    ->whereColumn('vaccinations.client_id', 'clients.id')
                    ->where('vaccinations.vaccine_id', 3);
            })
            ->whereRaw('(SELECT COUNT(*) FROM vaccinations WHERE vaccinations.client_id = clients.id) >= 2')
            ->count();

        return $fullyVaccinatedWithJJ + $fullyVaccinatedTwoDoses - $overlap;
    }

    /**
     * Get vaccine statistics
     */
    private function getVaccineStatistics()
    {
        return DB::table('vaccinations')
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
    }

    /**
     * Get vaccination trends (last 30 days)
     */
    private function getVaccinationTrends()
    {
        return DB::table('vaccinations')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
    }

    /**
     * Get user growth data for current year
     */
    private function getUserGrowthData()
    {
        $userMonthlyData = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month');

        $user_data = array_fill(0, 12, 0);
        foreach($userMonthlyData as $month => $count) {
            $user_data[$month - 1] = $count;
        }

        return $user_data;
    }

    /**
     * Get default statistics array for error cases
     */
    private function getDefaultStatistics()
    {
        return [
            'clients' => 0,
            'vaccinations' => 0,
            'certificates' => 0,
            'male_count' => 0,
            'female_count' => 0,
            'under_18' => 0,
            'age_18_40' => 0,
            'age_41_60' => 0,
            'over_60' => 0,
            'vaccinations_last_7_days' => 0,
            'unique_clients_last_7_days' => 0,
            'fully_vaccinated' => 0,
            'vaccination_progress' => 0,
            'astrazeneca_first_dose' => 0,
            'astrazeneca_second_dose' => 0,
            'astrazeneca_doses' => 0,
            'janssen_doses' => 0,
            'sinopharm_doses' => 0,
            'pfizer_doses' => 0,
            'moderna_first_dose' => 0,
            'moderna_second_dose' => 0,
            'moderna_doses' => 0,
            'user_data' => array_fill(0, 12, 0),
            'vaccination_trends' => collect()
        ];
    }
}
