<?php

namespace App\Http\DAO\Contact;

use App\Model\Contact;

class ContactDAO extends IContactDAO
{
    /**
     * @inheritDoc
     */
    public function saveMessage(array $message): ?Contact
    {
        $contact = new Contact();
        $contact->name = $message[IContactDAO::SAVE_MESSAGE_NAME];
        $contact->email = $message[IContactDAO::SAVE_MESSAGE_EMAIL];
        $contact->phone = $message[IContactDAO::SAVE_MESSAGE_PHONE];
        $contact->message = $message[IContactDAO::SAVE_MESSAGE_MESSAGE];
        $contact->file_path = $message[IContactDAO::SAVE_MESSAGE_FILE_PATH];
        $contact->ip = $message[IContactDAO::SAVE_MESSAGE_IP];

        return $contact->save() ? $contact : null;
    }
}
