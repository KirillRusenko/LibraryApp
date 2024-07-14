<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Employee;
use App\Models\Publisher;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(20)->create();
        Author::factory(10)->create();
        Publisher::factory(5)->create();
        Book::factory(50)->create();
        Employee::factory(5)->create();
    }
}
