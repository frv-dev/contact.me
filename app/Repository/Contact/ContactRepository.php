<?php

namespace App\Repository\Contact;

use App\Model\Contact;

class ContactRepository implements IContactRepository
{
    /**
     * @inheritDoc
     */
    public function saveMessage(array $message): ?Contact
    {
        $contact = new Contact();
        $contact->name = $message[IContactRepository::SAVE_MESSAGE_NAME];
        $contact->email = $message[IContactRepository::SAVE_MESSAGE_EMAIL];
        $contact->phone = $message[IContactRepository::SAVE_MESSAGE_PHONE];
        $contact->message = $message[IContactRepository::SAVE_MESSAGE_MESSAGE];
        $contact->file_path = $message[IContactRepository::SAVE_MESSAGE_FILE_PATH];
        $contact->ip = $message[IContactRepository::SAVE_MESSAGE_IP];

        return $contact->save() ? $contact : null;
    }
}
