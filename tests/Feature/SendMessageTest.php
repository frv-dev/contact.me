<?php

namespace Tests\Feature;

use App\Model\Contact;
use App\Repository\Contact\IContactRepository;
use App\Service\Mail\IMailService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SendMessageTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $contact = $this->mock('alias:' . Contact::class);
        /** @var Contact $contact */
        $contact->name = 'John Doe';
        $contact->email = 'john.doe@mail.com';
        $contact->phone = '(12) 98100-0000';
        $contact->ip = '192.168.0.1';
        $contact->message = 'Testing';

        $contactRepository = $this->mock(IContactRepository::class);
        $contactRepository->shouldReceive('saveMessage')
            ->andReturn($contact);
        $this->instance(IContactRepository::class, $contactRepository);

        $mailService = $this->mock(IMailService::class);
        $mailService->shouldReceive('sendEmail')
            ->andReturn(true);
        $this->instance(IMailService::class, $mailService);
    }

    public function testSendMessage(): void
    {
        $response = $this->post('/api/send-message', [
            'name' => 'John Doe',
            'email' => 'john.doe@mail.com',
            'phone' => '(12) 98100-0000',
            'message' => 'Testing.',
            'file' => UploadedFile::fake()->createWithContent('500_exact.txt', Storage::get('500_exact.txt')),
            'ip' => '192.168.0.1'
        ]);

        $response->assertStatus(200);
    }

    public function testSendMessageLargerFile(): void
    {
        $response = $this->post('/api/send-message', [
            'name' => 'John Doe',
            'email' => 'john.doe@mail.com',
            'phone' => '(12) 98100-0000',
            'message' => 'Testing.',
            'file' => UploadedFile::fake()->createWithContent('500_exact.txt', Storage::get('500_plus.txt')),
            'ip' => '192.168.0.1'
        ]);

        $response->assertStatus(400);
    }

    public function testSendMessageInvalidPhone(): void
    {
        $response = $this->post('/api/send-message', [
            'name' => 'John Doe',
            'email' => 'john.doe@mail.com',
            'phone' => '(12) 98100-00000',
            'message' => 'Testing.',
            'file' => UploadedFile::fake()->createWithContent('500_exact.txt', Storage::get('500_exact.txt')),
            'ip' => '192.168.0.1'
        ]);

        $response->assertStatus(400);
    }

    public function testSendMessageInvalidIp(): void
    {
        $response = $this->post('/api/send-message', [
            'name' => 'John Doe',
            'email' => 'john.doe@mail.com',
            'phone' => '(12) 98100-0000',
            'message' => 'Testing.',
            'file' => UploadedFile::fake()->createWithContent('500_exact.txt', Storage::get('500_exact.txt')),
            'ip' => '192.168.0.1.1'
        ]);

        $response->assertStatus(400);
    }

    public function testSendMessageInvalidEmail(): void
    {
        $response = $this->post('/api/send-message', [
            'name' => 'John Doe',
            'email' => 'john.doe@mail.',
            'phone' => '(12) 98100-0000',
            'message' => 'Testing.',
            'file' => UploadedFile::fake()->createWithContent('500_exact.txt', Storage::get('500_exact.txt')),
            'ip' => '192.168.0.1'
        ]);

        $response->assertStatus(400);
    }
}
