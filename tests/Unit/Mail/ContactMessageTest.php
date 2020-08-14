<?php

namespace Tests\Unit\Mail;

use App\Mail\ContactMessage;
use App\Model\Contact;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    protected Contact $contact;

    public function setUp(): void
    {
        parent::setUp();

        $this->contact = $this->mock('alias:' . Contact::class);

        /** @var Contact $contact */
        $this->contact->name = 'John Doe';
        $this->contact->email = 'john.doe@mail.com';
        $this->contact->phone = '(12) 98100-0000';
        $this->contact->ip = '192.168.0.1';
        $this->contact->message = 'Testing';
    }

    public function testBuildMessageHtmlRender(): void
    {
        $contactMessage = new ContactMessage($this->contact);

        $actual = $contactMessage->build()->render();
        $expected = view('emails.contact.message', ['contact' => $this->contact])->render();

        $this->assertEquals($expected, $actual);
    }

    public function testBuildMessageReturn(): void
    {
        $contactMessage = new ContactMessage($this->contact);

        $actual = $contactMessage->build();
        $expected = ContactMessage::class;

        $this->assertInstanceOf($expected, $actual);
    }
}
