<?php

namespace App\Http\Controllers;

use App\Http\Repository\Contact\IContactRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function sendMessage(Request $request, IContactRepository $contactRepository): JsonResponse
    {
        $data = $request->all();

        $contact = $contactRepository->saveMessage([
            IContactRepository::SAVE_MESSAGE_NAME => $data['name'],
            IContactRepository::SAVE_MESSAGE_EMAIL => $data['email'],
            IContactRepository::SAVE_MESSAGE_PHONE => $data['phone'],
            IContactRepository::SAVE_MESSAGE_MESSAGE => $data['message'],
            IContactRepository::SAVE_MESSAGE_IP => $data['ip'],
            IContactRepository::SAVE_MESSAGE_FILE_PATH => uniqid() . '.jpg',
        ]);

        return is_null($contact) ? response()->json([
            'error' => 'nulo',
            'message' => $request->all(),
        ]) : response()->json([
            'id' => $contact->id,
            'message' => $request->all(),
        ]);
    }
}
