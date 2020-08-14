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
        /**
         * EMBORA NÃO SEJA IDEAL COLOCAR COMENTÁRIOS COMO ESTE NO MEIO
         * DO CÓDIGO, ESTE COMENTÁRIO ESTÁ RELACIONADO AO TESTE TÉCNICO
         * PROPOSTO PELA EMPRESA, NESTE FOI INFORMADO QUE DEVERIA SER
         * ENVIADO UM ARQUIVO DE, NO MÁXIMO, 500kb COM b MINÚSCULO,
         * POR NÃO SABER SE FOI UM ERRO OU PROPOSITAL EU CONSIDEREI
         * KILOBITS (Kb) E NÃO KILOBYTES (KB), LOGO O ARQUIVO TERÁ NO MÁXIMO
         * O SEGUINTE VALOR:
         *
         * 500Kb / 8 = 62.5KB
         * 62.5KB * 1024 = 64000B
         *
         * SEGUE ESTÁ INFORMAÇÃO PARA IDENTIFICAR QUE, CASO SEJA KILOBYTES,
         * FOI UMA ESCOLHA PARA SEGUIR O QUE O TESTE TÉCNICO ESTAVA PEDINDO
         * E NÃO UM ERRO DE CÁLCULO.
         *
         * NOS TESTES UNITÁRIOS DO PHP UNIT TEM UM TESTE PARA O ENVIO E
         * VALIDAÇÃO DO ARQUIVO COM 64000B E 64001B NO QUAL É POSSÍVEL
         * VERIFICAR QUE O LIMITE REALMENTE É DE 64000B.
         *
         * ATT: FELIPE RENAN VIEIRA
         */
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^(\(?\d{2}\)?\s)?(\d{4,5}\-?\d{4})$/i', 'string', 'max:15'],
            'message' => ['required'],
            'ip' => ['required', 'regex:/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/i', 'string', 'max:15'],
            'file' => ['required', 'mimes:pdf,doc,docx,odt,txt', 'file', 'max:62.5'],
        ]);

        $fileExtension = $request->file('file')->getClientOriginalExtension();
        $mimeType = $request->file('file')->getMimeType();
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
        $pathToFile = "./contact/{$completeFileName}";

        if (
            is_null($mailService->sendEmail(
                [env('MAIL_TO_ADDRESS')],
                new ContactMessage($contact, $pathToFile, $completeFileName, $mimeType)
            ))
        ) {
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
