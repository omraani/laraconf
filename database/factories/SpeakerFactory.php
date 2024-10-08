<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Speaker;
use App\Models\Talk;

class SpeakerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Speaker::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $qualificationCount = $this->faker->numberBetween(0, 10);
        $qualifications = $this->faker->randomElements(array_keys(Speaker::QUALIFICATIONS), $qualificationCount);

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'qualifications' => $qualifications,
            'bio' => $this->faker->text(),
            'twitter_handle' => $this->faker->word(),
        ];
    }

    public function withTalks(int $count = 1): self
    {
        return $this->has(Talk::factory()->count($count));
    }
}
