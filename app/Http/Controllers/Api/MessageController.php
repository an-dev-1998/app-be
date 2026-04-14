<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $roomId = $request->room_id;
        $afterId = $request->after_id;

        return Message::with('user:id,name')
            ->where('room_id', $roomId)
            ->when($afterId, function ($q) use ($afterId) {
                $q->where('id', '>', $afterId);
            })
            ->orderBy('id')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'content' => 'required|string',
        ]);

        return Message::create([
            'room_id' => $validated['room_id'],
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
        ])->load('user:id,name');
    }
}
