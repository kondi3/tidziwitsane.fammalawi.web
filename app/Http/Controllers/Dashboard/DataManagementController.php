<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Response;

class DataManagementController extends Controller
{
    public function index() : Response
    {
        return inertia('Dashboard/Settings/DataManagement');
    }
}
