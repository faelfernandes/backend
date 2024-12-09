<?php

use App\Models\Contact;
use App\Models\Address;

it('deve excluir um contato e seu endereço', function () {
    $contact = Contact::factory()
        ->has(Address::factory())
        ->create();

    $response = $this->delete("/api/contacts/{$contact->id}");

    $response->assertStatus(200);
    $response->assertJson(['message' => 'Contato excluído com sucesso']);
    $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    $this->assertDatabaseMissing('addresses', ['contact_id' => $contact->id]);
});

it('deve retornar erro ao tentar excluir contato inexistente', function () {
    $response = $this->delete('/api/contacts/9999');

    $response->assertStatus(404);
    $response->assertJson(['error' => 'Contato não encontrado.']);
});
