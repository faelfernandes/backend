<?php

use App\Models\Contact;
use App\Models\Address;

it('deve criar um novo contato com endereço', function () {
    $contact = Contact::factory()->make();
    $address = Address::factory()->make();

    $data = [
        'name' => $contact->name,
        'email' => $contact->email,
        'phone' => $contact->phone,
        'address' => [
            'zip_code' => $address->zip_code,
            'state' => $address->state,
            'city' => $address->city,
            'neighborhood' => $address->neighborhood,
            'street' => $address->street,
            'number' => $address->number
        ]
    ];

    $response = $this->post('/api/contacts', $data);

    $response->assertStatus(201);
    $this->assertDatabaseHas('contacts', [
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone']
    ]);
    $this->assertDatabaseHas('addresses', $data['address']);
});

it('deve validar campos obrigatórios na criação', function () {
    $response = $this->post('/api/contacts', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([
        'name',
        'email',
        'phone',
        'address.zip_code',
        'address.state',
        'address.city',
        'address.neighborhood',
        'address.street',
        'address.number'
    ]);
});
