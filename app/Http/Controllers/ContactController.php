<?php

namespace App\Http\Controllers;

use App\Builder\IJsonResponseBuilder;
use App\Repository\Contact\IContactRepository;
use App\Mail\ContactMessage;
use App\Service\Mail\IMailService;
use App\Service\Mail\MailException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PDOException;

class ContactController extends Controller
{
    public function sendMessage(
        Request $request,
        IContactRepository $contactRepository,
        IMailService $mailService,
        IJsonResponseBuilder $responseBuilder
    ): JsonResponse {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255'],
            'phone' => ['required', 'regex:/(\(?\d{2}\)?\s)?(\d{4,5}\-?\d{4})/i', 'string', 'max:15'],
            'message' => ['required'],
            'ip' => ['required', 'regex:/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/i', 'string', 'max:15'],
            'file' => ['required', 'mimes:pdf,doc,docx,odt,txt', 'file', 'max:500'],
        ]);

        $fileExtension = $request->file('file')->getClientOriginalExtension();
        $fileName = substr(
            $request->file('file')->getClientOriginalName(),
            0,
            (strlen($fileExtension) + 1) * (-1)
        );
        $completeFileName = "{$fileName}_" . uniqid() . ".{$fileExtension}";

        $completeFileNameLength = strlen($completeFileName);
        if ($completeFileNameLength > 255) {
            $start = $completeFileNameLength - 255;
            $completeFileName = substr($completeFileName, $start);
        }

        $contact = $contactRepository->saveMessage([
            IContactRepository::SAVE_MESSAGE_NAME => $data['name'],
            IContactRepository::SAVE_MESSAGE_EMAIL => $data['email'],
            IContactRepository::SAVE_MESSAGE_PHONE => $data['phone'],
            IContactRepository::SAVE_MESSAGE_MESSAGE => $data['message'],
            IContactRepository::SAVE_MESSAGE_IP => $data['ip'],
            IContactRepository::SAVE_MESSAGE_FILE_PATH => $completeFileName,
        ]);

        if (is_null($contact)) {
            throw new PDOException('Cannot save the message in database.');
        }

        $request->file('file')->storeAs('./contact', $completeFileName);

        if (is_null($mailService->sendEmail([env('MAIL_TO_ADDRESS')], new ContactMessage($contact)))) {
            throw new MailException('Error to send e-mail');
        }

        return $responseBuilder->build(
            false,
            'Comunicado enviado com sucesso!',
            null,
            null,
            null,
            200
        );
    }
}
