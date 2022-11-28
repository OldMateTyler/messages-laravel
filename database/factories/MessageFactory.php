<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'sent_at' => now(),
            'read_at' => now(),
            'body'=>Str::random(100),
            'author'=> \App\Models\User::all()->random()->id,
            'recipient'=> \App\Models\User::all()->random()->id,
            'thread_id'=> \App\Models\Thread::all()->random()->id,
        ];
    }
}
