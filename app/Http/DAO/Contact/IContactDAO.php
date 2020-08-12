<?php

namespace App\Http\DAO\Contact;

use App\Model\Contact;

interface IContactDAO
{
    public const SAVE_MESSAGE_NAME = 'name';
    public const SAVE_MESSAGE_EMAIL = 'email';
    public const SAVE_MESSAGE_PHONE = 'phone';
    public const SAVE_MESSAGE_MESSAGE = 'message';
    public const SAVE_MESSAGE_FILE_PATH = 'file_path';
    public const SAVE_MESSAGE_IP = 'ip';

    /**
     * @param array<IContactDAO::SAVE_MESSAGE_*, string> $message
     * @return Contact|null
     */
    public function saveMessage(array $message): ?Contact;
}
