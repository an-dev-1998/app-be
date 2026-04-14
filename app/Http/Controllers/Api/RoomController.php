<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $memberRoomIds = $user->rooms()->pluck('rooms.id');

        return Room::with('members:id,name,email')
            ->latest()
            ->get()
            ->map(function (Room $room) use ($memberRoomIds) {
                $room->is_member = $memberRoomIds->contains($room->id);
                return $room;
            });
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $room = Room::create([
            'name' => $validated['name'],
            'type' => 'group',
        ]);

        $room->members()->attach($request->user()->id);
        $room->load('members:id,name,email');
        $room->is_member = true;

        return response()->json($room, 201);
    }

    public function join(Request $request, Room $room)
    {
        $room->members()->syncWithoutDetaching([$request->user()->id]);
        $room->load('members:id,name,email');
        $room->is_member = true;

        return response()->json($room);
    }
}
