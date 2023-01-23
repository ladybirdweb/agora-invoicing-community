<?php

namespace App\Http\Controllers;


use App\Http\Requests\AnnouncementRequest;
use App\Model\Common\Announcement as Announce;
use App\Jobs\Announcement;

class MessagingController extends Controller
{
    public function sendMessage(AnnouncementRequest $request){
        try {
            $user = $request->user;
            $message = $request->message;
            $messageType = $request->is_closeable;
            $from = $request->from;
            $till = $request->till;
            $daysReappear = $request->reappear;
            $condition = $request->condition;
            Announce::create(
                    ['user'=> $user, 'message'=> $message,
                    'is_closeable' => $messageType,
                    'version'=> implode(",", $request->version),
                    'from'=> $from,
                    'till'=> $till,
                    'products'=> implode(",", $request->products),
                    'reappear'=> $daysReappear, 'condition' => $condition]
            );
            Announcement::dispatch($message,$messageType,$request->version,$request->products,$from,$till,$daysReappear,$condition);
            return redirect()->back()->with('success', trans('message.announce_in_progress'));
        }
        Catch(\Exception $e){
            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function announcementPage(){
        return view('themes.default1.common.messaging');
    }

}