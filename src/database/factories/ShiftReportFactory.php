<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ShiftReport;
class ShiftReportFactory extends Factory
{
    protected $model = ShiftReport::class;

    public function definition(): array
    {
        $shift = Shift::inRandomOrder()->first();
        $employees = Employee::inRandomOrder()->limit(rand(1, 5))->pluck('id')->toArray();
        $borrowedBooks = $this->faker->randomNumber();

        return [
            'shift_id' => $shift->id,
            'shift_date' => $shift->shift_date,
            'employee_ids' => $employees,
            'close_author' => $employees[array_rand($employees)],
            'total_books_count' => $this->faker->randomNumber() + $borrowedBooks,
            'borrowed_books_count' => $borrowedBooks,
        ];
    }
}
