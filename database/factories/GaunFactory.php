<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GaunFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'kode' => '01 BM 1', 
            'gambar' => 'default.png', 
            'warna' => 'Baby Blue', 
            'harga' => 700000
        ];
    }
}
