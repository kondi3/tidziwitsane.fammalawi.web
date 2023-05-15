<?php

use App\Models\Dashboard\Chat;
use App\Models\Dashboard\FAQ;
use App\Models\Dashboard\Message;
use App\Models\Dashboard\Myth;
use App\Models\Dashboard\ServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    // authentication
    Route::get('/handshake', function () {
        $user = User::create([
            'uuid' => uniqid(),
            'type' => User::TYPE_ANONYMOUS,
        ]);

        $token = $user->createToken($user->uuid)->plainTextToken;

        return response()->json([
            'uuid' => $user->uuid,
            'token' => $token
        ]);
    });

    Route::post('/login', function (Request $request) {
        $request->validate([
            'uuid' => 'required|exists:users'
        ]);

        $user = User::query()->where('uuid', $request->uuid)->first()->getModel();

        $user->tokens()->delete();

        $token = $user->createToken($request->uuid)->plainTextToken;

        return response()->json([
            'uuid' => $request->uuid,
            'token' => $token
        ]);
    });


    // 
    Route::middleware('auth:sanctum')->group(function () {
        // configurations
        Route::get('/config', function () {
            return response()->json([
                'key' => env('PUSHER_APP_KEY', null),
                'cluster' => env('PUSHER_APP_CLUSTER', null),
                'host' => env('PUSHER_APP_HOST', null),
                'port' => env('PUSHER_APP_PORT', null),
                'scheme' => env('PUSHER_APP_SCHEME', null),
                'forceTLS' => (env('PUSHER_APP_SCHEME') ?? 'https') === 'https',
            ]);
        });

        // get faqs
        Route::get('/faqs', function () {
            return FAQ::query()->latest()->paginate(30);
        });

        // get myths
        Route::get('/myths', function () {
            return Myth::query()->latest()->paginate(30);
        });

        // get service providers
        Route::get('/providers', function () {
            return ServiceProvider::query()->latest()->paginate(30);
        });

        // get users messages
        Route::get('/messages/{user:uuid}', function (User $user) {
            return $user->chat?->messages ?? [];
        });

        // send a message
        Route::post('/messages/{user:uuid}', function (Request $request, User $user) {
            $request->validate([
                'message' => 'required'
            ]);

            // check if user already has a chat and append to it else create a new one
            if ($user->chat === null) {
                $user->chat()->create([
                    'uid' => uniqid(),
                    'name' => 'Chat #' . Chat::query()->count()+1,
                ]);

                $user->refresh();
            }

            $user->chat->messages()->create([
                'user_id' => auth()->id(),
                'message' => $request->message,
                'status' => Message::STATUS_SENT,
            ]);

            return response()->json();
        });
    });
});