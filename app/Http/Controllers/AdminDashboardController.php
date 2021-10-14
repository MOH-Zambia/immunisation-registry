<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $users = User::select(DB::raw("COUNT(id) AS count"))
            ->whereYear('create_at', date('Y'))
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->pluck('count');

        $months = User::select(DB::raw("MONTH(created_at) AS month"))
            ->whereYear('create_at', date('Y'))
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->pluck('month');

        $data = array_combine($users, $months);

        return view('admin')
            ->with('data', $data);
    }
}
