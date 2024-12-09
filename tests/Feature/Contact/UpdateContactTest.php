<?php

use App\Models\Contact;
use App\Models\Address;

it('deve atualizar um contato existente', function () {
    $contact = Contact::factory()
        ->has(Address::factory())
        ->create();

    $newData = [
        'name' => 'Novo Nome',
        'email' => 'novo@email.com',
        'phone' => '11999999999',
        'address' => [
            'zip_code' => '12345678',
            'state' => 'SP',
            'city' => 'São Paulo',
            'neighborhood' => 'Centro',
            'street' => 'Rua Nova',
            'number' => '100'
        ]
    ];

    $response = $this->put("/api/contacts/{$contact->id}", $newData);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'id',
        'name',
        'email',
        'phone',
        'created_at',
        'updated_at',
        'address' => [
            'id',
            'contact_id',
            'zip_code',
            'state',
            'city',
            'neighborhood',
            'street',
            'number',
            'created_at',
            'updated_at'
        ]
    ]);

    $this->assertDatabaseHas('contacts', [
        'id' => $contact->id,
        'name' => $newData['name'],
        'email' => $newData['email'],
        'phone' => $newData['phone']
    ]);

    $updatedAddress = Address::where('contact_id', $contact->id)->first();
    $this->assertEquals($newData['address']['zip_code'], $updatedAddress->zip_code);
    $this->assertEquals($newData['address']['state'], $updatedAddress->state);
    $this->assertEquals($newData['address']['city'], $updatedAddress->city);
    $this->assertEquals($newData['address']['neighborhood'], $updatedAddress->neighborhood);
    $this->assertEquals($newData['address']['street'], $updatedAddress->street);
    $this->assertEquals($newData['address']['number'], $updatedAddress->number);
});

it('deve retornar erro ao atualizar contato inexistente', function () {
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

    $response = $this->put('/api/contacts/9999', $data);

    $response->assertStatus(404);
    $response->assertJson(['error' => 'Contato não encontrado.']);
});

it('deve validar campos obrigatórios na atualização', function () {
    $contact = Contact::factory()
        ->has(Address::factory())
        ->create();

    $response = $this->put("/api/contacts/{$contact->id}", []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([
        'name',
        'email',
        'phone',
        'address',
        'address.zip_code',
        'address.state',
        'address.city',
        'address.neighborhood',
        'address.street',
        'address.number'
    ]);
});

it('deve validar formato do email na atualização', function () {
    $contact = Contact::factory()
        ->has(Address::factory())
        ->create();

    $data = [
        'name' => 'Nome Teste',
        'email' => 'email-invalido',
        'phone' => '11999999999',
        'address' => [
            'zip_code' => '12345678',
            'state' => 'SP',
            'city' => 'São Paulo',
            'neighborhood' => 'Centro',
            'street' => 'Rua Teste',
            'number' => '123'
        ]
    ];

    $response = $this->put("/api/contacts/{$contact->id}", $data);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});
