<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dashboard\FAQ;
use App\Models\Dashboard\Myth;
use App\Models\Dashboard\ServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class DataManagementController extends Controller
{
    public function index(): Response
    {
        return inertia('Dashboard/Settings/DataManagement');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => 'required|file',
            'belongs_to' => 'required'
        ]);

        // get file
        $file = $request->file('csv_file');

        // get file handle
        $fh = fopen($file->path(), 'r');

        // loop through contents
        // check where it belongs to and create a new record
        while (($line = fgetcsv($fh))) {
            // check if its header
            $belongs_to = strtolower($request->belongs_to);

            if ($belongs_to === 'myths') {
                [$myth, $fact] = $line;

                if (in_array(strtolower($myth), ['myth', 'myths', 'misconception', 'misconceptions'])) {
                    continue;
                } else {
                    Myth::create([
                        'user_id' => auth()->id(),
                        'myth' => $myth,
                        'fact' => $fact
                    ]);
                }
            } else if ($belongs_to === 'providers') {
                [$provider, $action, $args] = $line;

                if (in_array(strtolower($provider), ['service provider', 'service providers', 'services prover', 'services providers', 'service', 'services'])) {
                    continue;
                } else {
                    ServiceProvider::create([
                        'user_id' => auth()->id(),
                        'provider' => $provider,
                        'action' => $action,
                        'args' => $args
                    ]);
                }
            } else if ($belongs_to === 'faqs') {
                [$question, $answer] = $line;

                if (in_array(strtolower($question), ['question', 'questions'])) {
                    continue;
                } else {
                    FAQ::create([
                        'user_id' => auth()->id(),
                        'question' => $question,
                        'answer' => $answer
                    ]);
                }
            }
        }

        return back();
    }
}
