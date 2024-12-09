<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Address;
use App\Mail\ContactCreatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Contact::factory()
            ->has(Address::factory())
            ->count(20)
            ->create()
            ->each(function ($contact) {
                Mail::to(env('NOTIFICATION_MAIL'))
                    ->queue(new ContactCreatedMail($contact));
            });
    }
}
