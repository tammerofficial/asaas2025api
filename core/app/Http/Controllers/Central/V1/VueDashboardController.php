<?php

namespace App\Http\Controllers\Central\V1;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class VueDashboardController extends Controller
{
    /**
     * Show the Vue.js Dashboard V1
     */
    public function index(): View
    {
        return view('central.v1.dashboard');
    }
}

