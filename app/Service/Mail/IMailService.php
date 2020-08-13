<?php

namespace App\Service\Mail;

use Illuminate\Mail\Mailable;

interface IMailService
{
    /**
     * Send an e-mail
     *
     * @param array $to
     * @param Mailable $email
     * @return bool|null
     */
    public function sendEmail(array $to, Mailable $email): ?bool;
}
