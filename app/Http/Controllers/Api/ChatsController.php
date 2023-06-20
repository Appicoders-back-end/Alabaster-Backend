<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chatlist;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ChatsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = request()->user();
        $baseChatlist = Chatlist::with(['to_user', 'from_user'])
            ->where(function ($q) use ($user) {
                $q->where('from_user_id', $user->id)->orWhere('to_user_id', $user->id);
            });
        // ->orWhere(function ($q) use ($user) {
        //     $q->where('to_user_id', $user->id);
        // });

        if (isset($request->search) && $request->search != null) {
            $search = $request->search;
            $baseChatlist = $baseChatlist->whereHas('to_user', function ($toUser) use ($search) {
                $toUser->where('name', 'like', '%' . $search . '%')->where('id', '!=', auth()->user()->id);
            })->orWherehas('from_user', function ($fromUser) use ($search) {
                $fromUser->where('name', 'like', '%' . $search . '%')->where('id', '!=', auth()->user()->id);
            });
        }

        $chatlist = $baseChatlist->orderBy('created_at', 'DESC')->simplePaginate(10);
        return apiresponse(true, 'Chatlist', $chatlist);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
//        dd(env('PUSHER_APP_ID'), env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_CLUSTER'));
        $user = request()->user();
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:text,photo,audio,video,document',
        ]);

        if ($validator->fails())
            return apiresponse(false, implode("\n", $validator->errors()->all()), null, 400);

        try {

            $user_id = $request->user_id;
            $chatlist = Chatlist::where(function ($q) use ($user_id, $user) {
                $q->where('from_user_type', 'App\Models\User')
                    ->where('from_user_id', $user_id)
                    ->where('to_user_type', 'App\Models\User')
                    ->where('to_user_id', $user->id);
            })->orWhere(function ($q) use ($user_id, $user) {
                $q->where('to_user_type', 'App\Models\User')
                    ->where('to_user_id', $user_id)
                    ->where('from_user_type', 'App\Models\User')
                    ->where('from_user_id', $user->id);
            })->first();
            if (!$chatlist) {
                $chatlist = Chatlist::create([
                    'from_user_type' => 'App\Models\User',
                    'from_user_id' => $user->id,
                    'to_user_type' => 'App\Models\User',
                    'to_user_id' => $user_id
                ]);
            }
            $messageData = [
                'chatlist_id' => $chatlist->id,
                'type' => $request->type,
                'sent_from_type' => 'App\Models\User',
                'sent_from_id' => $user->id,
                'image' => $request->image,
            ];
            //public_path('images')
            if ($chatlist->from_user_type == "App\Models\User" && $chatlist->from_user_id == $user->id) {
                $messageData['sent_to_type'] = $chatlist->to_user_type;
                $messageData['sent_to_id'] = $chatlist->to_user_id;
            } else if ($chatlist->to_user_type == "App\Models\User" && $chatlist->to_user_id == $user->id) {
                $messageData['sent_to_type'] = $chatlist->from_user_type;
                $messageData['sent_to_id'] = $chatlist->from_user_id;
            }
            if ($request->type == "photo" and $request->hasFile('media')) {
                $fileName = time() . '.' . $request->file('media')->getClientOriginalExtension();
                $request->file('media')->move("../public/storage/uploads", $fileName);
                $messageData['media'] = $fileName;
            } else {
                $messageData['message'] = $request->message;
            }

            if ($request->type == "video" and $request->hasFile('media')) {
                $fileName = time() . '.' . $request->file('media')->getClientOriginalExtension();
                $request->file('media')->move("../public/storage/uploads", $fileName);
                $messageData['media'] = $fileName;
            } else {
                $messageData['message'] = $request->message;
            }

            if ($request->type == "document" and $request->hasFile('media')) {
                $fileName = time() . '.' . $request->file('media')->getClientOriginalExtension();
                $request->file('media')->move("../public/storage/uploads", $fileName);
                $messageData['media'] = $fileName;
            } else {
                $messageData['message'] = $request->message;
            }

            if ($request->type == "audio" and $request->hasFile('media')) {
                $fileName = time() . '.' . $request->file('media')->getClientOriginalExtension();
                $request->file('media')->move("../public/storage/uploads", $fileName);
                $messageData['media'] = $fileName;
            } else {
                $messageData['message'] = $request->message;
            }

            $message = Message::create($messageData);

            $message = Message::find($message->id);
            broadcast(new \App\Events\Message($messageData['sent_to_type'], $message->chatlist->id, $message))->toOthers();

            /** @var \App\Models\Chatlist $msg */
            //        $msg    =   Chatlist::with(['customer', 'provider', 'services'])->findOrFail($id);
            $title = "You have a message from " . $request->user()->name;
            // $message   =   $message->message;

            SendNotification($message->sender_id, $title, $message);

            Notification::create([
                'send_to_type' => $message->sent_to_type,
                'reciever_id' => $message->sent_to_id,
                'sender_id' => $message->sent_from_id,
                'title' => $title,
                'message' => $message->message,
                'is_read' => 0
            ]);

            triggerUnreadNotificationEvent($message->sent_to_id);

            return apiresponse(true, 'Message Sent', $message);

        } catch (\Exception $exception) {
            return apiresponse(false, $exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $messages = Message::where(['chatlist_id' => $id])->orderBy('created_at', 'DESC')->simplePaginate(10);
        if ($messages) {
            return apiresponse(true, 'Messages Found', $messages);
        } else {
            return apiresponse(false, 'Messages Not Found');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkSessionBeforeMessage(Request $request)
    {
        $chathead = Chatlist::where(DB::raw("(from_user_id  =  " . Auth::user()->id . " AND to_user_id  = $request->id) or (from_user_id  = $request->id AND to_user_id  = " . Auth::user()->id . ")"), '>', DB::raw('0'))
            ->first();
        if (empty($chathead)) {

            $chathead = Chatlist::create([
                "from_user_id" => Auth::user()->id,
                "to_user_id" => $request->id,
                "from_user_type" => "App\Models\User",
                "to_user_type" => "App\Models\User",
            ]);

        } else {

            $chathead = Chatlist::where('id', $chathead->id)->update([
                "from_user_id" => Auth::user()->id,
                "to_user_id" => $request->id,
                "from_user_type" => "App\Models\User",
                "to_user_type" => "App\Models\User",
            ]);

            $chathead = Chatlist::where(DB::raw("(from_user_id  =  " . Auth::user()->id . " AND to_user_id  = $request->id) or (from_user_id  = $request->id AND to_user_id  = " . Auth::user()->id . ")"), '>', DB::raw('0'))
                ->first();
        }

        $chathead->from_user = $chathead->from_user;
        $chathead->to_user = $chathead->to_user;
        return apiresponse(true, 'chathead', $chathead);
    }
}
