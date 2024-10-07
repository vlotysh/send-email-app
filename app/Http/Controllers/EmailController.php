<?php

namespace App\Http\Controllers;

use App\Enums\EmailStatus;
use App\Models\Email;
use Illuminate\Http\Request;
use App\Jobs\SendEmailJob;

class EmailController extends Controller
{
    public function store(Request $request)
    {
        /** @var Email $email */
        $email = Email::create(array_merge($request->all(), ['status' => EmailStatus::PENDING()]));
        if ($request->has('send_now') && $request->send_now) {
            SendEmailJob::dispatch($email);
        }
        return response()->json($email, 201);
    }

    public function index()
    {
        $emails = Email::all();
        return response()->json($emails);
    }

    public function sendNow(Request $request, $id)
    {
        /** @var Email $email */
        $email = Email::findOrFail($id);
        SendEmailJob::dispatch($email);

        return response()->json(['status' => 'ok']);
    }

    public function update(Request $request, $id)
    {
        /** @var Email $email */
        $email = Email::findOrFail($id);
        if ($email->status === EmailStatus::PENDING()) {
            $email->update($request->all());
            return response()->json($email);
        }
        return response()->json(['error' => 'Email already sent'], 400);
    }

    public function delete(Request $request, $id)
    {
        /** @var Email $email */
        $email = Email::findOrFail($id);
        $email->delete();
        return response()->json(['result' => 'Email has been deleted'], 204);
    }
}
