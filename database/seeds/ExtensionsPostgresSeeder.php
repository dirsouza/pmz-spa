<?php

use Illuminate\Database\Seeder;

class ExtensionsPostgresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::select('CREATE EXTENSION IF NOT EXISTS unaccent');
    }
}
