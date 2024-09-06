<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\BookItem;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookItem>
 */
class BookItemFactory extends Factory
{
    public function definition()
    {
        return [
            'book_id' => Book::inRandomOrder()->first(),
            'isbn' => fake()->isbn13(),
            'publication_year' => fake()->year(),
            'publisher_id' => Publisher::inRandomOrder()->first(),
            'last_returned_at' => fake()->optional()->dateTimeBetween('-1 year', 'now'),
            'last_borrowed_at' => fake()->optional()->dateTimeBetween('-1 year', 'now'),
            'is_borrowed' => fake()->boolean(),
            'condition' => fake()->randomElement([
                BookItem::CONDITION_GOOD,
                BookItem::CONDITION_FAIR,
                BookItem::CONDITION_POOR,
                BookItem::CONDITION_ON_REPAIR
            ]),
        ];
    }
}
