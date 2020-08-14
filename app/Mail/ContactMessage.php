<?php

namespace App\Mail;

use App\Model\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable;
    use SerializesModels;

    public Contact $contact;
    private string $pathToFile;
    private string $fileName;
    private string $mimeType;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Contact $contact, string $pathToFile, string $fileName, string $mimeType)
    {
        $this->contact = $contact;
        $this->pathToFile = $pathToFile;
        $this->fileName = $fileName;
        $this->mimeType = $mimeType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contact.message')
            ->text('emails.contact.message_plain')
            ->attachFromStorage($this->pathToFile, $this->fileName, [
                'mime' => $this->mimeType,
            ]);
    }
}
