<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // En DatabaseSeeder.php o AuthorSeeder.php
        Author::factory(10)
            ->has(Book::factory()->count(rand(1, 4)))
        .create();

    }
}
