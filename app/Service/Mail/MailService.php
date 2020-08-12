<?php

namespace App\Service\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class MailService implements IMailService
{
    /**
     * @inheritDoc
     */
    public function sendEmail(array $to, Mailable $email)
    {
        return Mail::to($to)->send($email);
    }
}
