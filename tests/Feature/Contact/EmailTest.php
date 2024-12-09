<?php

use App\Models\Contact;
use App\Models\Address;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactCreatedMail;

beforeEach(function () {
    Mail::fake();
});

it('deve enfileirar email quando contato for criado', function () {
    $contact = Contact::factory()->make();
    $address = Address::factory()->make();

    $data = [
        'name' => $contact->name,
        'email' => $contact->email,
        'phone' => $contact->phone,
        'address' => $address->toArray()
    ];

    $response = $this->post('/api/contacts', $data);

    $response->assertStatus(201);
    Mail::assertQueued(ContactCreatedMail::class, function ($mail) {
        return $mail->queue === 'back_emails';
    });
});

it('deve enviar email com dados corretos do contato', function () {
    $contact = Contact::factory()->make();
    $address = Address::factory()->make();

    $data = [
        'name' => $contact->name,
        'email' => $contact->email,
        'phone' => $contact->phone,
        'address' => $address->toArray()
    ];

    $this->post('/api/contacts', $data);

    Mail::assertQueued(ContactCreatedMail::class, function ($mail) use ($data) {
        return $mail->contact->name === $data['name'] &&
            $mail->contact->email === $data['email'];
    });
});
