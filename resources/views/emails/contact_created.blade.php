<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Contato Criado</title>
</head>
<body>
    <h1>Novo Contato Criado</h1>
    <p><strong>Nome:</strong> {{ $contact->name }}</p>
    <p><strong>Email:</strong> {{ $contact->email }}</p>
    <p><strong>Telefone:</strong> {{ $contact->phone }}</p>
    <p><strong>Endereço:</strong> {{ $contact->address->street }}, {{ $contact->address->number }} - {{ $contact->address->city }}, {{ $contact->address->state }}</p>
</body>
</html>
