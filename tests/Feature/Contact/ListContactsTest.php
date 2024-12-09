<?php

use App\Models\Contact;
use App\Models\Address;

it('deve listar contatos paginados', function () {
    Contact::factory()
        ->has(Address::factory())
        ->count(3)
        ->create();

    $response = $this->get('/api/contacts?per_page=2');

    $response->assertStatus(200);
    $response->assertJsonCount(2, 'data');
    $response->assertJsonStructure([
        'data' => [
            '*' => [
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
            ]
        ],
        'first_page_url',
        'from',
        'last_page',
        'last_page_url',
        'links' => [
            '*' => [
                'url',
                'label',
                'active'
            ]
        ],
        'next_page_url',
        'path',
        'per_page',
        'prev_page_url',
        'to',
        'total'
    ]);
});

it('deve retornar lista vazia quando não houver contatos', function () {
    $response = $this->get('/api/contacts');

    $response->assertStatus(200);
    $response->assertJsonCount(0, 'data');
});

it('deve respeitar o limite de itens por página', function () {
    Contact::factory()
        ->has(Address::factory())
        ->count(5)
        ->create();

    $perPage = 3;
    $response = $this->get("/api/contacts?per_page={$perPage}");

    $response->assertStatus(200);
    $response->assertJsonCount($perPage, 'data');
    $response->assertJson([
        'per_page' => $perPage,
        'total' => 5
    ]);
});

it('deve filtrar contatos corretamente', function () {
    Contact::factory()
        ->has(Address::factory()->state(['state' => 'SP', 'city' => 'São Paulo']))
        ->create(['name' => 'João Silva']);

    Contact::factory()
        ->has(Address::factory()->state(['state' => 'RJ', 'city' => 'Rio de Janeiro']))
        ->create(['name' => 'Maria Santos']);

    $response = $this->get('/api/contacts?search=João');
    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.name', 'João Silva');

    $response = $this->get('/api/contacts?state=SP');
    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.address.state', 'SP');

    $response = $this->get('/api/contacts?city=Rio');
    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.address.city', 'Rio de Janeiro');

    $response = $this->get('/api/contacts?state=SP&city=São Paulo');
    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.address.state', 'SP');
    $response->assertJsonPath('data.0.address.city', 'São Paulo');
});
