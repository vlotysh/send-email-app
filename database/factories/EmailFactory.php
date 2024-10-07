<?php

namespace Database\Factories;

use App\Models\Email;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class EmailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Email::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'recipient' => $this->faker->safeEmail,
            'subject' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
            'scheduled_time' => Carbon::now()->addMinutes($this->faker->numberBetween(1, 60)),
            'status' => 'pending',
        ];
    }
}
