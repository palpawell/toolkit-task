<?php

namespace Database\Factories;

use App\Models\Statement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class StatementFactory extends Factory
{
    protected $model = Statement::class;

    public function definition(): array
    {
        return [
            'user_id'    => 1,
            'title'      => $this->faker->text(20),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
