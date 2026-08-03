<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $cars = [
            [
                'brand' => 'Ford',
                'name' => 'Mustang 1967',
                'description' => 'The muscle car that started a revolution and became an American automotive legend.',
                'price_per_day' => 450,
                'year' => 1967,
                'image_url' => 'images/cars/r1.jpg',
            ],
            [
                'brand' => 'Mercedes-Benz',
                'name' => '190 SL 1962',
                'description' => 'A timeless German roadster built for elegance, comfort, and unforgettable journeys.',
                'price_per_day' => 700,
                'year' => 1962,
                'image_url' => 'images/cars/r2.jpg',
            ],
            [
                'brand' => 'Ferrari',
                'name' => 'F40',
                'description' => 'A legendary Italian supercar born from racing DNA, delivering pure performance and emotion.',
                'price_per_day' => 2500,
                'year' => 1987,
                'image_url' => 'images/cars/r3.jpg',
            ],
            [
                'brand' => 'Chevrolet',
                'name' => 'Corvette 1969',
                'description' => 'An American icon with a powerful V8 soul, representing the golden age of muscle cars.',
                'price_per_day' => 600,
                'year' => 1969,
                'image_url' => 'images/cars/r4.jpg',
            ],
            [
                'brand' => 'Jaguar',
                'name' => 'E-Type 1961',
                'description' => 'A masterpiece of British design, combining elegance, speed, and timeless beauty.',
                'price_per_day' => 800,
                'year' => 1961,
                'image_url' => 'images/cars/r5.jpg',
            ],
            [
                'brand' => 'Aston Martin',
                'name' => 'DB5',
                'description' => 'The legendary grand tourer famous for luxury, sophistication, and cinematic history.',
                'price_per_day' => 1500,
                'year' => 1963,
                'image_url' => 'images/cars/r6.jpg',
            ],
        ];

        foreach ($cars as $car) {
            Car::updateOrCreate(
                ['brand' => $car['brand'], 'name' => $car['name']],
                $car
            );
        }
    }
}
