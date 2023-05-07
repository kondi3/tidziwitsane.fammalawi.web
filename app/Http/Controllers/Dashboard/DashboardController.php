<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke() : Response
    {
        return inertia('Dashboard/Index');
    }
}
