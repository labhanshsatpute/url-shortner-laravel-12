<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    // View Dashboard Page
    public function viewDasboard(): View {
        return view('pages.dashboard.dashboard');
    }
}
