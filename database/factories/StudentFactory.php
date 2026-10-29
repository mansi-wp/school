<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'roll_number' => 'APEX-' . date('Y') . '-' . fake()->unique()->numerify('###'),
            'gender' => fake()->randomElement(['male', 'female']),
            'dob' => fake()->dateTimeBetween('-18 years', '-14 years')->format('Y-m-d'),
            'school_name' => fake()->company() . ' School',
            'current_class' => fake()->randomElement(['Class 10', 'Class 11', 'Class 12']),
            'parent_name' => fake()->name(),
            'parent_phone' => fake()->e164PhoneNumber(),
            'parent_email' => fake()->safeEmail(),
            'address_line1' => fake()->streetAddress(),
            'city' => 'Noida',
            'state' => 'Uttar Pradesh',
            'zip' => '201301',
            'admission_date' => now()->format('Y-m-d'),
            'total_fee' => 150000.00,
            'discount_amount' => 10000.00,
            'discount_reason' => 'Merit',
            'final_fee' => 140000.00,
            'paid_fee' => 50000.00,
            'outstanding_fee' => 90000.00,
            'payment_plan' => 'installment_3',
            'status' => 'active',
        ];
    }
}
