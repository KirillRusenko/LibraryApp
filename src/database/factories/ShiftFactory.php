<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shift>
 */
class ShiftFactory extends Factory
{
    protected $model = Shift::class;

    public function definition()
    {
        $shiftEmployees = $this->faker->randomElements(Employee::pluck('id')->toArray(), 5);

        return [
            'shift_date' => $this->faker->date(),
            'employee_ids' => $shiftEmployees,
            'close_author' => $this->faker->randomElement($shiftEmployees),
            'notes' => $this->faker->sentence(),
        ];
    }
}
