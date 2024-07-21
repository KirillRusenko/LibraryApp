<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition()
    {
        $authorIds = Author::pluck('id')->toArray();
        $publishersIds = Publisher::pluck('id')->toArray();

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'publication_year' => $this->faker->year(),
            'isbn' => $this->faker->isbn13(),
            'author_id' => $this->faker->randomElement($authorIds),
            'publisher_id' => $this->faker->randomElement($publishersIds),
        ];
    }
}
