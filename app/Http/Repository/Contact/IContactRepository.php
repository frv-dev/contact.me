<?php

namespace App\Http\Repository\Contact;

use App\Model\Contact;

interface IContactRepository
{
    public const SAVE_MESSAGE_NAME = 'name';
    public const SAVE_MESSAGE_EMAIL = 'email';
    public const SAVE_MESSAGE_PHONE = 'phone';
    public const SAVE_MESSAGE_MESSAGE = 'message';
    public const SAVE_MESSAGE_FILE_PATH = 'file_path';
    public const SAVE_MESSAGE_IP = 'ip';

    /**
     * Save the message and return a Contact object on success and null on error.
     *
     * @param array<IContactRepository::SAVE_MESSAGE_*, string> $message
     * @return Contact|null
     */
    public function saveMessage(array $message): ?Contact;
}
