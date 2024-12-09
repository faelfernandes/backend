<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        return [
            'zip_code' => str_replace('-', '', $this->faker->postcode()),
            'state' => $this->faker->stateAbbr(),
            'city' => $this->faker->city(),
            'neighborhood' => $this->faker->word(),
            'street' => $this->faker->streetAddress(),
            'number' => $this->faker->buildingNumber(),
        ];
    }
}
