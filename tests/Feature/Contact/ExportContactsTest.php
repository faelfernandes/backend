<?php

use App\Models\Contact;
use App\Models\Address;

it('deve exportar contatos selecionados para CSV', function () {
    $contacts = Contact::factory()
        ->has(Address::factory())
        ->count(3)
        ->create();

    $response = $this->post('/api/contacts/export', [
        'contacts' => $contacts->pluck('id')->toArray()
    ]);

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
});

it('deve validar IDs de contatos inexistentes na exportação', function () {
    $response = $this->post('/api/contacts/export', [
        'contacts' => [999, 888, 777]
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['contacts.0', 'contacts.1', 'contacts.2']);
});
