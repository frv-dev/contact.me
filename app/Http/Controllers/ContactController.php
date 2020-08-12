<?php

namespace App\Http\Controllers;

use App\Http\Repository\Contact\IContactRepository;
use App\Mail\ContactMessage;
use App\Service\Mail\IMailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function sendMessage(
        Request $request,
        IContactRepository $contactRepository,
        IMailService $mailService
    ): JsonResponse {
        $data = $request->all();

        $contact = $contactRepository->saveMessage([
            IContactRepository::SAVE_MESSAGE_NAME => $data['name'],
            IContactRepository::SAVE_MESSAGE_EMAIL => $data['email'],
            IContactRepository::SAVE_MESSAGE_PHONE => $data['phone'],
            IContactRepository::SAVE_MESSAGE_MESSAGE => $data['message'],
            IContactRepository::SAVE_MESSAGE_IP => $data['ip'],
            IContactRepository::SAVE_MESSAGE_FILE_PATH => uniqid() . '.jpg',
        ]);

        if (is_null($contact)) {
            return response()->json([
                'error' => 'nulo',
                'message' => $request->all(),
            ]);
        }

        $mailService->sendEmail([env('MAIL_FROM_ADDRESS')], new ContactMessage($contact));

        return response()->json([
            'id' => $contact->id,
            'message' => $request->all(),
        ]);
    }
}
