<?php

namespace App\Http\Controllers;

use App\Jobs\ContactTheFarmerJob;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;

class SendMailController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //        if(auth()->guest()) {
        //            return response()->json([
        //                'message' => 'You must be logged in to send an email.',
        //            ], 401);
        //        }

        $request->validate([
            'toMail' => 'required|email|exists:users,email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $toUser = User::where('email', $request->input('toMail'))->first();

        Contact::create([
            'user_id' => $toUser->id,
            'message' => $request->input('message'),
            'subject' => $request->input('subject'),
        ]);

        dispatch(new ContactTheFarmerJob(
            subjectFor: $request->input('subject'),
            message: $request->input('message'),
            emilTo: $request->input('toMail'),
            from: $request->input('from') ?? auth()?->user()?->email,
        ));

        return response()->json([
            'message' => 'Email sent successfully!',
        ]);

    }
}
