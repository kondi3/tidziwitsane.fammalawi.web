<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dashboard\Chat;
use App\Models\Dashboard\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : Response
    {
        $chats = Chat::query()->with('messages')->latest()->paginate();

        return inertia('Dashboard/Chats/Index', ['chats' => $chats]);
    }

    /**
     * Store a new message
     */
    public function message(Request $request, Chat $chat) : RedirectResponse
    {
        $request->validate([
            'message' => 'required',
        ]);

        $chat->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'status' => Message::STATUS_SENT
        ]);

        return back();
    }
}
