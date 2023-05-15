<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dashboard\Message;
use App\Models\Download;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request) : Response
    {
        $year = $request->year ?? (int) date('Y');
        $totals = [];

        $totals['downloads'] = Download::query()->count();
        $totals['installations'] = User::query()->where('type', User::TYPE_ANONYMOUS)->count();

        $users = User::query()->where('type', User::TYPE_ANONYMOUS)->withCount('messages')->get();
        $count = 0;

        foreach($users as $user) {
            $count += $user->messages_count;
        }

        $totals['messages'] = $count;

        $annual = [];

        $annual['downloads'] = Download::query()->whereYear('created_at', $year)->get(['id']);
        $annual['installations'] = User::query()->where('type', User::TYPE_ANONYMOUS)->whereYear('created_at', $year)->get();
        $annual['messages'] = Message::query()->with('user')->whereYear('created_at', $year)->get();

        return inertia('Dashboard/Index', ['totals' => $totals, 'annual' => $annual]);
    }
}
