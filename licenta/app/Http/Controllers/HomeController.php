<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    public function markNotificationAsRead(Request $request)
    {
        $request->validate([
            'notification' => 'required',
        ]);

        $notifications = auth()->user()->unreadNotifications;

        try{
            if ($request->input('notification') === 'all') {
                $notifications->each(function($notification){
                    $notification->markAsRead();
                });
            } else {
                $notifications->where('id', $request->input('notification'))->markAsRead();
            }

            return response()->json(['success' => true]);
        }catch(\Exception $e){
            return Response::json(['error' => 'Whoops, something went wrong!'],500);
        }
    }
}
